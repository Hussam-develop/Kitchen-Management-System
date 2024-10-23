<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubLocation extends Model
{
    use HasFactory;
    protected $table = 'sub_locations';
    protected $fillable = [
        'name',
        'status',
        'location_id',
    ];

    public function products()
    {
        return $this->hasMany(Product::class,'subLocation_id','id');
    }

    public function location()
    {
        return $this->belongsTo(Location::class,'location_id','id');
    }
}
