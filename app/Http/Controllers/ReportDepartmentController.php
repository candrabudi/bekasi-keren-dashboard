<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\DepartmentTicket;
use Illuminate\Http\Request;

class ReportDepartmentController extends Controller
{
    public function index()
    {
        $departmentTickets = DepartmentTicket::all();
    }
}
