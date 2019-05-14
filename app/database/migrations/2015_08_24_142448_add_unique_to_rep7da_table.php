<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddUniqueToRep7daTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('rep7day', function(Blueprint $table)
		{
			$table->integer('currentunique')->after("percentsessions");
			$table->integer('previousunique')->after("currentunique");
			$table->integer('diffunique')->after("previousunique");
			$table->float('punique')->after("diffunique");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('rep7day', function(Blueprint $table)
		{
			//
		});
	}

}
