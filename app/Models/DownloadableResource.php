<?php

declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

final class DownloadableResource extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'description',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'is_premium',
        'status',
        'downloads',
    ];

    protected $casts = [
        'is_premium' => 'boolean',
        'file_size' => 'integer',
        'downloads' => 'integer',
    ];

    public function creator(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function scopePublished($query)
    {
        return $query->where('status', 'published');
    }

    public function scopePremium($query)
    {
        return $query->where('is_premium', true);
    }

    public function incrementDownloads(): void
    {
        $this->increment('downloads');
    }
}
