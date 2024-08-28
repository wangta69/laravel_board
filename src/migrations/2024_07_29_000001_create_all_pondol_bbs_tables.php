<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreateAllPondolBbsTables extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {

    // Create Articles Table
    if (!Schema::hasTable('bbs_articles')) {
      Schema::create('bbs_articles', function(BluePrint $table) {
        $table->id();
        $table->bigInteger('user_id')->nullable()->unsigned();
        $table->string('writer', '20')->nullable();
        $table->string('password', '20')->nullable();
        $table->bigInteger('bbs_table_id')->unsigned();
        $table->bigInteger('bbs_category_id')->unsigned()->nullable();
        $table->integer('order_num')->index()->comment('정렬번호');
        $table->bigInteger('parent_id')->unsigned()->comment('부모 id');
        $table->smallInteger('comment_cnt')->default(0)->unsigned()->comment('전체 댓글 표시');
        $table->string('reply_depth', '25')->nullable()->comment('reply 일경우 depth A AA B..');
        $table->string('text_type', '5')->comment('html, br');
        $table->string('title', '255');
        $table->string('keywords', '100')->nullable()->comment('키워드(laravel, php, ..) 로 컴마로 구분');
        $table->text('content');
        $table->string('image', '255')->nullable();
        $table->integer('hit')->unsigned()->default(0);
        $table->timestamps();
        $table->softDeletes();
      });
    }

    // Create BBS Config table
    if (!Schema::hasTable('bbs_tables')) {
      Schema::create('bbs_tables', function(BluePrint $table) {
        $table->id();
        $table->string('name');
        $table->string('table_name', '20')->unique();
        $table->string('skin', '20');
        $table->string('skin_admin', '20');
        $table->string('section', '50');
        $table->string('extends', '50');
        $table->tinyInteger('lists')->unsigned()->default(10)->comment('reply 기능 활성');
        $table->string('editor', '20')->comment('none, smartEditor');
        $table->tinyInteger('enable_reply')->unsigned()->default(0)->comment('articles count displayed on index');
        $table->tinyInteger('enable_comment')->unsigned()->default(0)->comment('comment 기능 활성');
        $table->tinyInteger('enable_qna')->unsigned()->default(0)->comment('1대1 기능 활성(관리자 및 글쓴이만 확인)');
        $table->tinyInteger('enable_password')->unsigned()->default(0)->comment('비회원 운영시 패스워드 입력');
        $table->string('auth_list', '10')->default('none')->comment('none: nonmember, login:logined member, role:has role member');
        $table->string('auth_write', '10')->default('none')->comment('none: nonmember, login:logined member, role:has role member');
        $table->string('auth_read', '10')->default('none')->comment('none: nonmember, login:logined member, role:has role member');
        $table->timestamps();
        $table->softDeletes();
      });
    }

    // Create BBS Categories table
    if (!Schema::hasTable('bbs_categories')) {
      Schema::create('bbs_categories', function(BluePrint $table) {
        $table->id();
        $table->bigInteger('bbs_table_id')->unsigned()->index();
        $table->string('name', '20');
        $table->tinyInteger('order')->unsigned()->default(0)->comment('카테고리 출력 순서');
        $table->timestamps();
        $table->foreign('bbs_table_id')->references('id')->on('bbs_tables')->onDelete('cascade');
      });
    }

    // Create Comments Table
    if (!Schema::hasTable('bbs_comments')) {
      Schema::create('bbs_comments', function(BluePrint $table) {
        $table->id();
        $table->bigInteger('user_id')->nullable()->unsigned();
        $table->string('writer', '20')->nullable();
        $table->bigInteger('bbs_articles_id')->index()->unsigned();
        $table->integer('order_num')->index()->comment('정렬번호');
        $table->bigInteger('parent_id')->unsigned()->comment('부모 id');
        $table->string('reply_depth', '25')->nullable()->comment('reply 일경우 depth A AA B..');
        $table->text('content')->nullable()->comment('사용자가 삭제할때 자식이 있을 경우 이곳을 null로 처리한다.');
        $table->timestamps();
        $table->softDeletes();
        $table->foreign('bbs_articles_id')->references('id')->on('bbs_articles')->onDelete('cascade');
      });
    }

    // Create BBS Config table
    if (!Schema::hasTable('bbs_config')) {
      Schema::create('bbs_config', function(BluePrint $table) {
        $table->id();
        $table->string('k', '20');
        $table->string('v', '50');
        $table->timestamps();
      });

      DB::table('bbs_config')->insert(
        [
          ['k' => 'extends','v' => 'bbs::admin.default-layout'],
          ['k' => 'section','v' => 'bbs-content']
        ]
      );
    }

    // Create Files Table
    if (!Schema::hasTable('bbs_files')) {
      Schema::create('bbs_files', function(BluePrint $table) {
        $table->id();
        $table->bigInteger('bbs_articles_id')->unsigned()->index();
        $table->string('file_name')->comment('original file name');
        $table->string('path_to_file')->comment('saved file path from storage');
        $table->string('name_on_disk')->comment('saved file name');
        $table->integer('rank')->unsigned();
        $table->timestamps();

        $table->foreign('bbs_articles_id')->references('id')->on('bbs_articles');
      });
    }

    // Create BBS Comments table
    if (!Schema::hasTable('bbs_item_comments')) {
      Schema::create('bbs_item_comments', function(BluePrint $table) {
        $table->id();
        $table->string('item', '10');

        $table->bigInteger('user_id')->nullable()->unsigned();
        $table->string('writer', '20');
        $table->bigInteger('item_id')->unsigned();
        $table->tinyInteger('rating')->unsigned()->nullable()->comment('점수');
        $table->integer('order_num')->comment('정렬번호');
        $table->bigInteger('parent_id')->unsigned()->comment('부모 id');
        $table->string('reply_depth', '25')->comment('reply 일경우 depth A AA B..');
        $table->text('content');
        $table->timestamps();
        $table->softDeletes();
      });
    }

    // Create BBS Roles Table
    if (!Schema::hasTable('bbs_roles')) {
      Schema::create('bbs_roles', function(BluePrint $table) {
        $table->bigInteger('bbs_tables_id')->unsigned();
        $table->bigInteger('read_role_id')->unsigned()->nullable();
        $table->bigInteger('write_role_id')->unsigned()->nullable();
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
    Schema::table('bbs_categories', function($table) {
      $table->dropForeign('bbs_categories_bbs_table_id_foreign');
    });

    Schema::table('bbs_comments', function($table) {
      $table->dropForeign('bbs_comments_bbs_articles_id_foreign');
    });

    Schema::table('bbs_files', function($table) {
      $table->dropForeign('bbs_files_bbs_articles_id_foreign');
    });

    Schema::dropIfExists('bbs_articles');
    Schema::dropIfExists('bbs_categories');
    Schema::dropIfExists('bbs_comments');
    Schema::dropIfExists('bbs_config');
    Schema::dropIfExists('bbs_files');
    Schema::dropIfExists('bbs_item_comments');
    Schema::dropIfExists('bbs_roles');
    Schema::dropIfExists('bbs_tables');
  }
}
