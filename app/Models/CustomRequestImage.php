<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomRequestImage extends Model
{
    protected $fillable = ['custom_request_id', 'image_url'];

    public function customRequest()
    {
        return $this->belongsTo(CustomRequest::class);
    }
}
