<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ServicePhoto extends Model
{
    protected $table = 'service_photos';

    public $timestamps = false;

    protected $fillable = ['category', 'image_url', 'caption'];
}
