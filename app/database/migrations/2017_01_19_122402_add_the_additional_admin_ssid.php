<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddTheAdditionalAdminSsid extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('venues', function($t){

			$t->string('type1')->after('password1')->nullable();
			$t->string('adminssid2', 20)->after('type1')->nullable();
			$t->string('password2', 8)->after('adminssid2')->nullable();
           		$t->string('type2')->after('password2')->nullable();
            		$t->string('adminssid3', 20)->after('type2')->nullable();
            		$t->string('password3', 8)->after('adminssid3')->nullable();
            		$t->string('type3')->after('password3')->nullable();

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
		Schema::table('venues', function($t){
			$t->dropColumn('type1');
			$t->dropColumn('adminssid2');
			$t->dropColumn('password2');
            $t->dropColumn('type2');
            $t->dropColumn('adminssid3');
            $t->dropColumn('password3');
            $t->dropColumn('type3'); 


		});
			
			
	}

}