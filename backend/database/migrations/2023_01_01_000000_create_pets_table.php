<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate->Database->Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('pets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('name');
            $table->string('species'); // e.g., dog, cat
            $table->string('breed')->nullable();
            $table->date('birth_date')->nullable();
            $table->double('weight')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('pets'); }
};
