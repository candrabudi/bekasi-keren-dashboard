<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TicketLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'ticket_id',
        'status',
        'status_name',
        'created_by',
        'updated_by',
        'created_at',
        'updated_at',
    ];
    
}
