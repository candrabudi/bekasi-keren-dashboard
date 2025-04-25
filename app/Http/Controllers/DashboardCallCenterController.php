<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DepartmentTicket;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class DashboardCallCenterController extends Controller
{

    public function dashboardCallCenter()
    {
        return view('dashboards.call-center');
    }

    public function wallboardCallCenter()
    {
        return view('dashboards.wallboard-callcenter');
    }


    public function ticketDistribution(Request $request)
    {
        $user = auth()->user();
        $departmentId = $user->detail->department_id ?? null;

        [$start, $end] = $this->getDateRange($request->date_range);

        $startDate = Carbon::parse($start)->format('Y-m-d');
        $endDate = Carbon::parse($end)->format('Y-m-d');

        $data = DB::table('tickets')
            ->where('tickets.district', '!=', '-')
            ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
            ->when($startDate === $endDate, fn($q) => $q->whereDate('tickets.created_at', $startDate))
            ->when($startDate !== $endDate, fn($q) => $q->whereDate('tickets.created_at', '>=', $startDate)->whereDate('tickets.created_at', '<=', $endDate))
            ->select('tickets.district', DB::raw('COUNT(*) as total'))
            ->groupBy('tickets.district')
            ->get();

        return response()->json($data);
    }

    public function countReports(Request $request)
    {
        $user = auth()->user();
        $departmentId = $user->detail->department_id ?? null;

        [$start, $end] = $this->getDateRange($request->date_range);

        $startDate = Carbon::parse($start)->format('Y-m-d');
        $endDate = Carbon::parse($end)->format('Y-m-d');

        $baseTickets = DB::table('tickets')
            ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
            ->when($startDate === $endDate, function ($q) use ($startDate) {
                return $q->whereDate('created_at', $startDate);
            }, function ($q) use ($startDate, $endDate) {
                return $q->whereDate('created_at', '>=', $startDate)
                    ->whereDate('created_at', '<=', $endDate);
            });

        return response()->json([
            'new' => (clone $baseTickets)->where('tickets.status', 1)->count(),
            'process' => (clone $baseTickets)->where('tickets.status', 2)->count(),
            'completed' => (clone $baseTickets)->where('tickets.status', 3)->count(),
        ]);
    }



    public function ticketCategories(Request $request)
    {
        $user = auth()->user();
        $departmentId = $user->detail->department_id ?? null;

        [$start, $end] = $this->getDateRange($request->date_range);

        $startDate = Carbon::parse($start)->format('Y-m-d');
        $endDate = Carbon::parse($end)->format('Y-m-d');

        $categories = DB::table('tickets')
            ->select('tickets.category', DB::raw('COUNT(*) as total'))
            ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
            ->when($startDate === $endDate, fn($q) => $q->whereDate('tickets.created_at', $startDate))
            ->when($startDate !== $endDate, fn($q) => $q->whereDate('tickets.created_at', '>=', $startDate)->whereDate('tickets.created_at', '<=', $endDate))
            ->groupBy('tickets.category')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return response()->json($categories);
    }

    public function ticketDistricts(Request $request)
    {
        $user = auth()->user();
        $departmentId = $user->detail->department_id ?? null;

        [$start, $end] = $this->getDateRange($request->date_range);

        $startDate = Carbon::parse($start)->format('Y-m-d');
        $endDate = Carbon::parse($end)->format('Y-m-d');

        $districts = DB::table('tickets')
            ->select('tickets.district', DB::raw('COUNT(*) as total'))
            ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
            ->when($startDate === $endDate, fn($q) => $q->whereDate('tickets.created_at', $startDate))
            ->when($startDate !== $endDate, fn($q) => $q->whereDate('tickets.created_at', '>=', $startDate)->whereDate('tickets.created_at', '<=', $endDate))
            ->groupBy('tickets.district')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return response()->json($districts);
    }

    public function ticketSubDistricts(Request $request)
    {
        $user = auth()->user();
        $departmentId = $user->detail->department_id ?? null;

        [$start, $end] = $this->getDateRange($request->date_range);

        $startDate = Carbon::parse($start)->format('Y-m-d');
        $endDate = Carbon::parse($end)->format('Y-m-d');

        $subdistricts = DB::table('tickets')
            ->select('tickets.subdistrict', DB::raw('COUNT(*) as total'))
            ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
            ->when($startDate === $endDate, fn($q) => $q->whereDate('tickets.created_at', $startDate))
            ->when($startDate !== $endDate, fn($q) => $q->whereDate('tickets.created_at', '>=', $startDate)->whereDate('tickets.created_at', '<=', $endDate))
            ->groupBy('tickets.subdistrict')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        return response()->json($subdistricts);
    }

    private function getDateRange($dateRange)
    {
        if (!$dateRange) {
            $today = Carbon::today();
            return [$today->startOfDay(), $today->endOfDay()];
        }

        $dates = explode(' to ', $dateRange);
        if (count($dates) !== 2)
            return [null, null];

        return [
            Carbon::parse($dates[0])->startOfDay(),
            Carbon::parse($dates[1])->endOfDay(),
        ];
    }


    public function chatDataHours(Request $request)
    {
        $user = auth()->user();
        $departmentId = $user->detail->department_id ?? null;
        [$start, $end] = $this->getDateRange($request->date_range);
        $ticketPerHourQuery = DB::table('tickets')
            ->selectRaw('HOUR(tickets.created_at) as hour, COUNT(*) as total')
            ->leftJoin('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id');

        if ($departmentId) {
            $ticketPerHourQuery->where('department_tickets.department_id', $departmentId);
        }

        if ($start && $end) {
            $ticketPerHourQuery->whereBetween('tickets.created_at', [$start, $end]);
        }

        $ticketPerHour = $ticketPerHourQuery
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

    public function callStatusChartData(Request $request)
    {
        $user = auth()->user();
        $departmentId = $user->detail->department_id ?? null;
        [$start, $end] = $this->getDateRange($request->date_range);

        $query = DB::table('call_center_records')
            ->select(
                DB::raw("HOUR(datetime_entry_queue) as hour"),
                'call_center_records.status',
                DB::raw("COUNT(*) as total")
            );

        if ($start && $end) {
            $query->whereBetween('datetime_entry_queue', [$start, $end]);
        }

        if ($departmentId) {
            $query->where('user_details.department_id', $departmentId);
        }

        $data = $query
            ->groupBy(DB::raw("HOUR(datetime_entry_queue)"), 'call_center_records.status')
            ->orderBy('hour')
            ->get();

        $hours = range(0, 24);
        $statuses = $data->pluck('status')->unique();
        $series = [];

        foreach ($statuses as $status) {
            $series[] = [
                'name' => $status,
                'data' => array_map(function ($hour) use ($data, $status) {
                    return (int) $data->firstWhere(fn($d) => $d->hour == $hour && $d->status == $status)?->total ?? 0;
                }, $hours)
            ];
        }

        return response()->json([
            'series' => $series,
            'categories' => array_map(fn($h) => str_pad($h, 2, '0', STR_PAD_LEFT) . ':00', $hours),
        ]);
    }


}
