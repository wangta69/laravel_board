<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreateBbsItemCommentsTables extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    // Create BBS Config table
    if (!Schema::hasTable('bbs_item_comments')) {
      Schema::create('bbs_item_comments', function(BluePrint $table) {
        $table->id();
        $table->string('item', '10');

        $table->unsignedBigInteger('user_id');
        $table->string('user_name', '20');
        $table->unsignedBigInteger('item_id');
        $table->integer('order_num')->comment('정렬번호');
        $table->unsignedBigInteger('parent_id')->comment('부모 id');
        $table->string('reply_depth', '25')->comment('reply 일경우 depth A AA B..');
        $table->text('content');
        $table->timestamps();
        $table->softDeletes();
      });
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    // Schema::table('articles_files', function($table) {
    //   $table->dropForeign('bbs_files_bbs_articles_id_foreign');
    // });

    Schema::dropIfExists('bbs_item_comments');
    // Schema::dropIfExists('bbs_articles');
    // Schema::dropIfExists('bbs_files');
    // Schema::dropIfExists('bbs_roles');
    // Schema::dropIfExists('bbs_comments');

  }
}
