<?php

namespace App\Repository\Eloquent;

use Carbon\Carbon;
use App\Models\Event;
use App\Enums\TicketStatus;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use App\Repository\EventRepository as Repository;

class EventRepository implements Repository{
    private Event $eventModel;

    public function __construct(Event $event)
    {
        $this->eventModel = $event;
    }

    public function add(string $name, ?string $description, string $date, string $time, float $price, int $stadium_id, ?string $imagePath)
    {
        $event = new Event;
        $event->name = $name;
        $event->description = $description;
        $event->date = $date;
        $event->time = $time;
        $event->price = $price;
        $event->price = $price;
        $event->stadium_id = $stadium_id;
        $event->image = $imagePath;
        $event->save();
    }

    public function update(int $id, string $name, ?string $description, string $date, string $time, float $price, int $stadium_id, ?string $imagePath)
    {
        $event = $this->eventModel->findOrFail($id);
        $event->name = $name;
        $event->description = $description;
        $event->date = $date;
        $event->time = $time;
        $event->price = $price;
        $event->price = $price;
        $event->stadium_id = $stadium_id;
        $event->image = $imagePath;
        $event->save();
    }

    public function get(int $id): Event
    {
        return $this->eventModel->findOrFail($id);
    }

    public function allPaginated(int $limit): LengthAwarePaginator
    {
        return $this->eventModel->orderBy('name')->paginate($limit);
    }

    public function all(): Collection
    {
        return $this->eventModel->all();
    }

    public function orderByData(int $limit): Collection
    {
        return $this->eventModel->where('date', '>', Carbon::today())->orderBy('date')->orderBy('time')->limit($limit)->get();
    }

    public function mostComment(int $limit): Collection
    {
        return $this->eventModel->withCount('comments')->orderBy('comments_count', 'desc')->limit($limit)->get();
    }

    public function filterBy(?string $value, string $sortSearch = 'name', string $direction = 'asc', int $facility, ?string $filterData, int $limit = 10): LengthAwarePaginator
    {
        $query = $this->eventModel;

        if($sortSearch === 'freeSet')
        {
            $query = $query->selectRaw('events.*, (stadiums.places - COUNT(tickets.id)) AS freeSet')
                ->join('stadiums', 'events.stadium_id', '=', 'stadiums.id')
                ->leftJoin('tickets', function ($join){
                    $join->on('events.id', '=', 'tickets.event_id')
                         ->where('tickets.state', '=', TicketStatus::PURCHASED->value);
                })
                ->groupBy('events.id', 'stadiums.places');
        }
        if($facility != 0) $query = $query->where('stadium_id', '=', $facility);

        if($value) $query = $query->where('name', 'like', $value.'%');
        
        $driver = DB::getDriverName();
        if ($driver === 'sqlite'){
            $expression = DB::raw("date || ' ' || time");
        } elseif ($driver === 'pgsql'){
            $expression = DB::raw("date || ' ' || time::text");
        } else {
            $expression = DB::raw("CONCAT(date, ' ', time)");
        }

        if($filterData == 'past') $query = $query->where($expression, '<', now());
        if($filterData == 'future') $query = $query->where($expression, '>', now());

        $query = $query->orderBy($sortSearch, $direction);
        
        $paginate = $query->paginate($limit);

        $paginate->appends([
            'value' => $value,
            'sortSearch' => $sortSearch,
            'direction' => $direction,
            'filterData' => $filterData
        ]);

        return $paginate;
    }
}
