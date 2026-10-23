<?php

namespace App\Models;

use CodeIgniter\Model;

// Customer support tickets.
class TicketModel extends Model
{
    protected $table         = 'support_tickets';
    protected $primaryKey    = 'ticket_id';
    protected $returnType    = 'array';
    protected $allowedFields = ['customer_id', 'subject', 'category', 'status'];

    // created_at and updated_at are filled in automatically.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
}
