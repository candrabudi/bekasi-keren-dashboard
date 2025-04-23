<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CallCenterRecord;
use App\Models\Department;
use App\Models\DepartmentTicket;
use App\Models\Menu;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function dashboardCallCenter()
    {
        $user = auth()->user();
        $departmentId = $user->detail->department_id ?? null;

        $baseTickets = Ticket::join('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id');

        if ($departmentId) {
            $baseTickets->where('department_tickets.department_id', $departmentId);
        }

        $tickets = $baseTickets->get();

        $countNewReports = (clone $baseTickets)->where('tickets.status', 1)->count();
        $countProcessReports = (clone $baseTickets)->where('tickets.status', 2)->count();
        $countCompletedReports = (clone $baseTickets)->where('tickets.status', 3)->count();

        $ticketCategories = DB::table('tickets')
            ->join('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id')
            ->select('tickets.category', DB::raw('COUNT(*) as total'))
            ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
            ->groupBy('tickets.category')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $ticketDistricts = DB::table('tickets')
            ->join('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id')
            ->select('tickets.district', DB::raw('COUNT(*) as total'))
            ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
            ->groupBy('tickets.district')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $ticketSubDistricts = DB::table('tickets')
            ->join('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id')
            ->select('tickets.subdistrict', DB::raw('COUNT(*) as total'))
            ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
            ->groupBy('tickets.subdistrict')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // return $ticketSubDistricts;
        $data = DB::table('tickets')
            ->join('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id')
            ->where('tickets.district', '!=', '-')
            ->select('tickets.district', DB::raw('COUNT(*) as total'))
            ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
            ->groupBy('tickets.district')
            ->get();

        return view('dashboards.call-center', compact(
            'countNewReports', 'countProcessReports',
            'countCompletedReports', 'ticketCategories',
            'ticketDistricts', 'ticketSubDistricts', 'data'
        ));
    }
    
    public function dashboardCallCenterBackup()
    {
        $user = auth()->user();
        $departmentId = $user->detail->department_id ?? null;

        $baseTickets = Ticket::join('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id');

        if ($departmentId) {
            $baseTickets->where('department_tickets.department_id', $departmentId);
        }

        $tickets = $baseTickets->get();

        $countNewReports = (clone $baseTickets)->where('tickets.status', 1)->count();
        $countProcessReports = (clone $baseTickets)->where('tickets.status', 2)->count();
        $countCompletedReports = (clone $baseTickets)->where('tickets.status', 3)->count();

        $ticketCategories = DB::table('tickets')
            ->join('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id')
            ->select('tickets.category', DB::raw('COUNT(*) as total'))
            ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
            ->groupBy('tickets.category')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $ticketDistricts = DB::table('tickets')
            ->join('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id')
            ->select('tickets.district', DB::raw('COUNT(*) as total'))
            ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
            ->groupBy('tickets.district')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        $ticketSubDistricts = DB::table('tickets')
            ->join('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id')
            ->select('tickets.subdistrict', DB::raw('COUNT(*) as total'))
            ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
            ->groupBy('tickets.subdistrict')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // return $ticketSubDistricts;
        $data = DB::table('tickets')
            ->join('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id')
            ->where('tickets.district', '!=', '-')
            ->select('tickets.district', DB::raw('COUNT(*) as total'))
            ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
            ->groupBy('tickets.district')
            ->get();

        return view('dashboards.call-center', compact(
            'countNewReports', 'countProcessReports',
            'countCompletedReports', 'ticketCategories',
            'ticketDistricts', 'ticketSubDistricts', 'data'
        ));
    }


    public function chartDataPerJam()
    {
        $today = \Carbon\Carbon::today();
        $user = auth()->user();
        $departmentId = $user->detail->department_id ?? null;

        $ticketPerHour = DB::table('tickets')
            ->join('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id')
            ->select(DB::raw('HOUR(tickets.created_at) as hour'), DB::raw('COUNT(*) as total'))
            ->whereDate('tickets.created_at', $today);

        if ($departmentId) {
            $ticketPerHour->where('department_tickets.department_id', $departmentId);
        }

        $ticketPerHour = $ticketPerHour
            ->groupBy(DB::raw('HOUR(tickets.created_at)'))
            ->orderBy('hour')
            ->get();

        $hours = range(0, 23);
        $data = [];

        foreach ($hours as $h) {
            $data[] = $ticketPerHour->firstWhere('hour', $h)->total ?? 0;
        }

        return response()->json([
            'labels' => array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', $hours),
            'series' => $data
        ]);
    }

    public function callStatusChartData()
    {
        $date = now()->toDateString();
        $user = auth()->user();
        $departmentId = $user->detail->department_id ?? null;

        $query = DB::table('call_center_records')
            ->join('users', 'call_center_records.created_by', '=', 'users.id')
            ->join('user_details', 'users.id', '=', 'user_details.user_id')
            ->select(
                DB::raw("HOUR(datetime_entry_queue) as hour"),
                'status',
                DB::raw("COUNT(*) as total")
            )
            ->whereDate('datetime_entry_queue', $date);

        if ($departmentId) {
            $query->where('user_details.department_id', $departmentId);
        }

        $data = $query
            ->groupBy(DB::raw("HOUR(datetime_entry_queue)"), 'status')
            ->orderBy('hour')
            ->get();

        $hours = range(0, 23);
        $statuses = $data->pluck('status')->unique();
        $series = [];

        foreach ($statuses as $status) {
            $series[] = [
                'name' => $status,
                'data' => array_map(function ($hour) use ($data, $status) {
                    return (int) $data->firstWhere(fn ($d) => $d->hour == $hour && $d->status == $status)?->total ?? 0;
                }, $hours)
            ];
        }

        return response()->json([
            'series' => $series,
            'categories' => array_map(fn ($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', $hours),
        ]);
    }




    public function updateReportCrd()
    {
        set_time_limit(60000);
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Content-Type' => 'application/json',
            'X-CSRF-TOKEN' => '',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjNlYzRjNTRhZmM5ZTQxNDZjMTExNGRmNjkxNWE1MDFlYzUyNGM0OTU0MTQ3OGJmMjRmMTU1Y2E4YzczZjY3NTIxZjg1MTA2OWI2ZTQxMWRkIn0.eyJhdWQiOiI1IiwianRpIjoiM2VjNGM1NGFmYzllNDE0NmMxMTE0ZGY2OTE1YTUwMWVjNTI0YzQ5NTQxNDc4YmYyNGYxNTVjYThjNzNmNjc1MjFmODUxMDY5YjZlNDExZGQiLCJpYXQiOjE3NDQzNjc1ODksIm5iZiI6MTc0NDM2NzU4OSwiZXhwIjoxNzQ0NDUzOTg5LCJzdWIiOiI0NCIsInNjb3BlcyI6W119.tQB_9JpTioJd49-_w5nARAqqQpB6EJvdMAi-6BBIlJAJIfQzYvaU8OLiWqx27JnX2GZjSDLxEbJ7OoeXqSFngelnt5hrUTEp7xXOA4-stZ8Wlzq0vK5k_ycYi0KWjysc-cETQrovCIOrFQpHMfDf3iwBK9VX7hRRnnwTRTns285fOt37wRvq8HWHW6l8iCEHXcZep9wcPx99fEDhXvXqc9woYfs-HMbNdQwG-qNc32aEKiwQxO5dRzqrixQoVQtQwR8Rh2mBSUr3-IEoaruRPGvzTXCiIHMQ68wHf9KLKB3heSdlvioYVQO6C05IJ4RZi2BeTaAGDy_-F0Gjqqr0zLWRHpV6JzRaGRxQcZkCXeYZXhHsIsW_PwGRyRCcAKOkHRuMNBsGNkirn6wT3VcA5YCaekAOCdX4fIKCaUfpxe3hbXa2PWriAXvOxrBxuMVO-2PtfvO1iHXpMeWpm5FqsoL4aGPJKW6rXmtPS6XUQEBrRFiVXyIKGHj2xkhdjojDVr8jcf6rW31r2aTQyXCZAeEQtIVWIaKansQ6HJuedRp8-oqpQKOcj4xLZZxH1alprT0ALIQ87lWKpttmFr1Y5G1hbWb5owhT_zUNVE2Yh7Cn3Zth7ksF8JZ58J6ECUgsgSXRblUMcitJiqhDYR1aVYWAXHpTVtVGEpOplfClC6Y'
        ])->withCookies([
            'XSRF-TOKEN' => 'eyJpdiI6Ik80dmdyWlZ2aklyUHlqcTZmOXNHanc9PSIsInZhbHVlIjoic2l2RVhPUE...',
            'sakti_112_session' => 'eyJpdiI6IlBrZjZtVzU0N3FEY2JGWGFVZWhITnc9PSIsInZhbHVlIjoibHhFV0Nw...'
        ], 'kotabekasiv2.sakti112.id')
        ->post('https://kotabekasiv2.sakti112.id/api/v3/layer1/laporan-cdr', [
            'date' => '2025-04-11'
        ]);
        
        $data = $response->json();
        // return $data;
        foreach($data as $dt) {
            // return $dt;
            $cticket = CallCenterRecord::where('uniqueid', $dt['uniqueid'])
                ->first();
            if(!$cticket) {
                $sticket = new CallCenterRecord();
                $sticket->phone = $dt['phone'];
                $sticket->datetime_entry_queue = $dt['datetime_entry_queue'];
                $sticket->duration_wait = $dt['duration_wait'];
                $sticket->datetime_init = $dt['datetime_init'];
                $sticket->datetime_end = $dt['datetime_end'];
                $sticket->duration = $dt['duration'];
                $sticket->status = $dt['status'];
                $sticket->uniqueid = $dt['uniqueid'];
                $sticket->extension = $dt['extension'];
                $sticket->agent_name = $dt['nama_agen'];
                $sticket->recording_file = $dt['recording_file'];
                $sticket->save();
                $sticket->fresh();
            }
            

        }
    }
}
