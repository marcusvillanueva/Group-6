<?php

namespace App\Models;

use CodeIgniter\Model;

// Updates posted by Admin/HR for employees.
class AnnouncementModel extends Model
{
    protected $table         = 'announcements';
    protected $primaryKey    = 'announcement_id';
    protected $returnType    = 'array';
    protected $allowedFields = ['title', 'message', 'category', 'posted_by'];

    // created_at is filled in automatically.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';
}
