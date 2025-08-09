<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Fortify\TwoFactorAuthenticatable;
use Laravel\Jetstream\HasProfilePhoto;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;


class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens;
    use HasRoles;
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory;
    use HasProfilePhoto;
    use Notifiable;
    use TwoFactorAuthenticatable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */


    protected $fillable = [
        'name',
        'email',
        'password',
        'phone_number',
        'date_of_birth',
        'gender',
        'address',
        'current_team_id',
        'two_factor_secret',
        'profile_photo_path',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'two_factor_recovery_codes',
        'two_factor_secret',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'profile_photo_url',
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
            'password' => 'hashed',
        ];
    }

    public function driver()
    {
        return $this->hasOne(Driver::class, 'user_id');
    }
        public function cashier()
    {
        return $this->hasOne(Cashier::class, 'user_id');
    }

    // Relación muchos a muchos con sites
    public function sites()
    {
        return $this->belongsToMany(Site::class, 'site_users')
                    ->withPivot('role', 'status')
                    ->withTimestamps();
    }

    // Obtener sites donde el usuario es admin
    public function adminSites()
    {
        return $this->sites()->wherePivot('role', 'admin');
    }

    // Obtener sites donde el usuario es coordinador
    public function coordinatorSites()
    {
        return $this->sites()->wherePivot('role', 'coordinator');
    }

    // Verificar si el usuario tiene acceso a un site específico
    public function hasAccessToSite($siteId)
    {
        return $this->sites()->where('sites.id', $siteId)->exists();
    }

    // Verificar si el usuario es admin de un site específico
    public function isAdminOfSite($siteId)
    {
        return $this->sites()
                    ->where('sites.id', $siteId)
                    ->wherePivot('role', 'admin')
                    ->exists();
    }

    public function adminlte_profile_url()
    {
        return route('profile.edit');
    }

    public function adminlte_image()
    {
        return $this->profile_photo_url;
    }
    public function adminlte_desc()
    {
        return $this->description ?? 'Sin descripción'; // Ajusta según tus necesidades
    }
    public function getDescriptionAttribute()
    {
        return $this->email; // Retorna el correo en lugar de la descripción
    }
}
