<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\File;
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

Route::group(['middleware' => config('rkimageapi.middleware', 'auth:api'), 'prefix' => config('rkimageapi.api_url', 'api/image-api')], function() {
    Route::post('/', function (Request $request) {
        if ($request->hasFile('image')) {
            $imageUploader = new ImageUploader();
            $savedImages = $imageUploader->save($request->image->path(), new Size(config('rkimageapi.image_size', 1500)), null, new Size(config('rkimageapi.thumb_size', 180)));
            $image = Image::create([
                'image_url' => $savedImages['image_url'],
                'image_2x_url' => $savedImages['image_url_retina'],
                'thumb_url' => $savedImages['thumb_url'],
                'thumb_2x_url' => $savedImages['thumb_url_retina'],
                'key' => $request->key,
                'name' => $request->name,
                'position' => $request->position,
                'comments' => $request->comments
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
    });
    Route::get('/', function (Request $request) {
        return Image::where('key', $request->query('key'))
            ->orderBy('position')
            ->get([
            'id',
            'name',
            'image_url',
            'image_2x_url',
            'thumb_url',
            'thumb_2x_url',
            'position',
            'comments'
            ])->toJson();
    });

    Route::delete('/', function(Request $request) {
        try {
            $images = Image::where('key', $request->key)->get();
            foreach($images as $image) {
                if(!deleteImage($image)) return json_encode(['err' => true]);
            }
        } catch(Exception $e) {
            return json_encode(['err' => $e->getMessage()]);
        }
        return json_encode(['err' => false]);
    });

    Route::put('/reorder', function(Request $request) {
        $tableName = Image::getModel()->getTable();
        $images = $request->images;
        $ids = [];
        $params = [];
        $cases = [];
        foreach($images as $image) {
            $id = $image['id'];
            $cases[] = "WHEN {$id} THEN ?";
            $params[] = $image['pos'];
            $ids[] = $id;
        }
        $ids = implode(',', $ids);
        $cases = implode(' ', $cases);
        $params[] = Carbon::now();
        DB::update("UPDATE `{$tableName}` SET `position` = CASE `id` {$cases} END, `updated_at` = ? WHERE `ID` IN ({$ids})", $params);

        return json_encode([
            'err' => false
        ]);
    });
    Route::put('/{id}', function (Request $request, $id) {
        // get json raw
        // $json = file_get_contents('php://input');
        switch($request->action) {
            case 'rename':
                Image::find($id)
                    ->update(['name' => $request->name]);
                return json_encode([
                    'err' => false,
                    'name' => $request->name
                ]);
        }
    });

    Route::delete('/{id}', function (Request $request, $id) {
        $image = Image::find($id);
        if (!deleteImage($image)) {
            return json_encode(['err' => true]);
        }
        return json_encode(['err' => false]);
    });

    if (!function_exists('deleteImage')) {
        function deleteImage(Image $image)
        {
            try {
                if (File::exists($image->image_url)) File::delete($image->image_url);
                if (File::exists($image->image_2x_url)) File::delete($image->image_2x_url);
                if (File::exists($image->thumb_url)) File::delete($image->thumb_url);
                if (File::exists($image->thumb_2x_url)) File::delete($image->thumb_2x_url);
                $image->delete();
            } catch (Exception $e) {
                return false;
            }
            return true;
        }
    }
});

