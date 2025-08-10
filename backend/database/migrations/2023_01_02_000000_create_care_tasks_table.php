<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database->Schema\Blueprint;
use Illuminate->Support\Facades\Schema;
return new class extends Migration {
    public function up(): void {
        Schema::create('care_tasks', function (Blueprint $table) {
            $table->id();
            $table->foreignId('pet_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade'); # 方便查詢使用者所有任務
            $table->string('title');
            $table->text('description')->nullable();
            $table->timestamp('due_date')->nullable();
            $table->string('status')->default('pending'); // pending, completed, overdue
            $table->boolean('is_recurring')->default(false);
            $table->json('recurring_rule')->nullable(); # e.g., {'frequency': 'daily', 'interval': 1}
            $table->timestamp('completed_at')->nullable();
            $table->timestamps();
        });
    }
    public function down(): void { Schema::dropIfExists('care_tasks'); }
};
