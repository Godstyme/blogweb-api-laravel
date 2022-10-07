<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('comments', function (Blueprint $table) {
            $table->id();
            $table->Text('comments_content');
            $table->integer("blog_posts_id", false, true);
            // $table->integer('users_id', false, true);
            $table->unsignedBigInteger('users_id')
            ->nullable();
            // $table->foreignId('users_id')->constrained('users');
            // $table->foreignId('posts_id')->constrained('blog_posts');


            // $table->integer('blog_posts_id')
            // ->nullable();
            // $table->foreign('blog_posts_id')
            // ->references('id')
            // ->on('blog_posts')
            // ->onDelete('set null');
             //Or $table->unsignedInteger('category_id');
            $table->foreign('blog_posts_id')
            ->references('id')
            ->on('blog_posts');

            $table->foreign('users_id')
            ->references('id')
            ->on('users');

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
        Schema::dropIfExists('comments');
    }
};
