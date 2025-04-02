<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use HasFactory, Notifiable, SoftDeletes;

    protected $guarded = [
        'id'
    ];


    protected $hidden = [
        'password',
    ];


    protected function casts(): array
    {
        return [
            'password' => 'hashed',
        ];
    }

    public function scopeWithoutAdmin(Builder $query): void
    {
        $query->where('is_admin', 'false');
    }

    public function scopeDivisionNotDeleted(Builder $builder): void
    {
        $builder->whereHas('division', function ($query) {
            $query->whereNull('deleted_at');
        });
    }

    public function division(): BelongsTo
    {
        return $this->belongsTo(Division::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class);
    }
}
