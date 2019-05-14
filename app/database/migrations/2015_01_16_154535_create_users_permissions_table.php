<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersPermissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users_permissions', function(Blueprint $table)
		{
			$table->increments('id');

			$table->integer('user_id'); // the id of the bear
    		$table->integer('permission_id'); // the id of the picnic that this bear is at

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
		Schema::drop('users_permissions');
	}

}
