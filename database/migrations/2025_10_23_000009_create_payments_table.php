<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->string('payable_type');
            $table->unsignedBigInteger('payable_id')->nullable();
            $table->foreignId('account_id')->constrained()->cascadeOnDelete();
            $table->enum('method', ['cash','bank_transfer','cheque','card','mobile_wallet']);
            $table->decimal('amount', 15, 2);
            $table->date('paid_at');
            $table->string('reference')->nullable();
            $table->text('note')->nullable();
            $table->timestamps();
            $table->index(['payable_type','payable_id']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
    }
};
