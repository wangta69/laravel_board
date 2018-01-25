<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreatePondolBbsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // 게시판 설정값 담을 테이블
        Schema::create('bbs_tables', function(BluePrint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->string('name');
            $table->string('table_name', '20')->unique();
            $table->string('skin', '20');
			$table->string('editor', '20')->comment('none, smartEditor');
			$table->string('auth_write', '10')->default('gen')->comment('none: nonmember, gen:logined member, roll:has roll member');
			$table->string('auth_read', '10')->default('gen')->comment('none: nonmember, gen:logined member, roll:has roll member');
            $table->timestamps();
            $table->softDeletes();
        });
        
        // 게시글 담을 테이블
        // 새로운 게시판을 생성할시 이 아래 정의한 테이블들만 새로 추가하면 됩니다.
        Schema::create('bbs_articles', function(BluePrint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->unsigned();
			$table->string('user_name', '20')->nullable();
            $table->integer('bbs_table_id')->unsigned();

			$table->integer('order_num')->comment('정렬번호');
			$table->integer('parent_id')->unsigned()->comment('부모 id');
			$table->smallInteger('is_comment')->default(0)->unsigned()->comment('부모글 : 0, comment : 1');
			$table->smallInteger('order_comment_num')->default(0)->unsigned()->comment('부모글은 전체 댓글 표시');
			$table->string('reply', '25')->nullable()->comment('reply 일경우 depth A AA B..');
			$table->string('reply_comment', '25')->nullable()->comment('comment 일경우 depth A AA B..');
			$table->string('text_type', '5')->comment('html, br');
            $table->string('title', '255');
            $table->text('content');
            $table->integer('hit')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
        });
        
        // 게시글에 연결된 파일 담을 테이블
        Schema::create('bbs_files', function(BluePrint $table) {
            $table->engine = 'InnoDB';
            
            $table->increments('id');
            $table->integer('bbs_articles_id')->unsigned();
            $table->string('file_name');
            $table->string('path_to_file');
            $table->string('name_on_disk');
            $table->integer('rank')->unsigned();
            $table->timestamps();
            
            $table->foreign('bbs_articles_id')->references('id')->on('bbs_articles');
        });

    // 게시판과 연결되 role
        Schema::create('bbs_roles', function(BluePrint $table) {
            $table->engine = 'InnoDB';
            
            $table->integer('bbs_tables_id')->unsigned();
            $table->integer('read_role_id')->unsigned()->nullable();
            $table->integer('write_role_id')->unsigned()->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('articles_files', function($table) {
            $table->dropForeign('bbs_files_bbs_articles_id_foreign');
        });
        
        Schema::drop('bbs_tables');
        Schema::drop('bbs_articles');
        Schema::drop('bbs_files');
    }
}
