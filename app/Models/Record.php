<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Record extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';



    const UPDATED_AT = null;
    const CREATED_AT = 'recorded_at';
    public $timestamps = true;


    protected $fillable = [
        'user_id',
        'spo2',
        'heart_rate',
        'galvanic_skin_resistance',
        'relative_humidity',
        'recorded_at',
    ];

    protected $casts = [
        'spo2' => 'float',
        'heart_rate' => 'float',
        'galvanic_skin_resistance' => 'float',
        'relative_humidity' => 'float',
        'recorded_at' => 'datetime',
    ];


    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
