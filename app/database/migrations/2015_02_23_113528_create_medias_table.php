<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMediasTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('medias', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('target');
			$table->string('brand_id');
			$table->string('dt_registration');
			$table->binary('dt_image');	
			$table->string('mb_registration');
			$table->binary('mb_image');	
			$table->string('countrie_id');	
			$table->string('province_id');	
			$table->string('citie_id');	
			$table->string('venue_id');	
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
		Schema::drop('medias');
	}

}
