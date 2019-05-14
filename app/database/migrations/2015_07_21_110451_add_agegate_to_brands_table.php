<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddAgegateToBrandsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('brands', function(Blueprint $table)
		{
			$table->string('f4_display', 32);
			$table->integer('f4_agegate');
			$table->string('f4_type', 32);
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
