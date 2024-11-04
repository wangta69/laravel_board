<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class AddFulltextPondolBbsArticles extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    // Create Articles Table
    if (Schema::hasTable('bbs_articles')) {
      if (!collect(DB::select("SHOW INDEXES FROM bbs_articles"))->pluck('Key_name')->contains('bbs_articles_title_content_fulltext')) {
        \DB::statement('ALTER TABLE bbs_articles ADD FULLTEXT bbs_articles_title_content_fulltext(title, content)');
      }
    }
  }
    

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::table('bbs_articles', function($table) {
      $table->dropFullText(['title', 'content']); // removing full-text index
    });

  }
}
