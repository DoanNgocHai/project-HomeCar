<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Reports extends Model
{
    use HasFactory;
    protected $table = 'reports';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'car_id',
        'reason',
        'description',
        'status'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function car()
    {
        return $this->belongsTo(Cars::class, 'car_id', 'id');
    }
}
