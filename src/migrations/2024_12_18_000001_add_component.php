<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class AddComponent extends Migration
{
  /**
   * Run the migrations.
   *
   * @return void
   */
  public function up()
  {

    if (!Schema::hasColumn('bbs_tables', 'component')) {
      Schema::table('bbs_tables', function (Blueprint $table) {
        $table->string('component', '50')->nullable()->after('section');
      });
    }

    if (!Schema::hasColumn('bbs_tables', 'blade')) {
      Schema::table('bbs_tables', function (Blueprint $table) {
        $table->string('blade', '50')->nullable()->after('section');
      });
    }

    Schema::table('bbs_tables', function (Blueprint $table) {
      $table->string('extends', '50')->nullable()->change();
      $table->string('section', '50')->nullable()->change();
    });

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
