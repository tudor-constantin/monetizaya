<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Attributes\Fillable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Laravel\Cashier\Billable;
use Spatie\Permission\Traits\HasRoles;

#[Fillable(['name', 'email', 'password'])]
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
        ];
    }

    protected static function booted(): void
    {
        static::creating(function (User $user) {
            if (! $user->slug) {
                $user->slug = Str::slug($user->name).'-'.Str::random(4);
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
}
