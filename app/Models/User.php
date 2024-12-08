<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Filament\Models\Contracts\FilamentUser;
use Filament\Panel;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'document',
        'password',
        'birth_date',
        'gender',
        'blood_type',
        'marital_status',
        'education_level',
        'photo',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];


    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'photo' => 'array',
    ];

    public function associate()
    {
        return $this->hasOne(Associate::class);
    }
    public function employee()
    {
        return $this->hasOne(Employee::class);
    }
    public function address()
    {
        return $this->hasOne(Address::class);
    }
    public function phone()
    {
        return $this->hasOne(Phone::class);
    }

    public function meetings()
    {
        return $this->belongsToMany(Meeting::class, 'meeting_user', 'user_id', 'meeting_id');
    }


    public function canAccessPanel(Panel $panel): bool
    {
        return $this->hasPermissionTo('access_panel');
    }
}
