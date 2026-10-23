<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\NotificationModel;

// Reminders, schedule updates and promotions sent to the customer.
class Notifications extends BaseController
{
    public function index()
    {
        $model = new NotificationModel();

        $notifications = $model->where('customer_id', $this->userId())
            ->orderBy('created_at', 'DESC')
            ->findAll();

        // Opening the panel marks everything as read
        // (the list above still shows which ones were new).
        $model->where('customer_id', $this->userId())->where('is_read', 0)->set('is_read', 1)->update();

        return view('customer/notifications', [
            'title'         => 'Notifications | Sun Son Solar',
            'active'        => 'notifications',
            'notifications' => $notifications,
        ]);
    }
}
