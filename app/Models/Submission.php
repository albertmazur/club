<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Submission extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'reason',
        'comment_id'
    ];

    protected $casts = [
        'content' => 'array',
    ];

    public function comment()
    {
        return $this->belongsTo(Comment::class);
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
