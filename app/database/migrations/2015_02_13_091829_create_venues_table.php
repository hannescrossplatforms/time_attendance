<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateVenuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('venues', function($table)
		{
			$table->increments('id');
			$table->string('fullsitename')->unique();
			$table->string('sitename');
			$table->string('location')->unique();
			$table->string('macaddress')->unique();;
			$table->string('latitude');
			$table->string('longitude');
			$table->string('address');
			$table->string('server_id');
			$table->string('contact');
			$table->string('notes');
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
		Schema::drop('venues');
	}

}
