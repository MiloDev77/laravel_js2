<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('apitokens', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('workspace_id')->constrained(
                table: 'workspaces',
                indexName: 'apitokens_workspace_id',
            )->cascadeOnDelete();
            $table->string('name', 100);
            $table->string('token', 100);
            $table->timestamp('revoked_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('apitokens');
    }
};
