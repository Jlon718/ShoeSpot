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
        Schema::create('orderinfos', function (Blueprint $table) {
            $table->bigIncrements('orderinfo_id');
            $table->unsignedBigInteger('customer_id')->index();
            $table->foreign('customer_id')->references('customer_id')->on('customers')->onDelete('cascade');
            $table->date('date_place');
            $table->date('date_shipped')->nullable();
            $table->float('shipping_fee');
            $table->tinyInteger('status')->default(0)->comment('0=pending,1=shipped,2=delivered,3=cancelled');
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orderinfos');
    }
};
