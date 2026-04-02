<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password', 'slug', 'avatar', 'cover_image', 'bio', 'tagline', 'stripe_price_id', 'social_links', 'subscription_price', 'is_creator', 'is_active', 'creator_requested_at'])]
class User extends Authenticatable
{
    use Billable, HasFactory, HasRoles, Notifiable;

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_creator' => 'boolean',
            'is_active' => 'boolean',
            'social_links' => 'array',
            'subscription_price' => 'decimal:2',
            'creator_requested_at' => 'immutable_datetime',
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (! $user->slug) {
                $user->slug = self::generateUniqueSlug($user->name);
            }
        });
    }

    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    public function posts(): HasMany
    {
        return $this->hasMany(Post::class);
    }

    public function resources(): HasMany
    {
        return $this->hasMany(DownloadableResource::class);
    }

    public function courses(): HasMany
    {
        return $this->hasMany(Course::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class, 'subscriber_id');
    }

    public function earnings(): HasMany
    {
        return $this->hasMany(Transaction::class, 'creator_id');
    }

    public function getActiveSubscriptionPrice(): float
    {
        return (float) ($this->subscription_price ?? 9.99);
    }

    public function getAvatarUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->avatar);
    }

    public function getCoverImageUrlAttribute(): ?string
    {
        return $this->resolveMediaUrl($this->cover_image);
    }

    private function resolveMediaUrl(?string $path): ?string
    {
        if (! $path) {
            return null;
        }

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Storage::disk('public')->exists($path)) {
            return asset('storage/'.$path);
        }

        return null;
    }

    private static function generateUniqueSlug(string $name): string
    {
        $base = Str::slug($name);

        if ($base === '') {
            $base = 'user';
        }

        $slug = $base;
        $counter = 2;

        while (self::query()->where('slug', $slug)->exists()) {
            $slug = $base.'-'.$counter;
            $counter++;
        }

        return $slug;
    }
}
