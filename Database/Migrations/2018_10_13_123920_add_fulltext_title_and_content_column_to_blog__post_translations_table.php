<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFulltextTitleAndContentColumnToBlogPostTranslationsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('blog__post_translations', function (Blueprint $table) {

        });

        DB::statement('ALTER TABLE blog__post_translations ADD FULLTEXT fulltext_index (title, content)');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('blog__post_translations', function (Blueprint $table) {

        });
    }
}
