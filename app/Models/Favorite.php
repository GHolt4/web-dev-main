<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Bike;

class Favorite extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'bike_id'];

    public function bike()
    {
        return $this->belongsTo(Bike::class);
    }
}
