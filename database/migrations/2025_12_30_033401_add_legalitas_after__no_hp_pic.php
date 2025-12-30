<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('master_vendors', function (Blueprint $table) {
            $table->string('Npwp')->nullable()->after('NoHpPic');
            $table->string('Nib')->nullable()->after('Npwp');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('NoHpPic', function (Blueprint $table) {
            //
        });
    }
};
