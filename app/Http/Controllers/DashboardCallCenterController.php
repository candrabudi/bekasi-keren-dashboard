<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DepartmentTicket;
use App\Models\Ticket;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class DashboardCallCenterController extends Controller
{
    public function wallboardCallCenter()
    {
        return view('dashboards.wallboard-callcenter');
    }
}
