<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddConnectFieldsToMediasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('medias', function(Blueprint $table)
		{
			$table->string('connect_btn_colour')->after('ef_colour')->nullable()->default(null);
			$table->string('connect_text_colour')->after('connect_btn_colour')->nullable()->default(null);
			$table->integer('connect_btn_offset_from_top')->after('connect_text_colour')->nullable()->default(null);
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
