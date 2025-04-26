<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Ticket;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class CallCenterController extends Controller
{
    public function map()
    {
        return view('call-center.map');
    }

    public function ticketDistribution(Request $request)
{
    $user = auth()->user();
    $departmentId = $user->detail->department_id ?? null;
    $startDate = $request->start_date;
    $endDate = $request->end_date;

    // Validasi tanggal
    if (!$startDate || !$endDate) {
        return response()->json(['error' => 'Invalid date range'], 400);
    }

    // Format tanggal
    $startDate = Carbon::parse($startDate)->format('Y-m-d');
    $endDate = Carbon::parse($endDate)->format('Y-m-d');

    // Ambil data jumlah tiket per distrik
    $districtData = DB::table('tickets')
        ->where('district', '!=', '-')
        ->when($departmentId, fn($q) => $q->where('department_tickets.department_id', $departmentId))
        ->whereBetween('tickets.created_at', [$startDate, $endDate])
        ->select('district', DB::raw('COUNT(*) as total'))
        ->groupBy('district')
        ->get();

    // Ambil detail tiket berdasarkan district
    $districtsWithDetails = $districtData->map(function ($district) {
        $tickets = DB::table('tickets')
            ->where('district', $district->district)
            ->select('id', 'ticket', 'address', 'district', 'phone','created_at', 'status_name') // Kolom tiket yang ingin diambil
            ->get();

        $district->tickets = $tickets;
        return $district;
    });

    return response()->json($districtsWithDetails);
}


}
