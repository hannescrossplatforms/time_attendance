<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddNoregfieldsToBrandsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('brands', function(Blueprint $table)
		{
			$table->integer('register_field');

			$table->string('f1_display', 32);
			$table->string('f1_placeholder', 255);
			$table->string('f1_type', 32);

			$table->string('f2_display', 32);
			$table->string('f2_placeholder', 255);
			$table->string('f2_type', 32);

			$table->string('f3_display', 32);
			$table->string('f3_placeholder', 255);
			$table->string('f3_type', 32);
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
