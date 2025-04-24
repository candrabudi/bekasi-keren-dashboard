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
use Carbon\CarbonPeriod;
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
                return response()->json(['message' => 'Token tidak ditemukan.'], 500);
            }
    
            $startDate = Carbon::now()->subDays(2)->startOfDay();
            $endDate = Carbon::now()->endOfDay();
            $period = CarbonPeriod::create($startDate, $endDate);
    
            $allIncidentData = [];
    
            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');
    
                $response = Http::withHeaders([
                    'Accept' => '*/*',
                    'Content-Type' => 'application/json',
                    'Authorization' => 'Bearer ' . $accessToken,
                ])
                    ->timeout(5000)
                    ->retry(5, 5000)
                    ->post('https://kotabekasiv2.sakti112.id/api/v3/layer1/laporan-insiden', [
                        'date' => $dateStr,
                    ]);
    
                if ($response->successful()) {
                    $incidentData = $response->json();
    
                    if (is_array($incidentData)) {
                        $allIncidentData = array_merge($allIncidentData, $incidentData);
                    }
                }
            }
    
            foreach ($allIncidentData as $dt) {
                if (!Ticket::where('ticket', $dt['ticket'])->exists()) {
                    $ticket = Ticket::create([
                        'ticket' => $dt['ticket'] ?? null,
                        'channel_id' => $dt['channel_id'] ?? null,
                        'category' => $dt['category'] ?? null,
                        'category_id' => $dt['category_id'] ?? null,
                        'status' => $dt['status'] ?? null,
                        'status_name' => $dt['status_name'] ?? null,
                        'call_type' => $dt['call_type'] ?? null,
                        'call_type_name' => $dt['call_type_name'] ?? null,
                        'caller_id' => $dt['caller_id'] ?? null,
                        'phone' => $dt['phone'] ?? null,
                        'phone_unmask' => $dt['phone_unmask'] ?? null,
                        'caller' => $dt['caller'] ?? null,
                        'created_by' => $dt['created_by'] ?? null,
                        'address' => $dt['address'] ?? null,
                        'location' => $dt['location'] ?? null,
                        'district_id' => $dt['district_id'] ?? null,
                        'district' => $dt['district'] ?? null,
                        'subdistrict_id' => $dt['subdistrict_id'] ?? null,
                        'subdistrict' => $dt['subdistrict'] ?? null,
                        'notes' => $dt['notes'] ?? null,
                        'description' => $dt['description'] ?? null,
                        'created_at' => $dt['created_at'] ?? now(),
                        'updated_at' => $dt['updated_at'] ?? now(),
                    ]);
    
                    if (!empty($dt['dinas_terkait'])) {
                        $departments = collect($dt['dinas_terkait'])->map(function ($item) use ($ticket) {
                            return [
                                'ticket_id' => $ticket->id,
                                'report_id' => $item['report_id'] ?? null,
                                'department_id' => $item['dinas_id'] ?? null,
                                'department_name' => $item['dinas'] ?? null,
                                'status' => $item['status'] ?? null,
                                'status_name' => $item['status_name'] ?? null,
                                'created_at' => $item['created_at'] ?? now(),
                                'updated_at' => $item['updated_at'] ?? now(),
                            ];
                        })->toArray();
    
                        DepartmentTicket::insert($departments);
                    }
    
                    if (!empty($dt['log_ticket'])) {
                        $logs = collect($dt['log_ticket'])->map(function ($log) use ($ticket) {
                            return [
                                'ticket_id' => $ticket->id,
                                'status' => $log['status'] ?? null,
                                'status_name' => $log['status_name'] ?? null,
                                'created_by' => $log['created_by'] ?? null,
                                'updated_by' => $log['updated_by'] ?? null,
                                'created_at' => $log['created_at'] ?? now(),
                                'updated_at' => $log['updated_at'] ?? now(),
                            ];
                        })->toArray();
    
                        TicketLog::insert($logs);
                    }
                }
            }
    
            return response()->json([
                'message' => 'Data berhasil disinkronisasi.',
                'total_data' => count($allIncidentData)
            ]);
    
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

            $startDate = Carbon::now()->subDays(2)->startOfDay();
            $endDate = Carbon::now()->endOfDay();

            $period = CarbonPeriod::create($startDate, $endDate);

            $allIncidentData = [];

            foreach ($period as $date) {
                $dateStr = $date->format('Y-m-d');

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
                    Log::error("Gagal ambil data CDR untuk tanggal: $dateStr", [
                        'status' => $incidentResponse->status(),
                        'body' => $incidentResponse->body()
                    ]);
                    continue;
                }

                $incidentData = $incidentResponse->json();

                if (!isset($incidentData) || !is_array($incidentData)) {
                    Log::warning("Format data tidak sesuai di tanggal: $dateStr", ['data' => $incidentData]);
                    continue;
                }

                $allIncidentData = array_merge($allIncidentData, $incidentData);
            }
            foreach ($allIncidentData as $dt) {
                $cticket = CallCenterRecord::where('uniqueid', $dt['uniqueid'])
                    ->first();

                if (!$cticket) {
                    CallCenterRecord::create([
                        'phone' => $dt['phone'],
                        'datetime_entry_queue' => $dt['datetime_entry_queue'],
                        'duration_wait' => $dt['duration_wait'],
                        'datetime_init' => $dt['datetime_init'] ?? $dt['datetime_end'],
                        'datetime_end' => $dt['datetime_end'],
                        'duration' => $dt['duration'],
                        'status' => $dt['status'],
                        'uniqueid' => $dt['uniqueid'],
                        'extension' => $dt['extension'],
                        'agent_name' => $dt['nama_agen'],
                        'recording_file' => $dt['recording_file'],
                        'created_at' => $dt['datetime_init'] ?? $dt['datetime_end'],
                        'updated_at' => $dt['datetime_end'],
                    ]);
                } else {
                    Log::info("Data sudah ada untuk uniqueid: {$dt['uniqueid']}");
                }

                $aa[] = $dt;
            }

            return count($allIncidentData);

        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Terjadi kesalahan saat koneksi',
                'error' => $e->getMessage()
            ], 500);
        }
    }
    
    function simpanCallCenterRecord(array $dataList)
    {
        foreach ($dataList as $dt) {
            $cticket = CallCenterRecord::where('uniqueid', $dt['uniqueid'])
                ->where('created_at', $dt['datetime_init'])
                ->first();

            if (!$cticket) {
                CallCenterRecord::create([
                    'phone' => $dt['phone'],
                    'datetime_entry_queue' => $dt['datetime_entry_queue'],
                    'duration_wait' => $dt['duration_wait'],
                    'datetime_init' => $dt['datetime_init'],
                    'datetime_end' => $dt['datetime_end'],
                    'duration' => $dt['duration'],
                    'status' => $dt['status'],
                    'uniqueid' => $dt['uniqueid'],
                    'extension' => $dt['extension'],
                    'agent_name' => $dt['nama_agen'],
                    'recording_file' => $dt['recording_file'],
                    'created_at' => $dt['datetime_init'],
                    'updated_at' => $dt['datetime_end'],
                ]);
            } else {
                Log::info("Data sudah ada untuk uniqueid: {$dt['uniqueid']}");
            }
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

            foreach ($departmentTickets as $dpt) {
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
