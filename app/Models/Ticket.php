<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'ticket',
        'channel_id',
        'category',
        'category_id',
        'status',
        'status_name',
        'call_type',
        'call_type_name',
        'caller_id',
        'phone',
        'phone_unmask',
        'caller',
        'created_by',
        'address',
        'location',
        'district_id',
        'district',
        'subdistrict_id',
        'subdistrict',
        'notes',
        'description',
        'created_at',
        'updated_at',
    ];
    

    public function departmentTickets()
    {
        return $this->hasMany(DepartmentTicket::class, 'ticket_id', 'id');
    }

    public function ticketLogs()
    {
        return $this->hasMany(TicketLog::class, 'ticket_id', 'id');
    }

    public function departments()
    {
        return $this->hasMany(DepartmentTicket::class, 'ticket_id');
    }

}
