<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class Post extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'excerpt',
        'slug',
        'body',
        'cover_image',
        'is_premium',
        'status',
        'published_at',
        'views',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'published_at' => 'immutable_datetime',
        'views' => 'integer',
    ];

    public function author(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('status', 'published');
    }

    public function scopePremium(Builder $query): Builder
    {
        return $query->where('is_premium', true);
    }

    public function scopeFree(Builder $query): Builder
    {
        return $query->where('is_premium', false);
    }

    public function incrementViews(): void
    {
        $this->increment('views');
    }
}
