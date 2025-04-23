<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;

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
