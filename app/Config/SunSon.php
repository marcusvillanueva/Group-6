<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

/**
 * Sun Son Solar settings that never change while the site is running.
 * Use it anywhere with: config('SunSon')->departments
 */
class SunSon extends BaseConfig
{
    // Departments given by the client in the interview.
    public array $departments = [
        'Administration',
        'IT',
        'Dispatch',
        'Accounting',
        'HR',
        'Marketing',
        'Sales',
        'Customer Service',
    ];

    // Employee access levels (stored in employees.role).
    public array $roles = [
        'employee'   => 'Employee',
        'technician' => 'Technician',
        'dispatcher' => 'Dispatcher',
        'hr'         => 'HR staff',
        'admin'      => 'Admin',
    ];

    // Which employee roles may open each part of the staff area.
    public array $staffPermissions = [
        'dashboard'     => ['admin', 'hr', 'dispatcher'],
        'users'         => ['admin', 'hr'],
        'schedules'     => ['admin', 'dispatcher'],
        'reports'       => ['admin'],
        'notifications' => ['admin'],
        'settings'      => ['admin'],
        'announcements' => ['admin', 'hr'],
        'attendance'    => ['admin', 'hr', 'dispatcher'],
        'issues'        => ['admin', 'dispatcher'],
        'support'       => ['admin'],
        'quotes'        => ['admin', 'dispatcher'],
    ];

    public array $genders = ['Female', 'Male', 'Prefer not to say'];

    // Booking times customers can choose.
    public array $timeSlots = [
        '08:00:00' => '8:00 AM',
        '09:00:00' => '9:00 AM',
        '10:00:00' => '10:00 AM',
        '11:00:00' => '11:00 AM',
        '13:00:00' => '1:00 PM',
        '14:00:00' => '2:00 PM',
        '15:00:00' => '3:00 PM',
        '16:00:00' => '4:00 PM',
    ];

    public array $scheduleStatuses = ['pending', 'approved', 'declined', 'completed', 'cancelled'];

    public array $paymentMethods = ['Cash', 'GCash', 'Bank transfer', 'Card'];

    public array $ticketCategories = ['Schedule', 'Billing', 'System or equipment problem', 'Product question', 'Other'];

    public array $issueCategories = ['Equipment issue', 'Customer concern', 'Safety concern', 'Other'];

    public array $announcementCategories = ['General', 'Reminder', 'Safety', 'Urgent'];

    public array $notificationTypes = [
        'reminder'  => 'Reminder',
        'update'    => 'Update',
        'promotion' => 'Promotion',
        'system'    => 'System update',
    ];
}
