<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class CreatePondolBbsConfig extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    // Create BBS Config table
    if (!Schema::hasTable('bbs_config')) {
      Schema::create('bbs_config', function(BluePrint $table) {
        $table->engine = 'InnoDB';

        $table->increments('id');
        $table->string('k');
        $table->string('v', '50')->unique();
        $table->timestamps();
      });

      DB::table('bbs_config')->insert(
        [
          ['k' => 'extends','v' => 'bbs.admin.default-layout'],
          ['k' => 'section','v' => 'bbs-content']
        ]
      );
    }
  }

  /**
   * Reverse the migrations.
   *
   * @return void
   */
  public function down()
  {
    Schema::dropIfExists('bbs_config');
  }
}
