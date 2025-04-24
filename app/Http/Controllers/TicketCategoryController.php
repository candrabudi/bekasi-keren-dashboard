<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\Facades\DataTables;

class TicketCategoryController extends Controller
{
    public function index()
    {
        return view('ticket_categories.index');
    }

    public function getData(Request $request)
    {
        $query = DB::table('tickets')
        ->select('category', 'status_name', DB::raw('COUNT(*) as total'))
        ->where('category', '!=', '-')
        ->groupBy('category', 'status_name');
    
        $startDate = $request->filled('start_date') ? $request->start_date : Carbon::now()->startOfMonth()->toDateString();
        $endDate = $request->filled('end_date') ? $request->end_date : Carbon::now()->endOfMonth()->toDateString();
        
        $query->whereDate('created_at', '>=', $startDate)
            ->whereDate('created_at', '<=', $endDate);

        $raw = $query->get();
        $grouped = $raw->groupBy('category');

        $result = $grouped->map(function ($items, $category) {
            $totalTickets = $items->sum('total');

            $statuses = [
                'Baru' => 0,
                'Proses' => 0,
                'Selesai' => 0,
            ];

            foreach ($items as $item) {
                if (isset($statuses[$item->status_name])) {
                    $statuses[$item->status_name] = $item->total;
                }
            }

            return [
                'category' => $category,
                'total_tickets' => $totalTickets,
                'baru' => $statuses['Baru'] . ' (' . round(($statuses['Baru'] / $totalTickets) * 100, 2) . '%)',
                'proses' => $statuses['Proses'] . ' (' . round(($statuses['Proses'] / $totalTickets) * 100, 2) . '%)',
                'selesai' => $statuses['Selesai'] . ' (' . round(($statuses['Selesai'] / $totalTickets) * 100, 2) . '%)',
            ];
        })->values();

        return DataTables::of($result)->make(true);
    }
}
