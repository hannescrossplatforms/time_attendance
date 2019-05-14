<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class Admin extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */

	protected $table = 'admin';
	protected $connection = 'hiphub';

	public function __construct($con){
		error_log("In constructor");
		if($con) {
			$this->dbconnection = $con;
		} else {
			error_log("No connection defined");
		}
	}

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');


	public function getAdmins()
    {

    	Log::info('This is some useful information.');
        $results = DB::connection($this->dbconnection)->select('select * from admin');
        // $results = DB::connection('hiprm')->select('select * from users');
        print_r($results);

        return $results;
    }
}
