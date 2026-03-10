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
        Schema::create('request_for_compensation', function (Blueprint $table) {
            $table->id();
            $table->foreignId('employee_id');
            $table->dateTime('date_creation');
            $table->integer('total_days');
            $table->string('status');
            $table->date('approval_date');
            $table->date('pending_date');
            $table->text('comment');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('request_for_compensation');
    }
};
