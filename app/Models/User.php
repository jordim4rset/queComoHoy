<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'username',
        'email',
        'password',
        'rol',
        'chefpoints',
        'banned_at',
        'profile_photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'banned_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function isBanned(): bool
    {
        return $this->banned_at !== null;
    }

    public function chefLevel(): int
    {
        $level = intdiv($this->chefpoints, 1000) + 1;
        return min(5, max(1, $level));
    }

    public function chefLevelName(): string
    {
        return match ($this->chefLevel()) {
            1 => 'Chef Novato',
            2 => 'Chef Aprendiz',
            3 => 'Chef Experto',
            4 => 'Chef Maestro',
            default => 'Chef Legendario',
        };
    }

    public function chefLevelProgress(): int
    {
        $progress = $this->chefpoints - ($this->chefLevel() - 1) * 1000;
        return min(1000, max(0, $progress));
    }

    public function chefLevelPercent(): int
    {
        return (int) round(($this->chefLevelProgress() / 1000) * 100);
    }

    public function chefPointsToNextLevel(): int
    {
        if ($this->chefLevel() >= 5) {
            return 0;
        }

        return 1000 - $this->chefLevelProgress();
    }

    public function profilePhotoUrl(): string
    {
        if ($this->profile_photo) {
            return asset('storage/' . $this->profile_photo);
        }

        return 'https://ui-avatars.com/api/?name=' . urlencode($this->username ?? $this->name ?? 'Usuario');
    }

    public function following()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'follower_id',
            'following_id'
        );
    }

    public function followers()
    {
        return $this->belongsToMany(
            User::class,
            'follows',
            'following_id',
            'follower_id'
        );
    }

    public function blockedUsers()
    {
        return $this->belongsToMany(
            User::class,
            'blocks',
            'id_bloqueador',
            'id_bloqueado'
        );
    }

    public function hasBlocked(User $user): bool
    {
        return $this->blockedUsers()
            ->where('users.id', $user->id)
            ->exists();
    }

    public function recipes()
    {
        return $this->hasMany(Recipe::class);
    }

    public function likes()
{
    return $this->hasMany(Like::class);
}
}
