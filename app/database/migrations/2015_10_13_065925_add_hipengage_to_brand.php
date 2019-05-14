<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddHipengageToBrand extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('brands', function(Blueprint $table)
		{
			$table->string('hipengage')->after("hipjam");
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
