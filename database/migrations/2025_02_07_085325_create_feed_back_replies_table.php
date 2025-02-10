<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('feed_back_replies', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_feed_back_id')->constrained()->onDelete('cascade');
            $table->text("reply");
            $table->foreignId('user_id')->constrained()->cascadeOnDelete()->nullable();
            $table->string("type")->default("admin");
            $table->string("like")->default(0);
            $table->string("dislike")->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('feed_back_replies');
    }
};
