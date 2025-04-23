<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentTicket;
use App\Models\District;
use App\Models\Ticket;
use App\Models\TicketLog;
use App\Models\Village;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;

class TicketController extends Controller
{
    public function index(Request $request)
    {
        $tickets = Ticket::query();

        $departmentId = null;

        // Tentukan department_id dari user atau request
        if (Auth::user()->userDetail) {
            $departmentId = Auth::user()->userDetail->department_id;
        } elseif ($request->filled('department_id')) {
            $departmentId = $request->department_id;
        }

        // Filter tiket berdasarkan department_id
        if ($departmentId) {
            $tickets->whereHas('departments', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        // Search filter
        if ($request->filled('search')) {
            $tickets->where(function ($q) use ($request) {
                $q->where('ticket', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%")
                    ->orWhere('caller', 'like', "%{$request->search}%");
            });
        }

        // Date range filter
        if ($request->filled('date_range') && str_contains($request->date_range, 'to')) {
            [$start, $end] = explode(' to ', $request->date_range);
            try {
                $startDate = Carbon::parse(trim($start))->startOfDay();
                $endDate = Carbon::parse(trim($end))->endOfDay();
                $tickets->whereBetween('created_at', [$startDate, $endDate]);
            } catch (\Exception $e) {
            }
        }

        // Wilayah filter
        if ($request->filled('district')) {
            $tickets->where('district_id', $request->district);
        }

        if ($request->filled('subdistrict')) {
            $tickets->where('subdistrict_id', $request->subdistrict);
        }

        // Status filter
        if ($request->filled('status')) {
            $tickets->where('status', $request->status);
        }

        $tickets = $tickets->latest()->paginate(25)->appends($request->except('page'));

        // --- STATISTIK YANG JUGA TERFILTER DEPARTMENT ---
        $ticketsByStatus = DB::table('tickets')
            ->join('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id')
            ->when($departmentId, function ($query) use ($departmentId) {
                $query->where('department_tickets.department_id', $departmentId);
            })
            ->select('tickets.status_name', DB::raw('COUNT(*) as total'))
            ->groupBy('tickets.status_name')
            ->get();

        $ticketsByCategories = DB::table('tickets')
            ->join('department_tickets', 'tickets.id', '=', 'department_tickets.ticket_id')
            ->when($departmentId, function ($query) use ($departmentId) {
                $query->where('department_tickets.department_id', $departmentId);
            })
            ->select('tickets.call_type_name', DB::raw('COUNT(*) as total'))
            ->groupBy('tickets.call_type_name')
            ->get();

        $districts = District::where('regency_id', 3275)->get();
        $subdistricts = [];

        if ($request->filled('district')) {
            $subdistricts = Village::where('district_id', $request->district)->get();
        }

        $departments = Department::all();

        return view('tickets.index', compact(
            'tickets', 'districts', 'subdistricts',
            'ticketsByStatus', 'ticketsByCategories', 'departments'
        ));
    }



    public function detail($a)
    {
        $ticket = Ticket::where('ticket', $a)
            ->first();
        if(!$ticket) {
            return redirect()->route('backstreet.tickets.index')->with('error', 'Sorry no data found '. $a);
        }

        return view('tickets.detail', compact('ticket'));
    }
    
    public function getVillages(Request $request)
    {
        $villages = Village::where('district_id', $request->district_id)->pluck('name', 'id');
        return response()->json($villages);
    }

    public function getByDistrict(Request $request)
    {
        $district = $request->query('district');

        $tickets = Ticket::where('district', strtoupper($district))
            ->select([
                'ticket', 'category', 'status_name', 'call_type_name',
                'caller', 'phone', 'address', 'notes', 'created_at'
            ])
            ->orderBy('created_at', 'desc')
            ->get();

        return response()->json($tickets);
    }

    public function updateTickets()
    {
        $response = Http::withHeaders([
            'accept' => '*/*',
            'Content-Type' => 'application/json',
            'X-CSRF-TOKEN' => '',
            'Authorization' => 'Bearer eyJ0eXAiOiJKV1QiLCJhbGciOiJSUzI1NiIsImp0aSI6IjNlYzRjNTRhZmM5ZTQxNDZjMTExNGRmNjkxNWE1MDFlYzUyNGM0OTU0MTQ3OGJmMjRmMTU1Y2E4YzczZjY3NTIxZjg1MTA2OWI2ZTQxMWRkIn0.eyJhdWQiOiI1IiwianRpIjoiM2VjNGM1NGFmYzllNDE0NmMxMTE0ZGY2OTE1YTUwMWVjNTI0YzQ5NTQxNDc4YmYyNGYxNTVjYThjNzNmNjc1MjFmODUxMDY5YjZlNDExZGQiLCJpYXQiOjE3NDQzNjc1ODksIm5iZiI6MTc0NDM2NzU4OSwiZXhwIjoxNzQ0NDUzOTg5LCJzdWIiOiI0NCIsInNjb3BlcyI6W119.tQB_9JpTioJd49-_w5nARAqqQpB6EJvdMAi-6BBIlJAJIfQzYvaU8OLiWqx27JnX2GZjSDLxEbJ7OoeXqSFngelnt5hrUTEp7xXOA4-stZ8Wlzq0vK5k_ycYi0KWjysc-cETQrovCIOrFQpHMfDf3iwBK9VX7hRRnnwTRTns285fOt37wRvq8HWHW6l8iCEHXcZep9wcPx99fEDhXvXqc9woYfs-HMbNdQwG-qNc32aEKiwQxO5dRzqrixQoVQtQwR8Rh2mBSUr3-IEoaruRPGvzTXCiIHMQ68wHf9KLKB3heSdlvioYVQO6C05IJ4RZi2BeTaAGDy_-F0Gjqqr0zLWRHpV6JzRaGRxQcZkCXeYZXhHsIsW_PwGRyRCcAKOkHRuMNBsGNkirn6wT3VcA5YCaekAOCdX4fIKCaUfpxe3hbXa2PWriAXvOxrBxuMVO-2PtfvO1iHXpMeWpm5FqsoL4aGPJKW6rXmtPS6XUQEBrRFiVXyIKGHj2xkhdjojDVr8jcf6rW31r2aTQyXCZAeEQtIVWIaKansQ6HJuedRp8-oqpQKOcj4xLZZxH1alprT0ALIQ87lWKpttmFr1Y5G1hbWb5owhT_zUNVE2Yh7Cn3Zth7ksF8JZ58J6ECUgsgSXRblUMcitJiqhDYR1aVYWAXHpTVtVGEpOplfClC6Y'
        ])->withCookies([
            'XSRF-TOKEN' => 'eyJpdiI6Ik80dmdyWlZ2aklyUHlqcTZmOXNHanc9PSIsInZhbHVlIjoic2l2RVhPUE...',
            'sakti_112_session' => 'eyJpdiI6IlBrZjZtVzU0N3FEY2JGWGFVZWhITnc9PSIsInZhbHVlIjoibHhFV0Nw...'
        ], 'kotabekasiv2.sakti112.id')
        ->post('https://kotabekasiv2.sakti112.id/api/v3/layer1/laporan-insiden', [
            'date' => ''
        ]);
        
        $data = $response->json();

        foreach($data as $dt) {
            $cticket = Ticket::where('ticket', $dt['ticket'])
                ->first();
            if(!$cticket) {
                $sticket = new Ticket();
                $sticket->ticket           = $dt['ticket'] ?? null;
                $sticket->channel_id       = $dt['channel_id'] ?? null;
                $sticket->category         = $dt['category'] ?? null;
                $sticket->category_id      = $dt['category_id'] ?? null;
                $sticket->status           = $dt['status'] ?? null;
                $sticket->status_name      = $dt['status_name'] ?? null;
                $sticket->call_type        = $dt['call_type'] ?? null;
                $sticket->call_type_name   = $dt['call_type_name'] ?? null;
                $sticket->caller_id        = $dt['caller_id'] ?? null;
                $sticket->phone            = $dt['phone'] ?? null;
                $sticket->phone_unmask     = $dt['phone_unmask'] ?? null;
                $sticket->caller           = $dt['caller'] ?? null;
                $sticket->created_by       = $dt['created_by'] ?? null;
                $sticket->address          = $dt['address'] ?? null;
                $sticket->location         = $dt['location'] ?? null;
                $sticket->district_id      = $dt['district_id'] ?? null;
                $sticket->district         = $dt['district'] ?? null;
                $sticket->subdistrict_id   = $dt['subdistrict_id'] ?? null;
                $sticket->subdistrict      = $dt['subdistrict'] ?? null;
                $sticket->notes            = $dt['notes'] ?? null;
                $sticket->description      = $dt['description'] ?? null;
                $sticket->created_at       = $dt['created_at'] ?? now();
                $sticket->updated_at       = $dt['updated_at'] ?? now();
                $sticket->save();
                $sticket->fresh();
                
                if (!empty($dt['dinas_terkait']) && is_array($dt['dinas_terkait'])) {
                    foreach($dt['dinas_terkait'] as $dinasterkait) {
                        $sdpt = new DepartmentTicket();
                        $sdpt->ticket_id        = $sticket->id;
                        $sdpt->report_id        = $dinasterkait['report_id'] ?? null;
                        $sdpt->department_id    = $dinasterkait['dinas_id'] ?? null;
                        $sdpt->department_name  = $dinasterkait['dinas'] ?? null;
                        $sdpt->status           = $dinasterkait['status'] ?? null;
                        $sdpt->status_name      = $dinasterkait['status_name'] ?? null;
                        $sdpt->created_at       = $dinasterkait['created_at'] ?? now();
                        $sdpt->updated_at       = $dinasterkait['updated_at'] ?? now();
                        $sdpt->save();
                    }
                }
    
                if (!empty($dt['log_ticket']) && is_array($dt['log_ticket'])) {
                    foreach($dt['log_ticket'] as $lt) {
                        $stl = new TicketLog();
                        $stl->ticket_id    = $sticket->id;
                        $stl->status       = $lt['status'] ?? null;
                        $stl->status_name  = $lt['status_name'] ?? null;
                        $stl->created_by   = $lt['created_by'] ?? null;
                        $stl->updated_by   = $lt['updated_by'] ?? null;
                        $stl->created_at   = $lt['created_at'] ?? now();
                        $stl->updated_at   = $lt['updated_at'] ?? now();
                        $stl->save();
                    }
                }
            }
            

        }
    }
}
