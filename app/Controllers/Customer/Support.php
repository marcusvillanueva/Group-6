<?php

namespace App\Controllers\Customer;

use App\Controllers\BaseController;
use App\Models\TicketMessageModel;
use App\Models\TicketModel;

// Support / help section: a simple ticketing system with chat-style replies.
class Support extends BaseController
{
    public function index()
    {
        return view('customer/support', [
            'title'   => 'Support | Sun Son Solar',
            'active'  => 'support',
            'tickets' => (new TicketModel())->where('customer_id', $this->userId())
                ->orderBy('updated_at', 'DESC')->findAll(),
        ]);
    }

    // POST customer/support - open a new ticket
    public function create()
    {
        $data  = $this->request->getPost(['subject', 'category', 'message']);
        $rules = [
            'subject'  => ['rules' => 'required|max_length[150]', 'errors' => ['required' => 'Please enter a subject.']],
            'category' => ['rules' => 'required|in_list[' . implode(',', config('SunSon')->ticketCategories) . ']', 'errors' => ['required' => 'Please choose a category.', 'in_list' => 'Please choose a category.']],
            'message'  => ['rules' => 'required|max_length[3000]', 'errors' => ['required' => 'Please describe your concern.']],
        ];

        if (! $this->validateData($data, $rules)) {
            return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
        }

        $ticketModel = new TicketModel();
        $ticketId    = $ticketModel->insert([
            'customer_id' => $this->userId(),
            'subject'     => trim($data['subject']),
            'category'    => $data['category'],
            'status'      => 'open',
        ]);

        (new TicketMessageModel())->insert([
            'ticket_id'   => $ticketId,
            'sender_type' => 'customer',
            'sender_name' => session('name'),
            'message'     => trim($data['message']),
        ]);

        return redirect()->to(site_url('customer/support/' . $ticketId))
            ->with('success', 'Your ticket was sent. Our team will reply here.');
    }

    // GET customer/support/{id} - the conversation
    public function show(int $id)
    {
        $ticket = $this->findMine($id);
        if ($ticket === null) {
            return redirect()->to(site_url('customer/support'))->with('error', 'Ticket not found.');
        }

        return view('customer/ticket', [
            'title'    => 'Ticket #' . $id . ' | Sun Son Solar',
            'active'   => 'support',
            'ticket'   => $ticket,
            'viewer'   => 'customer',
            'messages' => (new TicketMessageModel())->where('ticket_id', $id)->orderBy('created_at', 'ASC')->findAll(),
        ]);
    }

    // POST customer/support/{id} - add a reply
    public function reply(int $id)
    {
        $ticket  = $this->findMine($id);
        $message = trim((string) $this->request->getPost('message'));

        if ($ticket === null || $ticket['status'] === 'closed') {
            return redirect()->to(site_url('customer/support'))->with('error', 'This ticket is closed. Please open a new one.');
        }
        if ($message === '') {
            return redirect()->back()->with('error', 'Please type a message.');
        }

        (new TicketMessageModel())->insert([
            'ticket_id'   => $id,
            'sender_type' => 'customer',
            'sender_name' => session('name'),
            'message'     => $message,
        ]);
        // Back to "open" so staff know the customer is waiting for an answer.
        (new TicketModel())->update($id, ['status' => 'open']);

        return redirect()->to(site_url('customer/support/' . $id));
    }

    private function findMine(int $id): ?array
    {
        return (new TicketModel())->where('customer_id', $this->userId())->find($id);
    }
}
