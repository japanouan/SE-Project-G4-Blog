<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('OrderDetails', function (Blueprint $table) {
            $table->timestamp('updated_at')->nullable(); // ✅ เพิ่มเฉพาะ updated_at
        });
    }

    public function down(): void
    {
        Schema::table('OrderDetails', function (Blueprint $table) {
            $table->dropColumn('updated_at');
        });
    }
};
