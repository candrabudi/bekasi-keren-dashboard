<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentReport extends Model
{
    use HasFactory;

    public function departmentTicketLogs()
    {
        return $this->hasMany(DepartmentReportLog::class, 'ticket', 'ticket');
    }
}
