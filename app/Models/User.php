<?php

namespace App\Models;

use App\Models\Fields\User\CalculatingStatus;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

/**
 * Class User
 *
 * @property \App\Models\Fields\User\CalculatingStatus $calculating_status
 */
class User extends Authenticatable
{
    use Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function phrases()
    {
        return $this->hasMany(Phrase::class, 'user_id');
    }

    /**
     * @return bool
     */
    public function isPendingCalculating()
    {
        return (string)$this->calculating_status == (string)CalculatingStatus::PENDING();
    }

    /**
     * @param $value
     * @return \App\Models\Fields\User\CalculatingStatus|null
     * @uses getCalculatingStatusAttribute()
     * @uses \App\Models\User::$calculating_status
     */
    protected function getCalculatingStatusAttribute($value): ?CalculatingStatus
    {
        return is_null($value) ? null : new CalculatingStatus($value);
    }

    /**
     * @param $value
     * @uses setCalculatingStatusAttribute()
     * @uses \App\Models\User::$calculating_status
     */
    protected function setCalculatingStatusAttribute($value)
    {
        $this->attributes['calculating_status'] = is_null($value) ? null : (new CalculatingStatus($value))->getValue();
    }
}
