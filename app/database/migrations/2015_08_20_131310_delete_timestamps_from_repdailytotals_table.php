<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeleteTimestampsFromRepdailytotalsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('repdailytotals', function(Blueprint $table)
		{
			$table->dropColumn(['created_at', 'updated_at']);
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('repdailytotals', function(Blueprint $table)
		{
			//
		});
	}

}
