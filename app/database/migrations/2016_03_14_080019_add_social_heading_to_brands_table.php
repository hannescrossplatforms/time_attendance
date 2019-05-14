<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSocialHeadingToBrandsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('brands', function(Blueprint $table)
		{
			$table->string('sm_text');
			$table->string('sm_color');
			$table->string('sm_buttonsize');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('brands', function(Blueprint $table)
		{
			//
		});
	}

}
