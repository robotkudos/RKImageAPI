<?php

return [
    'table_name' => 'images',
    'middleware' => ['auth:api'],
    'api_url'    => 'api/image-api',
    'image_size' => 1500,
    'thumb_size' => 180
];
