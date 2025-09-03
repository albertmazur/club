<?php

namespace App\Http\Controllers;

use App\Enums\TicketStatus;
use App\Repository\EventRepository;
use App\Repository\TicketRepository;
use App\Http\Requests\VerifyTicketRequest;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use App\Http\Requests\Store\StoreTicketRequest;
use App\Http\Requests\Update\BackTicketRequest;
use App\Http\Requests\Search\SearchTicketRequest;
use Laravel\Cashier\Exceptions\IncompletePayment;

class TicketController extends Controller
{
    private TicketRepository $ticketRepository;
    private EventRepository $eventRepository;

    public function __construct(TicketRepository $ticketRepository, EventRepository $eventRepository)
    {
        $this->ticketRepository = $ticketRepository;
        $this->eventRepository = $eventRepository;
    }

    public function index(SearchTicketRequest $request)
    {
        $data = $request->validated();

        $event = $data['event'] ?? null;
        $tickets = $this->ticketRepository->myTickets($event, TicketStatus::PURCHASED->value);

        foreach ($tickets as $ticket) $ticket->qr_code = QrCode::size(250)->generate( $ticket->qr_token);

        return view('dashboard.client.ticket', [
            'event' => $event,
            'events' => $this->eventRepository->all(),
            'tickets' => $tickets,
            'now' => now(),
            'option' => true
        ]);
    }

    public function store(StoreTicketRequest $request)
    {
        $data = $request->validated();

        $amount = $this->eventRepository->get($data['event_id'])->getAccount();
        try
        {
            $paymentIntent = auth()->user()->charge($amount, $data['payment_method']);
            $this->ticketRepository->add($data['countTickets'], $data['event_id'], $paymentIntent->id);
            return response()->json(['success' => true, 'message' => __('app.success_buy_ticket')]);
        }
        catch (IncompletePayment $exception)
        {
            return response()->json(['success' => false, 'error' => __('app.3D_secure')], 400);
        }
    }

    public function history(SearchTicketRequest $request)
    {
        $event = $request->validated()['event'] ?? null;

        return view('dashboard.client.historyTicket', [
            'event' => $event,
            'events' => $this->eventRepository->all(),
            'tickets' => $this->ticketRepository->myTickets($event),
            'option' => false
        ]);
    }

    public function backTicket(BackTicketRequest $request)
    {
        $success = $this->ticketRepository->backTicket($request->validated()['id']);

        return back()->with($success ? 'success' : 'error', __($success ? 'app.success_return' : 'app.error_return'));
    }

    public function paymentStatus()
    {
        $this->authorize('isUser', 'role');

        return back()->with('success', __('app.success_buy_ticket'));
    }

    public function scanner()
    {
        $this->authorize('isAdminOrModerator', 'role');

        return view('dashboard.admin.ticket.verify');
    }

    public function verifyQr(VerifyTicketRequest $request)
    {
        $ticket = $this->ticketRepository->getWithToken($request->validated()['token']);

        if ($ticket->used_at){
            return response()->json([
                'success' => false,
                'message' => __('dashboard.ticket.already_used', ['date' => $ticket->used_at])
            ]);
        }

        $ticket->used_at = now();
        $ticket->save();

        return response()->json([
            'success' => true,
            'ticket_id' => $ticket->id,
            'message' => __('dashboard.ticket.ticket_verified')
        ]);
    }
}
