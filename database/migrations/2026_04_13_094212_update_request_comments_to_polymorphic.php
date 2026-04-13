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
        Schema::table('request_comments', function (Blueprint $table) {
            // eliminar FK actual
            $table->dropColumn('vacation_request_id');

            // agregar relación polimórfica
            $table->morphs('commentable');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('request_comments', function (Blueprint $table) {
            $table->dropColumn(['commentable_id', 'commentable_type']);

            $table->foreignId('vacation_request_id');
        });
    }
};
