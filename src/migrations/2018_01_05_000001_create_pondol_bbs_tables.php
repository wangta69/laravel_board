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
        // Create BBS Config table
        Schema::create('bbs_tables', function(BluePrint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->string('name');
            $table->string('table_name', '20')->unique();
            $table->string('skin', '20');
            $table->tinyInteger('lists')->unsigned()->default(10)->comment('reply 기능 활성');
			$table->string('editor', '20')->comment('none, smartEditor');
			$table->tinyInteger('enable_reply')->unsigned()->default(0)->comment('articles count displayed on index');
			$table->tinyInteger('enable_comment')->unsigned()->default(0)->comment('comment 기능 활성');

			$table->string('auth_write', '10')->default('login')->comment('none: nonmember, login:logined member, role:has role member');
			$table->string('auth_read', '10')->default('login')->comment('none: nonmember, login:logined member, role:has role member');
            $table->timestamps();
            $table->softDeletes();
        });


        // Create Articles Table
        Schema::create('bbs_articles', function(BluePrint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->unsigned();
			$table->string('user_name', '20')->nullable();
            $table->integer('bbs_table_id')->unsigned();
			$table->integer('order_num')->comment('정렬번호');
			$table->integer('parent_id')->unsigned()->comment('부모 id');
			$table->smallInteger('comment_cnt')->default(0)->unsigned()->comment('전체 댓글 표시');
			$table->string('reply_depth', '25')->nullable()->comment('reply 일경우 depth A AA B..');
			$table->string('text_type', '5')->comment('html, br');
            $table->string('title', '255');
            $table->text('content');
            $table->integer('hit')->unsigned()->default(0);
            $table->timestamps();
            $table->softDeletes();
			$table->index('order_num');
        });

		 // Create Comments Table
        Schema::create('bbs_comments', function(BluePrint $table) {
            $table->increments('id');
            $table->integer('user_id')->nullable()->unsigned();
			$table->string('user_name', '20')->nullable();
            $table->integer('bbs_articles_id')->unsigned();
			$table->integer('order_num')->comment('정렬번호');
            $table->integer('parent_id')->unsigned()->comment('부모 id');
			$table->string('reply_depth', '25')->nullable()->comment('reply 일경우 depth A AA B..');
            $table->text('content');
            $table->timestamps();
            $table->softDeletes();
			$table->index('order_num');
        });


        // Create Files Table
        Schema::create('bbs_files', function(BluePrint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->integer('bbs_articles_id')->unsigned();
            $table->string('file_name')->comment('original file name');
            $table->string('path_to_file')->comment('saved file path from storage');
            $table->string('name_on_disk')->comment('saved file name');
            $table->integer('rank')->unsigned();
            $table->timestamps();

            $table->foreign('bbs_articles_id')->references('id')->on('bbs_articles');
        });

    // Create BBS Roles Table
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

        Schema::dropIfExists('bbs_tables');
        Schema::dropIfExists('bbs_articles');
        Schema::dropIfExists('bbs_files');
		Schema::dropIfExists('bbs_roles');
		Schema::dropIfExists('bbs_comments');

    }
}
