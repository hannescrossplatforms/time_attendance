<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsCountriesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('brands_countries', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('brand_id'); // the id of the bear
    		$table->integer('countrie_id'); // the id of the picnic that this bear is at

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
		Schema::drop('brands_countries');
	}

}
