<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Verifies extends Model
{
    use HasFactory;

    protected $table = 'verifies';
    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'car_id',
        'body_condition',
        'chassis_condition',
        'engine_condition',
        'interior_condition',
        'test_drive',
        'score',
        'note',
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
