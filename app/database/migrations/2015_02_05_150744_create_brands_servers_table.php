<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsServersTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('brands_servers', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('brand_id'); // the id of the bear
    		$table->integer('server_id'); // the id of the picnic that this bear is at

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
		Schema::drop('brands_servers');
	}

}
