<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTabletposPrintersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('tabletposprinters', function($t){
			$t->increments('id');
			$t->string('name');
			$t->string('ipaddress');
			$t->integer('venue_id');
			$t->string('status');
			$t->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('tabletposprinters');
	}

}
