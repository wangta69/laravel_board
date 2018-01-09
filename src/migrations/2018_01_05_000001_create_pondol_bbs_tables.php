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
            $table->string('table_name')->unique();
            $table->string('skin');
            $table->timestamps();
            $table->softDeletes();
        });
        
        // 게시글 담을 테이블
        // 새로운 게시판을 생성할시 이 아래 정의한 테이블들만 새로 추가하면 됩니다.
        Schema::create('bbs_articles', function(BluePrint $table) {
            $table->increments('id');
            
            $table->integer('user_id')->unsigned();
            $table->integer('bbs_table_id')->unsigned();
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
