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
        Schema::create('paid_request', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->integer('total_days')->nullable();
            $table->string('status')->nullable();
            $table->dateTime('request_date')->nullable();
            $table->text('comment')->nullable();
            $table->integer('paid_accrued')->nullable();
            $table->date('start_date')->nullable();
            $table->date('end_date')->nullable();
            $table->integer('used')->nullable();
            $table->integer('paid_total')->nullable();
            $table->integer('days_to_compensate')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('paid_request');
    }
};
