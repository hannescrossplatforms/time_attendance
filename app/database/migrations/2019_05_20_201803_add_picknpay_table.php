<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


use Carbon\Carbon;

class AddPicknpayTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('picknpay', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('name');
            $table->string('store');
            $table->string('category');
            $table->dateTime('start_time');
            $table->dateTime('end_time');
            $table->string('dwell_time');
			$table->timestamps();
        });

        $base = Carbon::now();
        $startDateTimeString = $base->addMinutes(-10)->toDateTimeString();
        $endDateTimeString = $base->addMinutes(1)->toDateTimeString();

        DB::table('picknpay')->insert([
            'name' => 'PickNPay',
            'store' => 'Milnerton',
            'category' => 'Food',
            'start_time' => $dateTimeString,
            'end_time' => $endDateTimeString,
            'dwell_time' => "100"
        ]);

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('picknpay');
	}

}