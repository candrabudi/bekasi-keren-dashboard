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
        Schema::create('department_reports', function (Blueprint $table) {
            $table->id();
            $table->string('report_id');
            $table->string('ticket');
            $table->string('status');
            $table->string('status_name')->nullable();
            $table->bigInteger('department_id');
            $table->string('department');
            $table->string('created_by');
            $table->text('description');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('department_reports');
    }
};
