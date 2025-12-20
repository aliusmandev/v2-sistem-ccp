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
        Schema::create('master_approvals', function (Blueprint $table) {
            $table->id();
            $table->string('KodePerusahaan');
            $table->string('JenisForm');
            $table->string('JabatanId');
            $table->string('UserId');
            $table->integer('Urutan');
            $table->enum('Wajib', ['Y', 'N'])->default('N');
            $table->enum('Aktif', ['Y', 'N'])->default('Y');
            $table->string('UserCreate')->nullable();
            $table->string('UserUpdate')->nullable();
            $table->string('UserDelete')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('master_approvals');
    }
};
