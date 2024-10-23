<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $table = 'products';
    protected $fillable = [
        'name',
        'qty',
        'alert_qty',
        'qr_code',
        'ingredients',
        'location_id',
        'production_date',
        'expire_date',
        'subLocation_id',
        'status'
    ];

    public function subLocation()
    {
        return $this->belongsTo(SubLocation::class, 'subLocation_id', 'id');
    }
}
