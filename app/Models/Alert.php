<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Alert extends Model
{
    use HasFactory;

    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';

    // Custom timestamp field
    const CREATED_AT = 'raised_at';
    const UPDATED_AT = null;

    protected $fillable = [
        'user_id',
        'type',
        'message',
        'status',
        'raised_at',
    ];

    protected $casts = [
        'status' => 'boolean',
        'raised_at' => 'datetime',
    ];

    
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
