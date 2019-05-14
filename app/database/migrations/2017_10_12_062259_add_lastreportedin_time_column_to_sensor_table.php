<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddLastreportedinTimeColumnToSensorTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('sensors', function($t){
			$t->string('lastreportedin')->after('status')->nullable()->default(null);
		});
	}

	/**

	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('sensors', function($t){
			$t->dropColumn('lastreportedin');
		});
	}

}
