<?php

namespace App\Models;

use App\Enums\TicketStatus;
use Illuminate\Support\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'date',
        'time',
        'price',
        'stadium_id',
        'image'
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
    ];

    public function stadium()
    {
        return $this->belongsTo(Stadium::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function commentsSort()
    {
        return $this->comments()->orderByDesc('date')->orderByDesc('time')->get();
    }

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public function freePlaces()
    {
        return $this->stadium->places - $this->tickets->where('state', '=', TicketStatus::PURCHASED->value)->count();
    }

    public function getAccount()
    {
        return $this->price * 100;
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format($this->price, 2, '.', '');
    }
    protected function date(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('d-m-Y'),
        );
    }
    protected function time(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => Carbon::parse($value)->format('H:i'),
        );
    }

    public function getName(string $locale = null): ?string
    {
        $locale = $locale
        ?: session('language')
        ?: config('app.local', 'en');

        return $this->name[$locale] ?? null;
    }

    public function setName(string $locale, ?string $value): void
    {
        $data = $this->description ?? [];
        $data[$locale] = $value;
        $this->name = $data;
    }

    public function getDescription(string $locale = null): ?string
    {
        $locale = $locale
        ?: session('language')
        ?: config('app.local', 'en');

        return $this->description[$locale] ?? null;
    }

    public function setDescription(string $locale, ?string $value): void
    {
        $data = $this->description ?? [];
        $data[$locale] = $value;
        $this->description = $data;
    }
}
