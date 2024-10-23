<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Location extends Model
{
    use HasFactory;
    protected $table = 'locations';
    protected $fillable = [
        'name',
        'status',
        'qr'
    ];


    protected $casts = [
        'status' => 'string',
    ];


    public function subLocations()
    {
        return $this->hasMany(SubLocation::class,'location_id','id');
    }
}
