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
        Schema::create('usagelogs', function (Blueprint $table) {
            $table->id();
            $table->foreignUuid('workspace_id')->constrained(
                table: 'workspaces',
                indexName: 'usagelogs_workspace_id',
            )->cascadeOnDelete();
            $table->foreignId('apitoken_id')->constrained(
                table: 'apitokens',
                indexName: 'usagelogs_apitoken_id',
            )->cascadeOnDelete();
            $table->string('service', 50);
            $table->integer('duration');
            $table->decimal('cost_per_second', 10, 4);
            $table->timestamp('created_at')->useCurrent();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('usagelogs');
    }
};
