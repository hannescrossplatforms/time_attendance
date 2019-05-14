<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddF7F8ToBrandsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('brands', function(Blueprint $table)
		{
			$table->string('f7_display', 32);
			$table->string('f7_placeholder', 255);
			$table->string('f7_type', 32);

			$table->string('f8_display', 32);
			$table->string('f8_placeholder', 255);
			$table->string('f8_type', 32);
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
