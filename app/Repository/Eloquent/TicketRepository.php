<?php

namespace App\Repository\Eloquent;

use Str;
use Stripe\Refund;
use Stripe\Stripe;
use App\Models\Ticket;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repository\TicketRepository as Repository;

class TicketRepository implements Repository{
    private Ticket $ticketModel;

    public function __construct(Ticket $ticket)
    {
        $this->ticketModel = $ticket;
    }

    public function add(int $count, int $event_id, string $stripe_payment_id)
    {
        for($i = 0; $i < $count; $i++)
        {
            $ticket = new Ticket();
            $ticket->user_id = Auth::id();
            $ticket->event_id = $event_id;
            $ticket->stripe_payment_id = $stripe_payment_id;
            $ticket->qr_token = Str::uuid();
            $ticket->save();
        }
    }

    public function get(int $id): Ticket{
        return $this->ticketModel->findOrFail($id);
    }

    public function getWithToken(string $token): Ticket{
        return Ticket::where('qr_token', $token)->where('state', 'purchased')->firstOrFail();
    }

    public function myTickets(?int $event, string $how = null): Collection{
        $query = $this->ticketModel->where('user_id', '=', Auth::id());

        if($event) $query = $query->where('event_id', '=', $event);

        if($how == 'bought') $query = $query->where('state', '=', 'bought');
        if($how == 'returned') $query = $query->where('state', '=', 'returned');
        return $query->get();
    }

    public function allPaginated(int $limit): LengthAwarePaginator{
        return $this->ticketModel->orderBy('name')->paginate($limit);
    }

    public function all(): Collection{
        return $this->ticketModel->all();
    }

    public function orderByData(int $limit): Collection{
        return $this->ticketModel
            ->where('date', '>', Carbon::today())
            ->orderBy('date')->orderBy('time')
            ->limit($limit)
            ->get();
    }

    public function mostComment(int $limit): Collection{
        return $this->ticketModel
            ->withCount('comments')
            ->orderBy('comments_count', 'desc')
            ->limit($limit)
            ->get();
    }

    public function backTicket(int $id): bool{
        $ticket = $this->ticketModel->findOrFail($id);
        $ticket->state = 'returned';

        try
        {
            Stripe::setApiKey(config('services.stripe.secret'));
            Refund::create([
                'payment_intent' => $ticket->stripe_payment_id,
            ]);

            if($ticket->event->date>(Carbon::now()->addDays(3)))
            {
                $ticket->update();
                return true;
            }
            else return false;
        }
        catch (\Exception $e)
        {
            return false;
        }
    }
}
