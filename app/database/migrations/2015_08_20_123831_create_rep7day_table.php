<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRep7dayTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('rep7day', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('nasid');

			$table->integer('currentsessions');
			$table->integer('previoussessions');
			$table->integer('diffsessions');
			
			$table->integer('currentnewsessions');
			$table->integer('previousnewsessions');
			$table->integer('diffnewsessions');
			
			$table->integer('currentminutes');
			$table->integer('previousminutes');
			$table->integer('diffminutes');
			
			$table->integer('currentdata');
			$table->integer('previousdata');
			$table->integer('diffdata');

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
