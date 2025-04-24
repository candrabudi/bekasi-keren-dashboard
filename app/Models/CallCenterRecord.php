<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CallCenterRecord extends Model
{
    use HasFactory;

    protected $fillable = [
        'phone',
        'datetime_entry_queue',
        'duration_wait',
        'datetime_init',
        'datetime_end',
        'duration',
        'status',
        'uniqueid',
        'extension',
        'agent_name',
        'recording_file',
        'created_at',
        'updated_at',
    ];
}
