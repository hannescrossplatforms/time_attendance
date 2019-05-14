<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAdvertMediaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('advertmedias', function($table){
			$table->increments('id');
			$table->string('campaign');
			$table->string('medianame');
			$table->string('type');
			$table->integer('brand_id');
			$table->integer('countrie_id');
			$table->integer('province_id');
			$table->integer('citie_id');
			$table->integer('venue_id');
			$table->string('location');
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
		Schema::drop('advertmedias');
	}

}
