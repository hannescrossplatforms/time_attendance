<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateConnectpageMediaTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		//
		Schema::create('cpmedias', function($t){
			$t->increments('id');
			$t->integer('brand_id');
			$t->integer('countrie_id');
			$t->integer('province_id')->nullable();
			$t->integer('citie_id')->nullable();
			$t->integer('venue_id')->nullable();
			$t->string('location');
			$t->string('cp_medianame');
			$t->integer('cp_btn1_top');
			$t->integer('cp_btn1_left');
			$t->integer('cp_btn1_width');
			$t->integer('cp_btn1_height');
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
		//
		Schema::drop('cp_medias');
	}

}
