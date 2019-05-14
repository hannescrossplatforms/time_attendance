<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepdailytotalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('repdailytotals', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('nasid');

			$table->integer('sessions');
			$table->integer('dwelltime');
			$table->integer('data');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
