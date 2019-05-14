<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSitenameToRep7dayTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('rep7day', function(Blueprint $table)
		{
			$table->string('sitename')->after("nasid");
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
