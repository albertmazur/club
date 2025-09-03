<?php

namespace App\Repository;

use App\Models\Ticket;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TicketRepository{
    public function add(int $count, int $event_id, string $stripe_payment_id);
    public function get(int $id): Ticket;
    public function getWithToken(string $token): Ticket;
    public function myTickets(?int $event, string $how = null): Collection;
    public function allPaginated(int $limit): LengthAwarePaginator;
    public function all(): Collection;
    public function backTicket(int $id): bool;

    //public function filterBy(string $name): LengthAwarePaginator;
}
