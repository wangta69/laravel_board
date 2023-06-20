<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class UpdatePondolBbsArticles2 extends Migration
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
            $table->string('password')->after('user_name')->comment('비회원용 글수정 이나 비밀글 확인용');
        });
    }
}
