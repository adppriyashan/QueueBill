<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pending_invoice_lines', function (Blueprint $table) {
            $table->id();
            $table->foreignId('recurring_service_id')->constrained('recurring_services')->onDelete('cascade');
            $table->string('description', 255);
            $table->decimal('amount', 15, 2);
            $table->enum('billing_status', ['pending', 'invoiced'])->default('pending');
            $table->foreignId('invoice_id')->nullable()->constrained('invoices')->onDelete('set null');
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->unsignedBigInteger('created_by')->nullable();
            $table->unsignedBigInteger('updated_by')->nullable();
            $table->timestamps();

            $table->foreign('created_by')->references('id')->on('users')->onDelete('set null');
            $table->foreign('updated_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pending_invoice_lines');
    }
};
