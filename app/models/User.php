<?php

use Illuminate\Auth\UserTrait;
use Illuminate\Auth\UserInterface;
use Illuminate\Auth\Reminders\RemindableTrait;
use Illuminate\Auth\Reminders\RemindableInterface;

class User extends Eloquent implements UserInterface, RemindableInterface {

	use UserTrait, RemindableTrait;

	/**
	 * The database table used by the model.
	 *
	 * @var string
	 */
	protected $table = 'users';

	/**
	 * The attributes excluded from the model's JSON form.
	 *
	 * @var array
	 */
	protected $hidden = array('password', 'remember_token');

	public function roles() {
        return $this->belongsToMany('Role');
    }	

    public function brands() {
        return $this->belongsToMany('Brand');
    }

    public function products() {
        return $this->belongsToMany('Product', 'users_products', 'user_id', 'product_id');
    }

    public function level() {
        return $this->belongsTo('Level', 'level_code', 'code');
    }

	public function getUserData($id)
    {
    	$data = array();
        $user = User::find($id);

		return $data;
    }

    public static function hasAccess($level_code) {

    	$user = Auth::user();
    	$allowed = false;
        
        if($user) { 

            if($user->level_code == $level_code) {

                $allowed = true;
            } 
        }

        return $allowed;
        
    }

    public static function hasProduct($product_name) {

        $user = Auth::user();
        $allowed = false;

        if($user) {            
            foreach ($user->products as $product)
            {
                // error_log("hasProduct : " . $product->name);
                if($product->name == $product_name) $allowed = true;
            }
        }
    	
    	return $allowed;
    }

    public static function hasPermission($level_code) {

        // TBD

    }

	public function getUsersData($product_id = null, $criteria = null)
    {
    	$data = array();

    	$thisuser = Auth::user();
    	$level_code = $thisuser->level_code;

        $brand_ids = array();
        if(\User::hasAccess("superadmin")) {
            $brands = \Brand::All();
        } else {
            $brands = $thisuser->brands;
        }
        foreach($brands as $brand) {
            error_log("getUsersData brands : " . $brand->name);
            array_push($brand_ids, $brand->id);
        }

        if($criteria) {
            $fullname = $criteria['fullname'];
            $email = $criteria['email'];
        } else {
            $fullname = "%";
            $email = "%";            
        }

        error_log("getUsersData fullname : $fullname");
        // error_log("getUsersData email : $email");

    	if($level_code == "superadmin") {
    		$administered_levels = array("superadmin", "admin", "reseller", "brandadmin", "mediamanager", "defaultuser");
    	} elseif($level_code == "admin") {
    		$administered_levels = array("reseller", "brandadmin", "mediamanager", "defaultuser");
    	} elseif($level_code == "reseller") {
    		$administered_levels = array("brandadmin", "mediamanager", "defaultuser");
    	} else {
            $administered_levels = array();
        }
        error_log("getUsersData administered_levels : " . print_r($administered_levels, true))   ;    

        if($product_id) {     
        error_log("getUsersData yesss product_id")   ;    
            $data['users'] = DB::table('users')
                // ->join('levels', 'users.level_code', '=', 'levels.code')
                ->join('users_products', 'users.id', '=', 'users_products.user_id')
                ->join('brand_user', 'users.id', '=', 'brand_user.user_id')
                ->select('users.*')
                ->distinct()
                ->where('fullname', 'like', $fullname)
                ->where('email', 'like', $email)
                ->whereIn('levels.code', $administered_levels)
                ->whereIn('brand_user.brand_id', $brand_ids)
                ->where('users_products.product_id', '=', 1)
                ->orderBy('users.fullname','ASC')
                ->get();
        } else {
        error_log("getUsersData nooo product_id")   ;    
            $data['users'] = DB::table('users')
                ->join('levels', 'users.level_code', '=', 'levels.code')
                ->join('brand_user', 'users.id', '=', 'brand_user.user_id')
                ->select('users.*')
                ->distinct()
                ->where('fullname', 'like', $fullname)
                ->where('email', 'like', $email)
                ->whereIn('levels.code', $administered_levels)
                ->whereIn('brand_user.brand_id', $brand_ids)
                ->orderBy('users.fullname','ASC')
                ->get();          
        }

            // echo "<pre>";
            // print_r($administered_levels);
        foreach($data['users'] as $user) {
            error_log("getUsersData users : " . $user->fullname) ;    
            // print_r($user);
            $level = \Level::where('code', 'LIKE', $user->level_code)->first();
            $user->level_name = $level->name;
        }
            // echo "<pre>";
            // dd();

        return $data;
    }

	public function getUsersForProduct($id){

		$users = \Product::find(1)->users()->where('user_products.product_id', 1)->get();

	}

	public function getUserStruct($id)
    {

    	$data = array();



        return $data;
    }

}
