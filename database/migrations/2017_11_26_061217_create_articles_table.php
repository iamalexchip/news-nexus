<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('source_id');

            $table->string('guid')->nullable();
            $table->string('title');
            $table->string('url');
            $table->string('summary')->nullable();
            $table->string('image')->nullable();
            $table->timestamp('published')->nullable();

            $table->integer('site_clicks')->nullable()->default(0);
            $table->integer('social_shares')->nullable()->default(0);

            $table->decimal('popularity_score', 5, 2)->nullable()->default(0);

            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('articles');
    }
}
