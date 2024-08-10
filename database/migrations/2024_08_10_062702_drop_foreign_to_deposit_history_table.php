<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('deposit_history', function (Blueprint $table) {
            $table->dropForeign(['invoice_id']);
        });
    }

    public function down(): void
    {
        Schema::table('deposit_history', function (Blueprint $table) {
            //
        });
    }
};
