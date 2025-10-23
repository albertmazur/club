<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'user_id',
        'event_id'
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public $timestamps = false;

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($model)
        {
            $mytime = Carbon::now();
            $model->date = $mytime->toDate();
            $model->time = $mytime->setTimezone('Europe/Warsaw')->format('H:i:s');
        });
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

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function getContent(string $locale = null): ?string
    {
        $locale = $locale
        ?: session('language')
        ?: config('app.local', 'en');

        return $this->content[$locale] ?? null;
    }

    public function setContent(string $locale, ?string $value): void
    {
        $data = $this->content ?? [];
        $data[$locale] = $value;
        $this->content = $data;
    }
}
