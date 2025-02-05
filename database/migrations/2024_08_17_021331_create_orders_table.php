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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('order_number');
            $table->foreignId('user_id')->nullable()->constrained()->onDelete('cascade'); // Nullable for guests
            $table->string("name");
            $table->string("email")->nullalbe();
            $table->string("phone")->nullable();
            $table->string("country")->nullable();
            $table->string("city")->nullalbe();
            $table->string("city_zip_code")->nullable();
            $table->text("address")->nullable();
            $table->string('payment_method');
            $table->string('payment_slip')->nullable();
            $table->decimal('total_price', 8, 1);
            $table->integer("payment_status")->default(0);
            $table->text("note")->nullable();
            $table->string("payment_currency")->nullable();
            $table->string("payment_currency_rate")->nullable();
            $table->string("payment_currency_price")->nullable();
            $table->string("delivery_price")->nullable();
            $table->timestamps();
        });        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
