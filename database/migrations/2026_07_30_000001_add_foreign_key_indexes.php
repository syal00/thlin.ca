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
        Schema::table('pages', function (Blueprint $table) {
            $table->index(
                ['parent_id', 'status', 'sort_order', 'title'],
                'pages_parent_id_status_sort_order_title_index'
            );
            $table->index('created_by');
            $table->index('updated_by');
        });

        Schema::table('media_files', function (Blueprint $table) {
            $table->index('uploaded_by');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('media_files', function (Blueprint $table) {
            $table->dropIndex(['uploaded_by']);
        });

        Schema::table('pages', function (Blueprint $table) {
            $table->dropIndex('pages_parent_id_status_sort_order_title_index');
            $table->dropIndex(['created_by']);
            $table->dropIndex(['updated_by']);
        });
    }
};
