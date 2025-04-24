<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DepartmentTicket extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'report_id',
        'department_id',
        'department_name',
        'status',
        'status_name',
        'created_at',
        'updated_at',
    ];
    
}
