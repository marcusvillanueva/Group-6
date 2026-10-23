<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\NotificationModel;
use App\Models\TicketMessageModel;
use App\Models\TicketModel;

// Staff side of the customer support tickets.
class Support extends BaseController
{
    public function index()
    {
        return view('admin/support', [
            'title'   => 'Support Tickets | Sun Son Solar',
            'active'  => 'support',
            'tickets' => (new TicketModel())
                ->select("support_tickets.*, CONCAT(customers.firstname, ' ', customers.lastname) AS customer_name", false)
                ->join('customers', 'customers.customer_id = support_tickets.customer_id')
                // Open tickets (customer waiting) first.
                ->orderBy("FIELD(support_tickets.status, 'open', 'answered', 'closed')", '', false)
                ->orderBy('support_tickets.updated_at', 'DESC')
                ->findAll(),
        ]);
    }

    public function show(int $id)
    {
        $ticket = (new TicketModel())
            ->select("support_tickets.*, CONCAT(customers.firstname, ' ', customers.lastname) AS customer_name, customers.email, customers.phone", false)
            ->join('customers', 'customers.customer_id = support_tickets.customer_id')
            ->where('support_tickets.ticket_id', $id)
            ->first();

        if ($ticket === null) {
            return redirect()->to(site_url('admin/support'))->with('error', 'Ticket not found.');
        }

        return view('admin/ticket', [
            'title'    => 'Ticket #' . $id . ' | Sun Son Solar',
            'active'   => 'support',
            'ticket'   => $ticket,
            'viewer'   => 'staff',
            'messages' => (new TicketMessageModel())->where('ticket_id', $id)->orderBy('created_at', 'ASC')->findAll(),
        ]);
    }

    public function reply(int $id)
    {
        $ticket  = (new TicketModel())->find($id);
        $message = trim((string) $this->request->getPost('message'));

        if ($ticket === null || $message === '') {
            return redirect()->back()->with('error', 'Please type a reply.');
        }

        (new TicketMessageModel())->insert([
            'ticket_id'   => $id,
            'sender_type' => 'staff',
            'sender_name' => session('name'),
            'message'     => $message,
        ]);
        (new TicketModel())->update($id, ['status' => 'answered']);

        // Let the customer know there is a reply.
        (new NotificationModel())->send(
            (int) $ticket['customer_id'],
            'update',
            'New reply on ticket #' . $id,
            'Our team replied to "' . $ticket['subject'] . '". Open Support to read it.'
        );

        return redirect()->to(site_url('admin/support/' . $id))->with('success', 'Reply sent.');
    }

    public function close(int $id)
    {
        (new TicketModel())->update($id, ['status' => 'closed']);

        return redirect()->to(site_url('admin/support'))->with('success', 'Ticket #' . $id . ' closed.');
    }
}
