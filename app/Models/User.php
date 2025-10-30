<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Hash;


class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    const CREATED_AT = 'created_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'is_admin',
        'username',
        'email',
        'password',
        'gender',
        'is_activated',
        'date_of_birth',
        'account_type',
        'caretaker_phone_number',
        'caretaker_name',
        'testimonial_rate',
        'testimonial_message',
    ];

    protected $hidden = [
        'password',
    ];

    protected $casts = [
        'is_admin' => 'boolean',
        'is_activated' => 'boolean',
        'date_of_birth' => 'date',
        'testimonial_rate' => 'integer',
    ];

    public function setPasswordAttribute($value)
    {
        if (!empty($value)) {
            $this->attributes['password'] = Hash::make($value);
        }
    }

    public function records()
    {
        return $this->hasMany(Record::class);
    }

    public function alerts()
    {
        return $this->hasMany(Alert::class);
    }
}
