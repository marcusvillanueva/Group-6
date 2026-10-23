<?php

namespace App\Models;

use CodeIgniter\Model;

// Messages shown in the customer's Notifications panel.
class NotificationModel extends Model
{
    protected $table         = 'notifications';
    protected $primaryKey    = 'notification_id';
    protected $returnType    = 'array';
    protected $allowedFields = ['customer_id', 'type', 'title', 'message', 'is_read'];

    // created_at is filled in automatically.
    protected $useTimestamps = true;
    protected $createdField  = 'created_at';
    protected $updatedField  = '';

    // Send one notification to one customer.
    public function send(int $customerId, string $type, string $title, string $message): void
    {
        $this->insert([
            'customer_id' => $customerId,
            'type'        => $type,
            'title'       => $title,
            'message'     => $message,
            'is_read'     => 0,
        ]);
    }

    public function unreadCount(int $customerId): int
    {
        return $this->where('customer_id', $customerId)->where('is_read', 0)->countAllResults();
    }
}
