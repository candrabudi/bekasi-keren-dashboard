<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Department;
use App\Models\DepartmentReport;
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

        if (Auth::user()->userDetail) {
            $departmentId = Auth::user()->userDetail->department_id;
        } elseif ($request->filled('department_id')) {
            $departmentId = $request->department_id;
        }

        if ($departmentId) {
            $tickets->whereHas('departments', function ($q) use ($departmentId) {
                $q->where('department_id', $departmentId);
            });
        }

        if ($request->filled('search')) {
            $tickets->where(function ($q) use ($request) {
                $q->where('ticket', 'like', "%{$request->search}%")
                    ->orWhere('phone', 'like', "%{$request->search}%")
                    ->orWhere('caller', 'like', "%{$request->search}%");
            });
        }

        if ($request->filled('date_range') && str_contains($request->date_range, 'to')) {
            [$start, $end] = explode(' to ', $request->date_range);
            try {
                $startDate = Carbon::parse(trim($start))->startOfDay();
                $endDate = Carbon::parse(trim($end))->endOfDay();
                $tickets->whereBetween('created_at', [$startDate, $endDate]);
            } catch (\Exception $e) {
            }
        }

        if ($request->filled('district')) {
            $tickets->where('district_id', $request->district);
        }

        if ($request->filled('subdistrict')) {
            $tickets->where('subdistrict_id', $request->subdistrict);
        }

        if ($request->filled('status')) {
            $tickets->where('status', $request->status);
        }

        $tickets = $tickets->latest()->paginate(25)->appends($request->except('page'));

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

        $departmentReports = DepartmentReport::where('ticket', $ticket->ticket)
            ->with('departmentTicketLogs')
            ->get();

            // return $departmentReports;
        return view('tickets.detail', compact('ticket', 'departmentReports'));
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
}
