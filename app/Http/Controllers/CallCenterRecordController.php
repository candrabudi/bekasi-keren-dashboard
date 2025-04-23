<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CallCenterRecord;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CallCenterRecordController extends Controller
{
    public function index(Request $request)
    {
        $today = Carbon::today();
        $monthStart = Carbon::now()->startOfMonth();

        $stats = [
            'total_today' => CallCenterRecord::whereDate('datetime_init', $today)->count(),
            'total_month' => CallCenterRecord::whereBetween('datetime_init', [$monthStart, now()])->count(),
            'avg_duration' => CallCenterRecord::avg('duration'),
            'avg_wait' => CallCenterRecord::avg('duration_wait'),
            'status_distribution' => CallCenterRecord::select('status', DB::raw('count(*) as total'))
                                        ->groupBy('status')
                                        ->get()
        ];

        
       $callCenterRecords = CallCenterRecord::query()
        ->when($request->filled('search'), function ($query) use ($request) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('phone', 'like', "%{$search}%")
                  ->orWhere('agent_name', 'like', "%{$search}%")
                  ->orWhere('uniqueid', 'like', "%{$search}%");
            });
        })
        ->when($request->filled('date_range'), function ($query) use ($request) {
            $range = explode(' to ', $request->date_range);
            if (count($range) === 2) {
                [$start, $end] = $range;
                $query->whereBetween('datetime_entry_queue', [
                    Carbon::parse($start)->startOfDay(),
                    Carbon::parse($end)->endOfDay(),
                ]);
            }
        })
        
        ->when($request->filled('status'), fn($query) => $query->where('status', $request->status))
        ->when($request->filled('min_wait'), fn($query) => $query->where('duration_wait', '>=', (int) $request->min_wait))
        ->when($request->filled('min_duration'), fn($query) => $query->where('duration', '>=', (int) $request->min_duration))
        ->latest()
        ->paginate(25)
        ->appends($request->except('page'));
    
        return view('call-records.index', compact('callCenterRecords', 'stats'));
    }
}
