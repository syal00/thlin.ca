<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('media_files', function (Blueprint $table) {
            $table->string('cloudinary_public_id')->nullable()->after('file_path');
        });

        if (Schema::getConnection()->getDriverName() !== 'pgsql') {
            return;
        }

        DB::statement("
            ALTER TABLE pages
            ADD COLUMN IF NOT EXISTS search_vector tsvector
            GENERATED ALWAYS AS (
                to_tsvector('english',
                    coalesce(title, '') || ' ' ||
                    coalesce(excerpt, '') || ' ' ||
                    coalesce(body, '') || ' ' ||
                    coalesce(meta_title, '') || ' ' ||
                    coalesce(meta_description, '') || ' ' ||
                    coalesce(hero_title, '') || ' ' ||
                    coalesce(hero_subtitle, '') || ' ' ||
                    coalesce(meta_keywords, '')
                )
            ) STORED
        ");

        DB::statement('CREATE INDEX IF NOT EXISTS pages_search_vector_idx ON pages USING GIN (search_vector)');

        DB::statement("
            ALTER TABLE news_posts
            ADD COLUMN IF NOT EXISTS search_vector tsvector
            GENERATED ALWAYS AS (
                to_tsvector('english',
                    coalesce(title, '') || ' ' ||
                    coalesce(excerpt, '') || ' ' ||
                    coalesce(body, '') || ' ' ||
                    coalesce(location, '')
                )
            ) STORED
        ");

        DB::statement('CREATE INDEX IF NOT EXISTS news_posts_search_vector_idx ON news_posts USING GIN (search_vector)');

        DB::statement("
            ALTER TABLE careers
            ADD COLUMN IF NOT EXISTS search_vector tsvector
            GENERATED ALWAYS AS (
                to_tsvector('english',
                    coalesce(title, '') || ' ' ||
                    coalesce(body, '')
                )
            ) STORED
        ");

        DB::statement('CREATE INDEX IF NOT EXISTS careers_search_vector_idx ON careers USING GIN (search_vector)');

        DB::statement("
            ALTER TABLE board_members
            ADD COLUMN IF NOT EXISTS search_vector tsvector
            GENERATED ALWAYS AS (
                to_tsvector('english',
                    coalesce(name, '') || ' ' ||
                    coalesce(role, '') || ' ' ||
                    coalesce(bio, '')
                )
            ) STORED
        ");

        DB::statement('CREATE INDEX IF NOT EXISTS board_members_search_vector_idx ON board_members USING GIN (search_vector)');

        DB::statement("
            ALTER TABLE portfolio_items
            ADD COLUMN IF NOT EXISTS search_vector tsvector
            GENERATED ALWAYS AS (
                to_tsvector('english',
                    coalesce(title, '') || ' ' ||
                    coalesce(excerpt, '')
                )
            ) STORED
        ");

        DB::statement('CREATE INDEX IF NOT EXISTS portfolio_items_search_vector_idx ON portfolio_items USING GIN (search_vector)');
    }

    public function down(): void
    {
        if (Schema::getConnection()->getDriverName() === 'pgsql') {
            DB::statement('DROP INDEX IF EXISTS portfolio_items_search_vector_idx');
            DB::statement('ALTER TABLE portfolio_items DROP COLUMN IF EXISTS search_vector');

            DB::statement('DROP INDEX IF EXISTS board_members_search_vector_idx');
            DB::statement('ALTER TABLE board_members DROP COLUMN IF EXISTS search_vector');

            DB::statement('DROP INDEX IF EXISTS careers_search_vector_idx');
            DB::statement('ALTER TABLE careers DROP COLUMN IF EXISTS search_vector');

            DB::statement('DROP INDEX IF EXISTS news_posts_search_vector_idx');
            DB::statement('ALTER TABLE news_posts DROP COLUMN IF EXISTS search_vector');

            DB::statement('DROP INDEX IF EXISTS pages_search_vector_idx');
            DB::statement('ALTER TABLE pages DROP COLUMN IF EXISTS search_vector');
        }

        Schema::table('media_files', function (Blueprint $table) {
            $table->dropColumn('cloudinary_public_id');
        });
    }
};
