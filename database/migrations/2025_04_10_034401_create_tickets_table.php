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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('ticket')->unique();
            $table->unsignedBigInteger('channel_id')->nullable();
            $table->string('category')->nullable();
            $table->string('category_id')->nullable();
            $table->tinyInteger('status');
            $table->string('status_name');
            $table->tinyInteger('call_type');
            $table->string('call_type_name');
            $table->unsignedBigInteger('caller_id')->nullable();
            $table->string('phone')->nullable();
            $table->string('phone_unmask')->nullable();
            $table->string('caller')->nullable();
            $table->string('created_by');
            $table->text('address')->nullable();
            $table->string('location')->nullable();
            $table->string('district_id')->nullable();
            $table->string('district')->nullable();
            $table->string('subdistrict_id')->nullable();
            $table->string('subdistrict')->nullable();
            $table->text('notes')->nullable();
            $table->text('description')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
