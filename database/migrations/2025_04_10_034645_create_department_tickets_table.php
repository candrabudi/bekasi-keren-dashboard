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
        Schema::create('department_tickets', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('ticket_id');
            $table->string('report_id');
            $table->unsignedBigInteger('department_id');
            $table->string('department_name');
            $table->tinyInteger('status');
            $table->string('status_name');
            $table->timestamp('created_at')->nullable();
            $table->timestamp('updated_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_tickets');
    }
};
