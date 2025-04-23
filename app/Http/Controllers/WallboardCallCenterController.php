<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class WallboardCallCenterController extends Controller
{
    public function wallBoardGetSummaryCall(Request $request)
    {
        $dateRangeString = $request->input('date_range');

        if ($dateRangeString) {
            $dates = explode(' to ', $dateRangeString);
            try {
                $start = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->format('d/m/Y');
                $end = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->format('d/m/Y');
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Invalid date format',
                    'details' => $e->getMessage(),
                ], 400);
            }
        } else {
            $start = Carbon::now()->startOfMonth()->format('d/m/Y');
            $end = Carbon::now()->endOfMonth()->format('d/m/Y');
        }

        $response = Http::get('https://kotabekasiv2.sakti112.id/master/wallboard/get-summary-call', [
            'date' => [$start, $end], 
        ]);

        return response()->json($response->json());
    }


    public function getSummaryInsiden(Request $request)
    {
        $dateRangeString = $request->input('date_range');

        if ($dateRangeString) {
            $dates = explode(' to ', $dateRangeString);
            try {
                $start = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->format('d/m/Y');
                $end = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->format('d/m/Y');
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Invalid date format',
                    'details' => $e->getMessage(),
                ], 400);
            }
        } else {
            $start = Carbon::now()->startOfMonth()->format('d/m/Y');
            $end = Carbon::now()->endOfMonth()->format('d/m/Y');
        }

        $response = Http::get('https://kotabekasiv2.sakti112.id/master/wallboard/get-summary-insiden', [
            'date' => [$start, $end],
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json([
                'error' => 'Request failed',
                'message' => $response->body(),
            ], $response->status());
        }
    }

    public function getTopCategories(Request $request)
    {
        $dateRangeString = $request->input('date_range');

        if ($dateRangeString) {
            $dates = explode(' to ', $dateRangeString);
            try {
                $start = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->format('d/m/Y');
                $end = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->format('d/m/Y');
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Invalid date format',
                    'details' => $e->getMessage(),
                ], 400);
            }
        } else {
            $start = Carbon::now()->startOfMonth()->format('d/m/Y');
            $end = Carbon::now()->endOfMonth()->format('d/m/Y');
        }

        $response = Http::get('https://kotabekasiv2.sakti112.id/master/wallboard/get-top-categories', [
            'date' => [$start, $end],
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Request failed'], 500);
        }
    }

    public function getTopArea(Request $request)
    {
        $dateRangeString = $request->input('date_range');

        if ($dateRangeString) {
            $dates = explode(' to ', $dateRangeString);
            try {
                $start = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->format('d/m/Y');
                $end = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->format('d/m/Y');
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Invalid date format',
                    'details' => $e->getMessage(),
                ], 400);
            }
        } else {
            $start = Carbon::now()->startOfMonth()->format('d/m/Y');
            $end = Carbon::now()->endOfMonth()->format('d/m/Y');
        }

        $response = Http::get('https://kotabekasiv2.sakti112.id/master/wallboard/get-top-area', [
            'date' => [$start, $end],
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Request failed'], 500);
        }
    }

    public function getOnlineDepartment(Request $request)
    {
        $dateRangeString = $request->input('date_range');

        if ($dateRangeString) {
            $dates = explode(' to ', $dateRangeString);
            try {
                $start = Carbon::createFromFormat('d/m/Y', trim($dates[0]))->format('d/m/Y');
                $end = Carbon::createFromFormat('d/m/Y', trim($dates[1]))->format('d/m/Y');
            } catch (\Exception $e) {
                return response()->json([
                    'error' => 'Invalid date format',
                    'details' => $e->getMessage(),
                ], 400);
            }
        } else {
            $start = Carbon::now()->startOfMonth()->format('d/m/Y');
            $end = Carbon::now()->endOfMonth()->format('d/m/Y');
        }

        $response = Http::get('https://kotabekasiv2.sakti112.id/master/wallboard/get-dinas-online', [
            'date' => [$start, $end],
        ]);

        if ($response->successful()) {
            return response()->json($response->json());
        } else {
            return response()->json(['error' => 'Request failed'], 500);
        }
    }
}
