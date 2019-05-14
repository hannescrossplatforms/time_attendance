<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConnectBtnEnabledToMediasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('medias', function(Blueprint $table)
		{
			$table->integer('connect_btn_enabled')->after('ef_colour')->default(0);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('medias', function(Blueprint $table)
		{
			//
		});
	}

}
