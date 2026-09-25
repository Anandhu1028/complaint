<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('complaints', function (Blueprint $table) {
            $table->id();
            $table->string('reference_no', 40)->unique();
            $table->string('full_name', 150);
            $table->string('mobile', 30);
            $table->string('email')->nullable();
            $table->text('address');
            $table->string('state', 100);
            $table->string('district', 100);
            $table->string('subject', 255);
            $table->text('details');
            $table->string('attachment_path')->nullable();
            $table->enum('status', ['new','forwarded','in_progress','resolved','closed'])->default('new');
            $table->timestamp('email_sent_at')->nullable();
            $table->text('email_error')->nullable();
            $table->timestamps();
            $table->index(['status','created_at']);
            $table->index(['mobile','created_at']);
        });
    }

    public function down(): void { Schema::dropIfExists('complaints'); }
};
