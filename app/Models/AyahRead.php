<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AyahRead extends Model
{
    /** @use HasFactory<\Database\Factories\AyahReadFactory> */
    use HasFactory, HasUuids;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'user_id',
        'surah_number',
        'ayah_number',
        'read_at',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'read_at' => 'datetime',
            'surah_number' => 'integer',
            'ayah_number' => 'integer',
        ];
    }

    /**
     * Get the user that owns the reading entry.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
