<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddPercentageToRep7daTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('rep7day', function(Blueprint $table)
		{
			$table->float('percentsessions')->after("diffsessions");
			$table->float('percentnewsessions')->after("diffnewsessions");
			$table->float('percentminutes')->after("diffminutes");
			$table->float('percentdata')->after("diffdata");
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
