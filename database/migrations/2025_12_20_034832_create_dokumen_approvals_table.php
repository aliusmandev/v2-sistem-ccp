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
        Schema::create('dokumen_approvals', function (Blueprint $table) {
            $table->id();
            $table->enum('JenisUser', ['Master', 'Manual']);
            $table->string('JenisFormId');
            $table->string('DokumenId');
            $table->string('PerusahaanId');
            $table->string('JabatanId');
            $table->string('UserId');
            $table->string('Nama');
            $table->integer('Urutan');
            $table->enum('Status', ['Pending', 'Approved', 'Rejected']);
            $table->timestamp('TanggalApprove')->nullable();
            $table->text('Catatan')->nullable();
            $table->string('Ttd')->nullable();
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
        Schema::dropIfExists('dokumen_approvals');
    }
};
