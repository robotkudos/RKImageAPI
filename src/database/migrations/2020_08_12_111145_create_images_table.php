<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateImagesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $table_name = config('rkimageapi.table_name', 'images');
        Schema::create( $table_name, function (Blueprint $table) {
            $table->id();
            $table->string('name', 250);
            $table->string('key');
            $table->string('image_url', 250);
            $table->string('image_2x_url', 250)->nullable();
            $table->string('thumb_url', 250)->nullable();
            $table->string('thumb_2x_url', 250)->nullable();
            $table->integer('position');
            $table->text('comments')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $table_name = config('rkimageapi.table_name', 'images');
        Schema::dropIfExists($table_name);
    }
}
