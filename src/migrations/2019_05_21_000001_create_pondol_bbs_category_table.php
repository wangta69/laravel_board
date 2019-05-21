<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreatePondolBbsCategoryTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Create BBS Config table
        Schema::create('bbs_categories', function(BluePrint $table) {
            $table->engine = 'InnoDB';

            $table->increments('id');
            $table->integer('bbs_table_id')->unsigned();
            $table->string('name', '20');
            $table->timestamps();
            $table->index('bbs_table_id');
            $table->foreign('bbs_table_id')->references('id')->on('bbs_tables')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('bbs_categories');
    }
}
