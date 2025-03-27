<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReviewsTable extends Migration
{
    public function up()
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id(); // Default auto-incrementing primary key
            $table->unsignedBigInteger('user_id');
            $table->string('bike_id');
            $table->unsignedBigInteger('favourites_id')->nullable();
            $table->decimal('rating', 3, 1)->nullable(); // Allow decimal ratings
            $table->text('review_text')->nullable();
            $table->timestamps();

            // Optional: Add foreign key constraints if you want to enforce referential integrity
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('cascade');

            $table->foreign('favourites_id')
                  ->references('id')
                  ->on('favourites')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        // Drop foreign keys first to avoid constraint errors
        Schema::table('reviews', function(Blueprint $table) {
            $table->dropForeignKey(['user_id', 'favourites_id']);
        });

        Schema::dropIfExists('reviews');
    }
}