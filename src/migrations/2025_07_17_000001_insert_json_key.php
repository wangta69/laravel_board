<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class  InsertJsonKeys extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {
    if (Schema::hasTable('json_key_values')) {
      DB::table('json_key_values')->updateOrInsert(
        ['key' => 'lnb.enable.pondol-bbs'],['v' => '1']
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
  }
}
