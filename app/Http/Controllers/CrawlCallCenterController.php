<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\CallCenterRecord;
use App\Models\DepartmentReport;
use App\Models\DepartmentReportLog;
use App\Models\DepartmentTicket;
use App\Models\Ticket;
use App\Models\TicketLog;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CrawlCallCenterController extends Controller
{
    public function crawlTodayIncidents()
    {
        set_time_limit(6000);
        date_default_timezone_set('Asia/Jakarta');
        try {
            $loginResponse = Http::withHeaders([
                'Accept' => 'application/json',
            ])->post('https://kotabekasiv2.sakti112.id/api/services/login', [
                'username' => 'apikotabekasi@sakti112.id',
                'password' => '_cH_h8_cLnGYBQH',
            ]);

            if (!$loginResponse->successful()) {
                return response()->json([
                    'message' => 'Login gagal',
                    'status' => $loginResponse->status(),
                    'body' => $loginResponse->body()
                ], $loginResponse->status());
            }

            $loginData = $loginResponse->json();
            $accessToken = $loginData['content']['access_token'] ?? null;

            if (!$accessToken) {
                return response()->json([
                    'message' => 'Token tidak ditemukan pada response login.'
                ], 500);
            }

            $startDate = Carbon::createFromFormat('Y-m-d', '2024-12-11')->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            while ($startDate->lte($endDate)) {
                $dateStr = $startDate->format('Y-m-d');
            
                $incidentResponse = Http::withHeaders([
                    'Accept' => '*/*',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken
                ])
                ->timeout(5000)
                ->retry(5, 5000)
                ->post('https://kotabekasiv2.sakti112.id/api/v3/layer1/laporan-insiden', [
                    'date' => $dateStr
                ]);
            
                if (!$incidentResponse->successful()) {
                    Log::error('Gagal ambil data insiden untuk tanggal: ' . $dateStr, [
                        'status' => $incidentResponse->status(),
                        'body' => $incidentResponse->body()
                    ]);
                    
                    $startDate->addDay();
                    continue;
                }
            
                $incidentData = $incidentResponse->json();
            
                foreach($incidentData as $dt) {
                    $cticket = Ticket::where('ticket', $dt['ticket'])->first();
            
                    if (!$cticket) {
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
                            foreach ($dt['dinas_terkait'] as $dinasterkait) {
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
                            foreach ($dt['log_ticket'] as $lt) {
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
                $startDate->addDay();
            }
            
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat koneksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }

    public function crawlCallDetailRecord()
    {
        set_time_limit(6000);
        date_default_timezone_set('Asia/Jakarta');
    
        try {
            $loginResponse = Http::withHeaders([
                'Accept' => 'application/json',
            ])
            ->timeout(5000)
            ->retry(5, 5000)
            ->post('https://kotabekasiv2.sakti112.id/api/services/login', [
                'username' => 'apikotabekasi@sakti112.id',
                'password' => '_cH_h8_cLnGYBQH',
            ]);
    
            if (!$loginResponse->successful()) {
                return response()->json([
                    'message' => 'Login gagal',
                    'status' => $loginResponse->status(),
                    'body' => $loginResponse->body()
                ], $loginResponse->status());
            }
    
            $loginData = $loginResponse->json();
            $accessToken = $loginData['content']['access_token'] ?? null;
    
            if (!$accessToken) {
                return response()->json([
                    'message' => 'Token tidak ditemukan pada response login.'
                ], 500);
            }
    
            $startDate = Carbon::now()->subDays(3)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
    
            while ($startDate->lte($endDate)) {
                $dateStr = $startDate->format('Y-m-d');
    
                $incidentResponse = Http::withHeaders([
                    'Accept' => '*/*',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken,
                    'X-CSRF-TOKEN' => ''
                ])
                ->timeout(5000)
                ->retry(5, 5000)
                ->post('https://kotabekasiv2.sakti112.id/api/v3/layer1/laporan-cdr', [
                    'date' => $dateStr,
                ]);
    
                if (!$incidentResponse->successful()) {
                    Log::error('Gagal ambil data CDR untuk tanggal: ' . $dateStr, [
                        'status' => $incidentResponse->status(),
                        'body' => $incidentResponse->body()
                    ]);
    
                    $startDate->addDay();
                    continue;
                }
    
                $incidentData = $incidentResponse->json();
    
                foreach ($incidentData as $dt) {
                    $cticket = CallCenterRecord::where('uniqueid', $dt['uniqueid'])->first();
    
                    if (!$cticket) {
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
                        $sticket->created_at = $dt['datetime_init'];
                        $sticket->updated_at = $dt['datetime_end'];
                        $sticket->save();
                        $sticket->fresh();
                    }
                }
    
                $startDate->addDay();
            }
    
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat koneksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    public function updateReportDepartment()
    {
        set_time_limit(6000);
        date_default_timezone_set('Asia/Jakarta');

        try {
            $loginResponse = Http::withHeaders([
                'Accept' => 'application/json',
            ])
            ->timeout(5000)
            ->retry(5, 5000)
            ->post('https://kotabekasiv2.sakti112.id/api/services/login', [
                'username' => 'apikotabekasi@sakti112.id',
                'password' => '_cH_h8_cLnGYBQH',
            ]);

            if (!$loginResponse->successful()) {
                return response()->json([
                    'message' => 'Login gagal',
                    'status' => $loginResponse->status(),
                    'body' => $loginResponse->body()
                ], $loginResponse->status());
            }

            $accessToken = $loginResponse->json()['content']['access_token'] ?? null;
            if (!$accessToken) {
                return response()->json(['message' => 'Token tidak ditemukan pada response login.'], 500);
            }

            $departmentTickets = DepartmentTicket::all();

            foreach($departmentTickets as $dpt) {
                $reportDepartment = Http::withHeaders([
                    'Accept' => '*/*',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken,
                ])
                ->timeout(5000)
                ->retry(5, 5000)
                ->post('https://kotabekasiv2.sakti112.id/api/v3/layer1/status-laporan-dinas', [
                    'report_id' => $dpt->report_id,
                ]);

                $dts = $reportDepartment->json();
                $report = $dts[0] ?? null;

                if ($report) {
                    // Cek duplicate DepartmentReport
                    $existingReport = DepartmentReport::where('department_id', $report['dinas_id'])
                        ->where('ticket', $report['ticket'])
                        ->where('status', $report['status'])
                        ->first();

                    if (!$existingReport) {
                        $sdpt = new DepartmentReport();
                        $sdpt->report_id = $report['report_id'];
                        $sdpt->ticket = $report['ticket'];
                        $sdpt->status = $report['status'];
                        $sdpt->status_name = $report['status_name'];
                        $sdpt->department_id = $report['dinas_id'];
                        $sdpt->department = $report['dinas'];
                        $sdpt->created_by = $report['created_by'];
                        $sdpt->description = $report['description'];
                        $sdpt->created_at = $report['created_at'];
                        $sdpt->updated_at = $report['updated_at'];
                        $sdpt->save();
                        $sdpt->fresh();
                    } else {
                        $sdpt = $existingReport;
                    }

                    // Insert logs jika belum ada
                    if (!empty($report['detail_log']) && is_array($report['detail_log'])) {
                        foreach ($report['detail_log'] as $dtlog) {
                            $exists = DepartmentReportLog::where('report_id', $sdpt->id)
                                ->where('ticket', $dtlog['ticket'])
                                ->where('status', $dtlog['status'])
                                ->where('created_at', $dtlog['created_at'])
                                ->exists();

                            if (!$exists) {
                                $slog = new DepartmentReportLog();
                                $slog->report_id = $sdpt->id;
                                $slog->ticket = $dtlog['ticket'];
                                $slog->status = $dtlog['status'];
                                $slog->status_name = $dtlog['status_name'];
                                $slog->created_by = $dtlog['created_by'];
                                $slog->description = $dtlog['description'];
                                $slog->notes = $dtlog['l2_notes'];
                                $slog->created_at = $dtlog['created_at'];
                                $slog->updated_at = $dtlog['updated_at'];
                                $slog->save();
                            }
                        }
                    }
                }
            }

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat koneksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }


}
