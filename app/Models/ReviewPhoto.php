<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReviewPhoto extends Model
{
    protected $table = 'review_photos';

    public $timestamps = false;

    protected $fillable = ['review_id', 'image_url'];

    public function review()
    {
        return $this->belongsTo(Review::class, 'review_id');
    }
}
