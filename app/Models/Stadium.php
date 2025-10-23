<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Stadium extends Model
{
    use HasFactory;

    protected $table = 'stadiums';

    protected $fillable = [
        'name',
        'description',
        'img',
        'city',
        'street',
        'numberBuilding',
        'places',
        'image'
    ];

    protected $casts = [
        'description' => 'array',
    ];

    public function events()
    {
        return $this->hasMany(Event::class);
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
