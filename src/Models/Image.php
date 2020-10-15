<?php

namespace RobotKudos\RKImageAPI\Models;

use Illuminate\Database\Eloquent\Model;

class Image extends Model
{
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        $this->table = config('rkimageapi.table_name', 'images');
    }

    public $fillable = [
        'image_url',
        'image_2x_url',
        'thumb_url',
        'thubm_2x_url',
        'key',
        'name',
        'comments',
        'position'
    ];

}
