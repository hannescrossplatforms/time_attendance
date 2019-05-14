<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RenameConnectionColumnInRemotedbTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('remotedbs', function(Blueprint $table)
		{
            $table->renameColumn('connection', 'dbconnection');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('remotedbs', function(Blueprint $table)
		{
			//
		});
	}

}
