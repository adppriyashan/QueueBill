<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('recurring_services', function (Blueprint $table) {
            $table->id();
            $table->foreignId('company_id')->constrained('companies')->onDelete('cascade');
            $table->foreignId('invoice_structure_template_id')->constrained('invoice_structure_templates')->onDelete('cascade');
            $table->string('name', 255);
            $table->date('from_date');
            $table->date('to_date');
            $table->enum('recurring_cadence', ['month', '6_months', 'year']);
            $table->decimal('base_cost', 15, 2);
            $table->text('invoice_includes');
            $table->date('next_billing_date');
            $table->string('google_drive_path')->nullable();
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
        Schema::dropIfExists('recurring_services');
    }
};
