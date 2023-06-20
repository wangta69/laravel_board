<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class UpdatePondolBbsArticles extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    // Update BBS Articles table
    Schema::table('bbs_articles', function(BluePrint $table) {
      $table->integer('bbs_category_id')->unsigned()->nullable()->after('bbs_table_id');
    });
  }
}
