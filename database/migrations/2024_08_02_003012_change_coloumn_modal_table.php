<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('asset', function (Blueprint $table) {
            $table->float('nominal')->change();
        });
        
    }

    public function down(): void
    {
        //
    }
};