<?php

namespace App\Models;

use CodeIgniter\Model;

// One chat-style message inside a support ticket.
class TicketMessageModel extends Model
{
    protected $table         = 'ticket_messages';
    protected $primaryKey    = 'message_id';
    protected $returnType    = 'array';
    protected $allowedFields = ['ticket_id', 'sender_type', 'sender_name', 'message'];

    // created_at is filled in automatically.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
