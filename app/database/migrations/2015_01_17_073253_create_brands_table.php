<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBrandsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('brands', function(Blueprint $table)
		{
			$table->increments('id');

			$table->string('name')->unique();;
			$table->string('description');
			$table->string('code')->unique();;
			$table->integer('countrie_id');
			$table->string('welcome');
			$table->string('uru');
			$table->string('ssid');
			$table->integer('limit');
			$table->integer('hipwifi');
			$table->integer('hiprm');
			$table->integer('hipjam');

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
		Schema::drop('brands');
	}

}
