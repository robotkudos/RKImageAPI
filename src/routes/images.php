<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use RobotKudos\RKImage\ImageUploader;
use RobotKudos\RKImage\Size;
use RobotKudos\RKImageAPI\Models\Image;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::post('/image-api', function (Request $request) {
    if ($request->hasFile('image')) {
        $imageUploader = new ImageUploader();
        $savedImages = $imageUploader->save($request->image->path(), new Size(1500), null, new Size(180));
        $image = Image::create([
            'image_url' => $savedImages['image_url'],
            'image_2x_url' => $savedImages['image_url_retina'],
            'thumb_url' => $savedImages['thumb_url'],
            'thumb_2x_url' => $savedImages['thumb_url_retina'],
        ]);
        return json_encode([
            'err' => false,
            'id' => $image->id
        ]);
    } else {
        return json_encode([
            'err'   => 'Request doesn\'t have image file'
        ]);
    }

    return json_encode([
        'hasFile' => $request->hasFile('image'),
        'text' => $request->key
    ]);
})->middleware(['api']);
