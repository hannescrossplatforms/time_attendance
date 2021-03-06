<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRepthismonthTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('repthismonth', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('nasid');
			$table->string('brandcode');
			$table->string('sitename');
			$table->string('calledstationid');

			$table->integer('currentsessions');
			$table->integer('previoussessions');
			$table->integer('diffsessions');
			$table->float('percentsessions');

			$table->integer('currentunique');
			$table->integer('previousunique');
			$table->integer('diffunique');
			$table->float('percentunique');

			$table->integer('currentnewusers');
			$table->integer('previousnewusers');
			$table->integer('diffnewusers');
			$table->integer('percentnewusers');
			
			$table->integer('currentminutes');
			$table->integer('previousminutes');
			$table->integer('diffminutes');
			$table->integer('percentminutes');
			
			$table->integer('currentdata');
			$table->integer('previousdata');
			$table->integer('diffdata');
			$table->integer('percentdata');

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
