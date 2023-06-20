<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;


class UpdatePondolBbsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Update BBS Articles table
        Schema::table('bbs_tables', function(BluePrint $table) {
            $table->string('extends')->nullable()->after('skin');
            $table->string('section')->nullable()->after('skin');
            $table->string('skin_admin')->nullable()->after('skin');
        });
    }
}