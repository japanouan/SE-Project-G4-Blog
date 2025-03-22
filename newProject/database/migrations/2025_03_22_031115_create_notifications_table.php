<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{

    public function up()
    {
        Schema::create('notifications', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->foreign('user_id')->references('user_id')->on('Users')->onDelete('cascade');
            $table->foreignId('issue_id')->constrained()->onDelete('cascade');
            $table->text('message');
            $table->boolean('is_read')->default(false); // สถานะการอ่าน
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('notifications');
    }
};
