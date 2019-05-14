<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMacAddressBypassColumnsToVenuesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('venues', function($t){
			$t->char('bypassmac1', 17)->after('type3')->nullable();
			$t->char('bypasscomment1', 25)->after('bypassmac1')->nullable();
			$t->char('bypassmac2', 17)->after('bypasscomment1')->nullable();
			$t->char('bypasscomment2', 25)->after('bypassmac2')->nullable();
			$t->char('bypassmac3', 17)->after('bypasscomment2')->nullable();
			$t->char('bypasscomment3', 25)->after('bypassmac3')->nullable();
			$t->char('bypassmac4', 17)->after('bypasscomment3')->nullable();
			$t->char('bypasscomment4', 25)->after('bypassmac4')->nullable();
			$t->char('bypassmac5', 17)->after('bypasscomment4')->nullable();
			$t->char('bypasscomment5', 25)->after('bypassmac5')->nullable();
			$t->char('bypassmac6', 17)->after('bypasscomment5')->nullable();
			$t->char('bypasscomment6', 25)->after('bypassmac6')->nullable();
			$t->char('bypassmac7', 17)->after('bypasscomment6')->nullable();
			$t->char('bypasscomment7', 25)->after('bypassmac7')->nullable();
			$t->char('bypassmac8', 17)->after('bypasscomment7')->nullable();
			$t->char('bypasscomment8', 25)->after('bypassmac8')->nullable();
			$t->char('bypassmac9', 17)->after('bypasscomment8')->nullable();
			$t->char('bypasscomment9', 25)->after('bypassmac9')->nullable();
			$t->char('bypassmac10', 17)->after('bypasscomment9')->nullable();
			$t->char('bypasscomment10', 25)->after('bypassmac10')->nullable();
		});


		
		//
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
			$t->dropColumn('bypassmac1');
			$t->dropColumn('bypasscomment1');
			$t->dropColumn('bypassmac2');
			$t->dropColumn('bypasscomment2');
			$t->dropColumn('bypassmac3');
			$t->dropColumn('bypasscomment3');
			$t->dropColumn('bypassmac4');
			$t->dropColumn('bypasscomment4');
			$t->dropColumn('bypassmac5');
			$t->dropColumn('bypasscomment5');
			$t->dropColumn('bypassmac6');
			$t->dropColumn('bypasscomment6');
			$t->dropColumn('bypassmac7');
			$t->dropColumn('bypasscomment7');
			$t->dropColumn('bypassmac8');
			$t->dropColumn('bypasscomment8');
			$t->dropColumn('bypassmac9');
			$t->dropColumn('bypasscomment9');
			$t->dropColumn('bypassmac10');
			$t->dropColumn('bypasscomment10');

		});


		
	}

}
