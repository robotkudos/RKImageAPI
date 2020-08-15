<?php

namespace RobotKudos\RKImageAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public $fillable = [
        'image_url',
        'image_2x_url',
        'thumb_url',
        'thubm_2x_url'
    ];
}
