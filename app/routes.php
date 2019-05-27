<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It's a breeze. Simply tell Laravel the URIs it should respond to
| and give it the Closure to execute when that URI is requested.
|
*/

// Test Routes
//Route::get('/test', array('as' => 'home', function () {
//    return View::make('test');
//}));

// Test Routes
Route::get('/test', array('as' => 'home', function () {
    return View::make('pdfreport.absence_report');
}));

// Login Routes
Route::get('/', array('as' => 'home', function () {
    $data = array();
    $data["systemstate"] = \DB::table('systemconfig')->where('name', '=', "systemstate")->first()->value;
    return View::make('login')->with('data', $data);
}));

Route::get('/support', array('as' => 'support', function () {
    $data = array();
    $data['currentMenuItem'] = "Support";
    return View::make('support')->with('data', $data);
}));

Route::get('/login', array('as' => 'login', function () {
    $data = array();
    $data["systemstate"] = \DB::table('systemconfig')->where('name', '=', "systemstate")->first()->value;
    return View::make('login')->with('data', $data);

}))->before('guest');

Route::post('login', function () {
    error_log("In POST login");
        $user = array(
            'email' => Input::get('email'),
            'password' => Input::get('password')
        );
    // error_log("In POST login : " . print_r($user, true));
    print_r($user, true);

        if (Auth::attempt($user)) {
            return Redirect::route('dashboard')
                ->with('flash_notice', 'You are successfully logged in.');
        }

        // authentication failure! lets go back to the login page
        return Redirect::route('login')
            ->with('flash_error', 'Your username/password combination was incorrect.')
            ->withInput();
});

Route::get('/login_forgotpassword', array('as' => 'login_forgotpassword', function () {
    return View::make('login_forgotpassword');
}));

Route::get('logout', array('as' => 'logout', function () {
    Auth::logout();
    return Redirect::route('home')->with('flash_notice', 'You are successfully logged out.');
}))->before('auth');

Route::get('/viewreportemail', array('as' => 'sendtestemail', function () {
    return View::make('hiptna.reportemail');
}));
Route::get('/sendtestemail', array('as' => 'sendtestemail', function () {
    return View::make('testemail');
}));
Route::any('/lib_sendtestemail', array('uses' => 'lib\LibController@lib_sendtestemail', 'as' => 'lib_sendtestemail'));


// Admin routes //////////////////////////////////////////////////////////
Route::any('/admin_showdashboard', array('uses' => 'admin\AdminController@admin_showdashboard', 'as' => 'admin_showdashboard'))->before('auth');
Route::any('/admin_showrolesandpermissions', array('uses' => 'admin\AdminController@admin_showrolesandpermissions', 'as' => 'admin_showrolesandpermissions'))->before('auth');
Route::any('/admin_showbrands', array('uses' => 'admin\AdminController@admin_showbrands', 'as' => 'admin_showbrands'))->before('auth');
// Admin Brand Management
Route::get('admin_showbrands/{json?}', array('uses' => 'admin\AdminController@admin_showBrands', 'as' => 'admin_showbrands'))->before('auth');
Route::get('admin_addbrand', array('uses' => 'admin\AdminController@admin_addBrand', 'as' => 'admin_addbrand'))->before('auth');
Route::post('admin_addbrand', array('uses' => 'admin\AdminController@admin_addBrandSave', 'as' => 'admin_addbrand'))->before('auth');
Route::get('admin_editbrand/{id}', array('uses' => 'admin\AdminController@admin_editBrand', 'as' => 'admin_editbrand'))->before('auth');
Route::post('admin_editbrand', array('uses' => 'admin\AdminController@admin_editBrandSave', 'as' => 'admin_editbrand'))->before('auth');
Route::get('admin_deletebrand/{id}', array('uses' => 'admin\AdminController@admin_deleteBrand', 'as' => 'admin_deletebrand'))->before('auth');
// Admin Venue Management
Route::any('/admin_showvenues/{json?}', array('uses' => 'admin\AdminController@admin_showvenues', 'as' => 'admin_showvenues'))->before('auth');
Route::get('admin_addvenue', array('uses' => 'admin\AdminController@admin_addVenue', 'as' => 'admin_addvenue'))->before('auth');
Route::post('admin_addvenue', array('uses' => 'admin\AdminController@admin_addVenueSave', 'as' => 'admin_addvenue'))->before('auth');
Route::get('admin_editvenue/{id}', array('uses' => 'admin\AdminController@admin_editVenue', 'as' => 'admin_editvenue'))->before('auth');
Route::post('admin_editvenue', array('uses' => 'admin\AdminController@admin_editVenueSave', 'as' => 'admin_editvenue'))->before('auth');
Route::get('admin_deletevenue/{id}', array('uses' => 'admin\AdminController@admin_deleteVenue', 'as' => 'admin_deletevenue'))->before('auth');

// Admin Roles and Permissions
Route::get('admin_roleedit/{id}', array('uses' => 'admin\AdminController@roleedit', 'as' => 'admin_roleedit'))->before('auth');
Route::get('admin_getAvailableProducts/', array('uses' => 'admin\AdminController@getAvailableProducts', 'as' => 'admin_getAvailableProducts'))->before('auth');
Route::get('admin_addPermission/', array('uses' => 'admin\AdminController@addPermission', 'as' => 'admin_addPermission'))->before('auth');
Route::any('/admin_showAvailablePermission', array('uses' => 'admin\AdminController@showAvailablePermission', 'as' => 'admin_showAvailablePermission'))->before('auth');
Route::any('admin_deletePermission', array('uses' => 'admin\AdminController@deletePermission', 'as' => 'admin_deletePermission'))->before('auth');
Route::any('admin_updatePermission', array('uses' => 'admin\AdminController@updatePermission', 'as' => 'admin_updatePermission'))->before('auth');
Route::any('admin_showAvailableRoles', array('uses' => 'admin\AdminController@showAvailableRoles', 'as' => 'admin_showAvailableRoles'))->before('auth');
Route::get('admin_addRole/', array('uses' => 'admin\AdminController@addRole', 'as' => 'admin_addRole'))->before('auth');
Route::any('admin_deleteRole', array('uses' => 'admin\AdminController@deleteRole', 'as' => 'admin_deleteRole'))->before('auth');
Route::any('admin_editRole', array('uses' => 'admin\AdminController@editRole', 'as' => 'admin_editRole'))->before('auth');

// Useradmin routes //////////////////////////////////////////////////////////
Route::get('useradmin_showusers/{json?}', array('uses' => 'useradmin\UseradminController@showUsers', 'as' => 'useradmin_showusers'))->before('auth');
Route::get('useradmin_add', array('uses' => 'useradmin\UseradminController@addUser', 'as' => 'useradmin_add'))->before('auth');
Route::post('useradmin_add', array('uses' => 'useradmin\UseradminController@addUserSave', 'as' => 'useradmin_add'))->before('auth');
Route::get('useradmin_edit/{id}', array('uses' => 'useradmin\UseradminController@editUser', 'as' => 'useradmin_edit'))->before('auth');
Route::post('useradmin_edit', array('uses' => 'useradmin\UseradminController@editUserSave', 'as' => 'useradmin_edit'))->before('auth');
Route::get('useradmin_delete/{id}', array('uses' => 'useradmin\UseradminController@deleteUser', 'as' => 'useradmin_delete'))->before('auth');
Route::get('useradmin_getrolesforproduct/', array('uses' => 'useradmin\UseradminController@getRolesForProduct', 'as' => 'useradmin_getrolesforproduct'))->before('auth');
Route::get('useradmin_getrolesforuserandproduct/', array('uses' => 'useradmin\UseradminController@getRolesForUserandProduct', 'as' => 'useradmin_getrolesforuserandproduct'))->before('auth');
Route::get('useradmin_addtnapermission/', array('uses' => 'useradmin\UseradminController@addRolesForUserandProduct', 'as' => 'useradmin_addtnapermission'))->before('auth');
Route::get('useradmin_deletetnapermission/', array('uses' => 'useradmin\UseradminController@deleteRolesForUserandProduct', 'as' => 'useradmin_deletetnapermission'))->before('auth');


// HipRM routes //////////////////////////////////////////////////////////////
Route::any('/hiprm_showdashboard', array('uses' => 'hiprm\HiprmController@showDashboard', 'as' => 'hiprm_showdashboard'))->before('auth');
// Brand Management
Route::get('hiprm_showbrands', array('uses' => 'hiprm\HiprmController@showBrands', 'as' => 'hiprm_showbrands'))->before('auth');
Route::get('hiprm_addbrand', array('uses' => 'hiprm\HiprmController@addBrand', 'as' => 'hiprm_addbrand'))->before('auth');
Route::post('hiprm_addbrand', array('uses' => 'hiprm\HiprmController@addBrandSave', 'as' => 'hiprm_addbrand'))->before('auth');
Route::get('hiprm_editbrand/{id}', array('uses' => 'hiprm\HiprmController@editBrand', 'as' => 'hiprm_editbrand'))->before('auth');
Route::post('hiprm_editbrand', array('uses' => 'hiprm\HiprmController@editBrandSave', 'as' => 'hiprm_editbrand'))->before('auth');
// Admin Management
Route::get('hiprm_showusers/{json?}', array('uses' => 'hiprm\HiprmController@showUsers', 'as' => 'hiprm_showusers'))->before('auth');
Route::get('hiprm_adduser', array('uses' => 'hiprm\HiprmController@addUser', 'as' => 'hiprm_adduser'))->before('auth');
Route::post('hiprm_adduser', array('uses' => 'hiprm\HiprmController@addUserSave', 'as' => 'hiprm_adduser'))->before('auth');
Route::get('hiprm_edituser/{id}', array('uses' => 'hiprm\HiprmController@editUser', 'as' => 'hiprm_edituser'))->before('auth');
Route::post('hiprm_edituser', array('uses' => 'hiprm\HiprmController@editUserSave', 'as' => 'hiprm_edituser'))->before('auth');
// Media Management
Route::get('hiprm_showmedias/{json?}', array('uses' => 'hiprm\HiprmController@showMedias', 'as' => 'hiprm_showmedias'))->before('auth');
Route::get('hiprm_addmedia', array('uses' => 'hiprm\HiprmController@addMedia', 'as' => 'hiprm_addmedia'))->before('auth');
Route::post('hiprm_addmedia', array('uses' => 'hiprm\HiprmController@addMediaSave', 'as' => 'hiprm_addmedia'))->before('auth');
Route::get('hiprm_editmedia/{id}', array('uses' => 'hiprm\HiprmController@editMedia', 'as' => 'hiprm_editmedia'))->before('auth');
Route::post('hiprm_editmedia', array('uses' => 'hiprm\HiprmController@editMediaSave', 'as' => 'hiprm_editmedia'))->before('auth');
// Insight Management
Route::get('hiprm_showinsights/{json?}', array('uses' => 'hiprm\HiprmController@showInsights', 'as' => 'hiprm_showinsights'))->before('auth');
Route::get('hiprm_addinsight', array('uses' => 'hiprm\HiprmController@addInsight', 'as' => 'hiprm_addinsight'))->before('auth');
Route::post('hiprm_addinsight', array('uses' => 'hiprm\HiprmController@addInsightSave', 'as' => 'hiprm_addinsight'))->before('auth');
Route::get('hiprm_editinsight/{id}', array('uses' => 'hiprm\HiprmController@editInsight', 'as' => 'hiprm_editinsight'))->before('auth');
Route::post('hiprm_editinsight', array('uses' => 'hiprm\HiprmController@editInsightSave', 'as' => 'hiprm_editinsight'))->before('auth');
// Report Management
Route::get('hiprm_showreports/{json?}', array('uses' => 'hiprm\HiprmController@showReports', 'as' => 'hiprm_showreports'))->before('auth');
Route::get('hiprm_addreport', array('uses' => 'hiprm\HiprmController@addReport', 'as' => 'hiprm_addreport'))->before('auth');
Route::post('hiprm_addreport', array('uses' => 'hiprm\HiprmController@addReportSave', 'as' => 'hiprm_addreport'))->before('auth');
Route::get('hiprm_editreport/{id}', array('uses' => 'hiprm\HiprmController@editReport', 'as' => 'hiprm_editreport'))->before('auth');
Route::post('hiprm_editreport', array('uses' => 'hiprm\HiprmController@editReportSave', 'as' => 'hiprm_editreport'))->before('auth');


// HipWifi routes //////////////////////////////////////////////////////////////
Route::any('/hipwifi_showdashboard', array('uses' => 'hipwifi\HipwifiController@showDashboard', 'as' => 'hipwifi_showdashboard'))->before('auth');
// Brand Management
Route::get('hipwifi_showbrands/{json?}', array('uses' => 'hipwifi\HipwifiController@showBrands', 'as' => 'hipwifi_showbrands'))->before('auth');
Route::get('hipwifi_getinactivebrands/', array('uses' => 'hipwifi\HipwifiController@getInactiveBrands', 'as' => 'hipwifi_getinactivebrands'))->before('auth');
Route::get('hipwifi_activatebrand/{id}', array('uses' => 'hipwifi\HipwifiController@activateBrand', 'as' => 'hipwifi_activatebrand'))->before('auth');
Route::post('hipwifi_activatebrand', array('uses' => 'hipwifi\HipwifiController@activateBrandSave', 'as' => 'hipwifi_activatebrand'))->before('auth');

Route::get('hipwifi_addbrand', array('uses' => 'hipwifi\HipwifiController@addBrand', 'as' => 'hipwifi_addbrand'))->before('auth');
Route::post('hipwifi_addbrand', array('uses' => 'hipwifi\HipwifiController@addBrandSave', 'as' => 'hipwifi_addbrand'))->before('auth');
Route::get('hipwifi_editbrand/{id}', array('uses' => 'hipwifi\HipwifiController@editBrand', 'as' => 'hipwifi_editbrand'))->before('auth');
Route::post('hipwifi_editbrand', array('uses' => 'hipwifi\HipwifiController@editBrandSave', 'as' => 'hipwifi_editbrand'))->before('auth');
Route::get('hipwifi_deletebrand/{id}', array('uses' => 'hipwifi\HipwifiController@deleteBrand', 'as' => 'hipwifi_deletebrand'))->before('auth');
// Admin Management
Route::get('hipwifi_showusers/{json?}', array('uses' => 'hipwifi\HipwifiController@showUsers', 'as' => 'hipwifi_showusers'))->before('auth');
Route::get('hipwifi_adduser', array('uses' => 'hipwifi\HipwifiController@addUser', 'as' => 'hipwifi_adduser'))->before('auth');
Route::post('hipwifi_adduser', array('uses' => 'hipwifi\HipwifiController@addUserSave', 'as' => 'hipwifi_adduser'))->before('auth');
Route::get('hipwifi_edituser/{id}', array('uses' => 'hipwifi\HipwifiController@editUser', 'as' => 'hipwifi_edituser'))->before('auth');
Route::post('hipwifi_edituser', array('uses' => 'hipwifi\HipwifiController@editUserSave', 'as' => 'hipwifi_edituser'))->before('auth');
Route::get('hipwifi_deleteuser/{id}', array('uses' => 'hipwifi\HipwifiController@deleteUser', 'as' => 'hipwifi_deleteuser'))->before('auth');
// Server Management
Route::get('hipwifi_showservers/{json?}', array('uses' => 'hipwifi\HipwifiController@showServers', 'as' => 'hipwifi_showservers'))->before('auth');
Route::get('hipwifi_addserver', array('uses' => 'hipwifi\HipwifiController@addServer', 'as' => 'hipwifi_addserver'))->before('auth');
Route::post('hipwifi_addserver', array('uses' => 'hipwifi\HipwifiController@addServerSave', 'as' => 'hipwifi_addserver'))->before('auth');
Route::get('hipwifi_editserver/{id}', array('uses' => 'hipwifi\HipwifiController@editServer', 'as' => 'hipwifi_editserver'))->before('auth');
Route::post('hipwifi_editserver', array('uses' => 'hipwifi\HipwifiController@editServerSave', 'as' => 'hipwifi_editserver'))->before('auth');
Route::get('hipwifi_deleteserver/{id}', array('uses' => 'hipwifi\HipwifiController@deleteServer', 'as' => 'hipwifi_deleteserver'))->before('auth');
// Venue Management
Route::get('hipwifi_showvenues/{json?}', array('uses' => 'hipwifi\HipwifiController@showVenues', 'as' => 'hipwifi_showvenues'))->before('auth');
Route::get('hipwifi_getinactivevenues/', array('uses' => 'hipwifi\HipwifiController@getInactiveVenues', 'as' => 'hipwifi_getinactivevenues'))->before('auth');
Route::get('hipwifi_activatevenue/{id}', array('uses' => 'hipwifi\HipwifiController@activateVenue', 'as' => 'hipwifi_activatevenue'))->before('auth');
Route::post('hipwifi_activatevenue', array('uses' => 'hipwifi\HipwifiController@activateVenueSave', 'as' => 'hipwifi_activatevenue'))->before('auth');

Route::get('hipwifi_addvenue', array('uses' => 'hipwifi\HipwifiController@addVenue', 'as' => 'hipwifi_addvenue'))->before('auth');
Route::post('hipwifi_addvenue', array('uses' => 'hipwifi\HipwifiController@addVenueSave', 'as' => 'hipwifi_addvenue'))->before('auth');
Route::get('hipwifi_editvenue/{id}', array('uses' => 'hipwifi\HipwifiController@editVenue', 'as' => 'hipwifi_editvenue'))->before('auth');
Route::post('hipwifi_editvenue', array('uses' => 'hipwifi\HipwifiController@editVenueSave', 'as' => 'hipwifi_editvenue'))->before('auth');
Route::get('hipwifi_deletevenue/{id}', array('uses' => 'hipwifi\HipwifiController@deleteVenue', 'as' => 'hipwifi_deletevenue'))->before('auth');
Route::get('hipwifi_disablevenue/{id}', array('uses' => 'hipwifi\HipwifiController@disablevenue', 'as' => 'hipwifi_disablevenue'))->before('auth');
Route::get('hipwifi_redeploymikrotikvenue/{id}', array('uses' => 'hipwifi\HipwifiController@redeployMikrotikVenue', 'as' => 'hipwifi_redeploymikrotikvenue'))->before('auth');
Route::get('hipwifi_deployrsc/{id}', array('uses' => 'hipwifi\HipwifiController@deployRsc', 'as' => 'hipwifi_deployrsc'))->before('auth');
Route::post('hipwifi_deployrsc', array('uses' => 'hipwifi\HipwifiController@deployRscSave', 'as' => 'hipwifi_deployrsc'))->before('auth');

//Adding, Updating and deleting Tabletpos printers at a venue;
Route::post('hipwifi_addtabletposprinter', array('uses' => 'hipwifi\HipwifiController@addTabletposPrinter', 'as' => 'hipwifi_addtabletposprinters'))->before('auth');
Route::post('hipwifi_edittabletposprinter', array('uses' => 'hipwifi\HipwifiController@editTabletposPrinter', 'as' => 'hipwifi_edittabletposprinters'))->before('auth');
Route::post('hipwifi_deletetabletposprinter', array('uses' => 'hipwifi\HipwifiController@deleteTabletposPrinter', 'as' => 'hipwifi_deletetabletposprinters'))->before('auth');
//Displaying Tabletpos printers on the hipwifi venue monitoring page
Route::post('hipwifi_showtabletposprinters', array('uses' => 'hipwifi\HipwifiMonitoringController@showTabletposPrinters', 'as' => 'hipwifi_showtabletposprinters'))->before('auth');



// Media Management
Route::get('hipwifi_showmedias/{json?}', array('uses' => 'hipwifi\HipwifiMediaController@showMedias', 'as' => 'hipwifi_showmedias'))->before('auth');
//new page that lists all brands belonging to a user regardless of if there exist a media entry for the brand or not.
Route::get('hipwifi_showbrandmedia', array('uses' => 'hipwifi\HipwifiMediaController@showBrands', 'as' => 'hipwifi_showbrandmedia'))->before('auth');
//editing advert media
Route::get('hipwifi_editadvertmedia/{id}/{brandid}', array('uses' => 'hipwifi\HipwifiMediaController@editAdvertMedia', 'as' =>'hipwifi_editadvertmedia'))->before('auth');
Route::post('hipwifi_editadvertmediasave', array('uses' => 'hipwifi\HipwifiMediaController@editAdvertMediaSave', 'as' =>'hipwifi_editadvertmediasave'))->before('auth');
Route::get('hipwifi_showsinglebrandmedia/{id}', array('uses' => 'hipwifi\HipwifiMediaController@showSingleBrandMedia', 'as' => 'hipwifi_showsinglebrandmedia'))->before('auth');
Route::get('hipwifi_deleteadvertmedia/{id}/{brandid}', array('uses' => 'hipwifi\HipwifiMediaController@deleteAdvertMedia', 'as' => 'hipwifi_deleteadvertmedia'))->before('auth');
Route::get('hipwifi_addmedia/{id}', array('uses' => 'hipwifi\HipwifiMediaController@addMedia', 'as' => 'hipwifi_addmedia'))->before('auth');
Route::post('hipwifi_addmedia', array('uses' => 'hipwifi\HipwifiMediaController@addMediaSave', 'as' => 'hipwifi_addmedia'))->before('auth');
Route::get('hipwifi_editmedia/{id}', array('uses' => 'hipwifi\HipwifiMediaController@editMedia', 'as' => 'hipwifi_editmedia'))->before('auth');
Route::post('hipwifi_editmedia', array('uses' => 'hipwifi\HipwifiMediaController@editMediaSave', 'as' => 'hipwifi_editmedia'))->before('auth');
Route::get('hipwifi_deletemedia/{id}', array('uses' => 'hipwifi\HipwifiMediaController@deleteMedia', 'as' => 'hipwifi_deletemedia'))->before('auth');
Route::get('hipwifi_preview/{type}', array('uses' => 'hipwifi\HipwifiMediaController@preview', 'as' => 'hipwifi_preview'))->before('auth');
// Adding advert
Route::get('hipwifi_addadvert/{id}', array('uses' => 'hipwifi\HipwifiMediaController@addAdvert', 'as' => 'hipwifi_addadvert'))->before('auth');
Route::post('hipwifi_addadvertsave', array('uses' => 'hipwifi\HipwifiMediaController@addAdvertSave', 'as' => 'hipwifi_addadvertsave'))->before('auth');
// Adding connect page media
Route::get('hipwifi_addconnectpage/{id}', array('uses' => 'hipwifi\HipwifiMediaController@addConnectPage', 'as' => 'hipwifi_addconnectpage'))->before('auth');
Route::post('hipwifi_addconnectpagesave', array('uses' => 'hipwifi\HipwifiMediaController@addConnectPageSave', 'as' => 'hipwifi_addconnectpagesave'))->before('auth');

Route::get('hipwifi_editcpmedia/{id}', array('uses' => 'hipwifi\HipwifiMediaController@editCpMedia', 'as' =>'hipwifi_editcpmedia'))->before('auth');
Route::post('hipwifi_editcpmediasave', array('uses' => 'hipwifi\HipwifiMediaController@editCpMediaSave', 'as' =>'hipwifi_editcpmediasave'))->before('auth');
Route::get('hipwifi_deletecpmedia/{id}/{brandid}', array('uses' => 'hipwifi\HipwifiMediaController@deleteCpMedia', 'as' => 'hipwifi_deletecpmedia'))->before('auth');

// User Management
Route::any('/hipwifi_showwifiusers', array('uses' => 'hipwifi\HipwifiWifiUsersController@showWifiUsers', 'as' => 'hipwifi_showwifiusers'))->before('auth');
Route::get('hipwifi_editwifiuser/{id}', array('uses' => 'hipwifi\HipwifiController@editWifiuser', 'as' => 'hipwifi_editwifiuser'))->before('auth');
Route::post('hipwifi_editwifiuser', array('uses' => 'hipwifi\HipwifiController@editWifiuserSave', 'as' => 'hipwifi_editwifiuser'))->before('auth');
Route::get('hipwifi_deletewifiuser/{id}', array('uses' => 'hipwifi\HipwifiController@deleteWifiuser', 'as' => 'hipwifi_deletewifiuser'))->before('auth');
// Venue Monitoring
Route::any('/hipwifi_showmonitoring', array('uses' => 'hipwifi\HipwifiMonitoringController@showMonitoring', 'as' => 'hipwifi_showmonitoring'))->before('auth');
// Statistics
Route::any('/hipwifi_showstatistics/{json?}', array('uses' => 'hipwifi\HipwifiStatisticsController@showStatistics', 'as' => 'hipwifi_showstatistics'))->before('auth');


// HipJAM routes //////////////////////////////////////////////////////////////
Route::any('/hipjam_showdashboard', array('uses' => 'hipjam\HipjamController@showDashboard', 'as' => 'hipjam_showdashboard'))->before('auth');
// User Management
Route::get('hipjam_showusers/{json?}', array('uses' => 'hipjam\HipjamController@showUsers', 'as' => 'hipjam_showusers'))->before('auth');
Route::get('hipjam_adduser', array('uses' => 'hipjam\HipjamController@addUser', 'as' => 'hipjam_adduser'))->before('auth');
Route::post('hipjam_adduser', array('uses' => 'hipjam\HipjamController@addUserSave', 'as' => 'hipjam_adduser'))->before('auth');
Route::get('hipjam_edituser/{id}', array('uses' => 'hipjam\HipjamController@editUser', 'as' => 'hipjam_edituser'))->before('auth');
Route::post('hipjam_edituser', array('uses' => 'hipjam\HipjamController@editUserSave', 'as' => 'hipjam_edituser'))->before('auth');
// Brand Management
Route::get('hipjam_showbrands', array('uses' => 'hipjam\HipjamController@showBrands', 'as' => 'hipjam_showbrands'))->before('auth');
Route::get('hipjam_addbrand', array('uses' => 'hipjam\HipjamController@addBrand', 'as' => 'hipjam_addbrand'))->before('auth');
Route::post('hipjam_addbrand', array('uses' => 'hipjam\HipjamController@addBrandSave', 'as' => 'hipjam_addbrand'))->before('auth');
Route::get('hipjam_editbrand/{id}', array('uses' => 'hipjam\HipjamController@editBrand', 'as' => 'hipjam_editbrand'))->before('auth');
Route::post('hipjam_editbrand', array('uses' => 'hipjam\HipjamController@editBrandSave', 'as' => 'hipjam_editbrand'))->before('auth');
Route::get('hipjam_getinactivebrands/', array('uses' => 'hipjam\HipjamController@getInactiveBrands', 'as' => 'hipjam_getinactivebrands'))->before('auth');
Route::get('hipjam_activatebrand/{id}', array('uses' => 'hipjam\HipjamController@activateBrand', 'as' => 'hipjam_activatebrand'))->before('auth');
Route::post('hipjam_activatebrand', array('uses' => 'hipjam\HipjamController@activateBrandSave', 'as' => 'hipjam_activatebrand'))->before('auth');
// Store Management
Route::get('hipjam_showvenues/{json?}', array('uses' => 'hipjam\HipjamController@showVenues', 'as' => 'hipjam_showvenues'))->before('auth');
Route::get('hipjam_addvenue', array('uses' => 'hipjam\HipjamController@addVenue', 'as' => 'hipjam_addvenue'))->before('auth');
Route::post('hipjam_addvenue', array('uses' => 'hipjam\HipjamController@addVenueSave', 'as' => 'hipjam_addvenue'))->before('auth');
Route::get('hipjam_editvenue/{id}', array('uses' => 'hipjam\HipjamController@editVenue', 'as' => 'hipjam_editvenue'))->before('auth');
Route::post('hipjam_editvenue', array('uses' => 'hipjam\HipjamController@editVenueSave', 'as' => 'hipjam_editvenue'))->before('auth');

Route::get('hipjam_getinactivevenues/', array('uses' => 'hipjam\HipjamController@getInactiveVenues', 'as' => 'hipjam_getinactivevenues'))->before('auth');
Route::get('hipjam_activatevenue/{id}', array('uses' => 'hipjam\HipjamController@activateVenue', 'as' => 'hipjam_activatevenue'))->before('auth');
Route::post('hipjam_activatevenue', array('uses' => 'hipjam\HipjamController@activateVenueSave', 'as' => 'hipjam_activatevenue'))->before('auth');



Route::post('hipjam_addSensordata', array('uses' => 'hipjam\HipjamController@addSensordata', 'as' => 'hipjam_addSensordata'))->before('auth');
Route::post('hipjam_updateSensordata', array('uses' => 'hipjam\HipjamController@updateSensordata', 'as' => 'hipjam_updateSensordata'))->before('auth');
Route::post('hipjam_deleteSensordata', array('uses' => 'hipjam\HipjamController@deleteSensordata', 'as' => 'hipjam_deleteSensordata'))->before('auth');
//track server config
Route::post('hipjam_addSvrScannerdata', array('uses' => 'hipjam\HipjamController@addSvrScannerdata', 'as' => 'hipjam_addSvrScannerdata'))->before('auth');
Route::post('hipjam_updateSvrScannerdata', array('uses' => 'hipjam\HipjamController@updateSvrScannerdata', 'as' => 'hipjam_updateSvrScannerdata'))->before('auth');
Route::post('hipjam_deleteSvrScannerdata', array('uses' => 'hipjam\HipjamController@deleteSvrScannerdata', 'as' => 'hipjam_deleteSvrScannerdata'))->before('auth');

// Prospects Management
Route::get('hipjam_showprospects/{json?}', array('uses' => 'hipjam\HipjamController@showProspects', 'as' => 'hipjam_showprospects'))->before('auth');
Route::get('hipjam_addprospect', array('uses' => 'hipjam\HipjamController@addProspect', 'as' => 'hipjam_addprospect'))->before('auth');
Route::post('hipjam_addprospect', array('uses' => 'hipjam\HipjamController@addProspectSave', 'as' => 'hipjam_addprospect'))->before('auth');
Route::get('hipjam_editprospect/{id}', array('uses' => 'hipjam\HipjamController@editProspect', 'as' => 'hipjam_editprospect'))->before('auth');
Route::post('hipjam_editprospect', array('uses' => 'hipjam\HipjamController@editProspectSave', 'as' => 'hipjam_editprospect'))->before('auth');
// Venue Management
Route::get('hipjam_showvenues/{json?}', array('uses' => 'hipjam\HipjamController@showVenues', 'as' => 'hipjam_showvenues'))->before('auth');
Route::get('hipjam_addvenue', array('uses' => 'hipjam\HipjamController@addVenue', 'as' => 'hipjam_addvenue'))->before('auth');
Route::post('hipjam_addvenue', array('uses' => 'hipjam\HipjamController@addVenueSave', 'as' => 'hipjam_addvenue'))->before('auth');
Route::get('hipjam/getWindowconversion','hipjam\HipjamController@getWindowconversion');
/*Route::get('hipjam_viewvenue/{id}', array('uses' => 'hipjam\HipjamController@viewVenue', 'as' => 'hipjam_viewvenue'))->before('auth');*/
Route::get('hipjam_viewvenue/{id}', array('uses' => 'hipjam\HipjamController@viewVenue', 'as' => 'hipjam_viewvenue'))->before('auth');
Route::get('hipjam_viewvenue/{id}/{name}', array('uses' => 'hipjam\HipjamController@viewVenue', 'as' => 'hipjam_viewvenue'))->before('auth');
Route::get('hipjam_viewvenue', array('uses' => 'hipjam\HipjamController@viewVenue', 'as' => 'hipjam_viewvenue'))->before('auth');
Route::get('hipjam/chartJsondata','hipjam\HipjamController@chartJsondata');
Route::get('hipjam/heatmapJsondata','hipjam\HipjamController@heatmapJsondata');
Route::get('hipjam/zonalJsondata','hipjam\HipjamController@zonalJsondata');
Route::get('hipjam/customchartJsondata','hipjam\HipjamController@customchartJsondata');
/*Route::get('hipjam_viewchart', array('uses' => 'hipjam\HipjamController@viewVenue', 'as' => 'hipjam_viewvenue'))->before('auth');*/

Route::get('hipjam_editvenue/{id}', array('uses' => 'hipjam\HipjamController@editVenue', 'as' => 'hipjam_editvenue'))->before('auth');
Route::post('hipjam_editvenue', array('uses' => 'hipjam\HipjamController@editVenueSave', 'as' => 'hipjam_editvenue'))->before('auth');
Route::post('hipjam_editvenueserver', array('uses' => 'hipjam\HipjamController@editVenueServer', 'as' => 'hipjam_editvenueserver'))->before('auth');
Route::get('hipjam_deletevenue/{id}', array('uses' => 'hipjam\HipjamController@deleteVenue', 'as' => 'hipjam_deletevenue'))->before('auth');
Route::get('hipjam_redeploymikrotikvenue/{id}', array('uses' => 'hipjam\HipjamController@redeployMikrotikVenue', 'as' => 'hipjam_redeploymikrotikvenue'))->before('auth');
Route::get('hipjam_deployrsc/{id}', array('uses' => 'hipjam\HipjamController@deployRsc', 'as' => 'hipjam_deployrsc'))->before('auth');
Route::post('hipjam_deployrsc', array('uses' => 'hipjam\HipjamController@deployRscSave', 'as' => 'hipjam_deployrsc'))->before('auth');
Route::post('hipjam_previewsensors', array('uses' => 'hipjam\HipjamController@previewSensors', 'as' => 'hipjam_previewsensors'))->before('auth');

// sensors' monitoring

Route::get('hipjam_monitorsensors', array('uses' => 'hipjam\HipjamController@monitorSensors', 'as' => 'hipjam_monitorsensors'))->before('auth');
Route::post('hipjam_getvenuesensors/{venue_id}', array('uses' => 'hipjam\HipjamController@getVenueSensors', 'as' => 'hipjam_getvenuesensors'))->before('auth');

// HipENGAGE routes //////////////////////////////////////////////////////////////
Route::any('/hipengage_showdashboard', array('uses' => 'hipengage\HipengageController@showDashboard', 'as' => 'hipengage_showdashboard'))->before('auth');
// Campaign Management
Route::get('hipengage_showcampaigns/{json?}', array('uses' => 'hipengage\HipengageController@showCampaigns', 'as' => 'hipengage_showcampaigns'))->before('auth');
Route::get('hipengage_addcampaign', array('uses' => 'hipengage\HipengageController@addCampaign', 'as' => 'hipengage_addcampaign'))->before('auth');
Route::post('hipengage_addcampaign', array('uses' => 'hipengage\HipengageController@addCampaignSave', 'as' => 'hipengage_addcampaign'))->before('auth');
Route::get('hipengage_editcampaign/{id}', array('uses' => 'hipengage\HipengageController@editCampaign', 'as' => 'hipengage_editcampaign'))->before('auth');
Route::post('hipengage_editcampaign', array('uses' => 'hipengage\HipengageController@editCampaignSave', 'as' => 'hipengage_editcampaign'))->before('auth');
// Campaign Results
Route::get('hipengage_showresults/{json?}', array('uses' => 'hipengage\HipengageController@showResults', 'as' => 'hipengage_showresults'))->before('auth');

// HipT&A routes //////////////////////////////////////////////////////////////
Route::any('/hiptna_showdashboard/{instance?}', array('uses' => 'hiptna\HiptnaController@showDashboard', 'as' => 'hiptna_showdashboard'))->before('auth');
Route::any('/hiptna_showinstancedashboard', array('uses' => 'hiptna\HiptnaController@showInstanceDashboard', 'as' => 'hiptna_showinstancedashboard'))->before('auth');
Route::any('/hiptna_shownrinstancedashboard', array('uses' => 'hiptna\HiptnaController@showNrInstanceDashboard', 'as' => 'hiptna_shownrinstancedashboard'))->before('auth');
Route::any('/hiptna_shownonrosterdashboard', array('uses' => 'hiptna\HiptnaController@showNonrosterDashboard', 'as' => 'hiptna_shownonrosterdashboard'))->before('auth');
Route::any('/hiptna_showimdashboard', array('uses' => 'hiptna\HiptnaController@showImDashboard', 'as' => 'hiptna_showimdashboard'))->before('auth');
Route::any('/hiptna_showcedashboard', array('uses' => 'hiptna\HiptnaController@showCeDashboard', 'as' => 'hiptna_showcedashboard'))->before('auth');
Route::any('/myPageDownload', array('uses' => 'hiptna\HiptnaController@showDashboarddownload', 'as' => 'showDashboarddownload'))->before('auth');//download test
Route::any('/createPdfReport', array('uses' => 'hiptna\HiptnaController@createPdfReport', 'as' => 'createPdfReport'));//auto download test
Route::any('/autoDownloadPdf', array('uses' => 'hiptna\HiptnaController@autoDownloadPdf', 'as' => 'autoDownloadPdf'));//auto download test
Route::any('/generatePdf', array('uses' => 'hiptna\HiptnaController@generatePdf', 'as' => 'generatePdf'));//auto download test

// HipPickNPay routes //////////////////////////////////////////////////////////////
Route::any('/hippnp_showdashboard', array('uses' => 'hippnp\HipPnpController@index', 'as' => 'hippnp_showdashboard'))->before('auth');
Route::any('/hiptna_showinstancedashboard', array('uses' => 'hiptna\HiptnaController@showInstanceDashboard', 'as' => 'hippnp_showinstancedashboard'))->before('auth');
Route::any('/hiptna_shownrinstancedashboard', array('uses' => 'hiptna\HiptnaController@showNrInstanceDashboard', 'as' => 'hippnp_shownrinstancedashboard'))->before('auth');
Route::any('/hiptna_shownonrosterdashboard', array('uses' => 'hiptna\HiptnaController@showNonrosterDashboard', 'as' => 'hippnp_shownonrosterdashboard'))->before('auth');
Route::any('/hiptna_showimdashboard', array('uses' => 'hiptna\HiptnaController@showImDashboard', 'as' => 'hippnp_showimdashboard'))->before('auth');
Route::any('/hiptna_showcedashboard', array('uses' => 'hiptna\HiptnaController@showCeDashboard', 'as' => 'hippnp_showcedashboard'))->before('auth');
Route::any('/myPageDownload', array('uses' => 'hiptna\HiptnaController@showDashboarddownload', 'as' => 'showDashboarddownload'))->before('auth');//download test
Route::any('/createPdfReport', array('uses' => 'hiptna\HiptnaController@createPdfReport', 'as' => 'createPdfReport'));//auto download test
Route::any('/autoDownloadPdf', array('uses' => 'hiptna\HiptnaController@autoDownloadPdf', 'as' => 'autoDownloadPdf'));//auto download test
Route::any('/generatePdf', array('uses' => 'hiptna\HiptnaController@generatePdf', 'as' => 'generatePdf'));//auto download test

// HipBidvest routes //////////////////////////////////////////////////////////////
Route::any('/hiptna_showdashboard/{instance?}', array('uses' => 'hiptna\HiptnaController@showDashboard', 'as' => 'hipbidvest_showdashboard'))->before('auth');
Route::any('/hiptna_showinstancedashboard', array('uses' => 'hiptna\HiptnaController@showInstanceDashboard', 'as' => 'hipbidvest_showinstancedashboard'))->before('auth');
Route::any('/hiptna_shownrinstancedashboard', array('uses' => 'hiptna\HiptnaController@showNrInstanceDashboard', 'as' => 'hipbidvest_shownrinstancedashboard'))->before('auth');
Route::any('/hiptna_shownonrosterdashboard', array('uses' => 'hiptna\HiptnaController@showNonrosterDashboard', 'as' => 'hipbidvest_shownonrosterdashboard'))->before('auth');
Route::any('/hiptna_showimdashboard', array('uses' => 'hiptna\HiptnaController@showImDashboard', 'as' => 'hipbidvest_showimdashboard'))->before('auth');
Route::any('/hiptna_showcedashboard', array('uses' => 'hiptna\HiptnaController@showCeDashboard', 'as' => 'hipbidvest_showcedashboard'))->before('auth');
Route::any('/myPageDownload', array('uses' => 'hiptna\HiptnaController@showDashboarddownload', 'as' => 'showDashboarddownload'))->before('auth');//download test
Route::any('/createPdfReport', array('uses' => 'hiptna\HiptnaController@createPdfReport', 'as' => 'createPdfReport'));//auto download test
Route::any('/autoDownloadPdf', array('uses' => 'hiptna\HiptnaController@autoDownloadPdf', 'as' => 'autoDownloadPdf'));//auto download test
Route::any('/generatePdf', array('uses' => 'hiptna\HiptnaController@generatePdf', 'as' => 'generatePdf'));//auto download test

// Route::any('/myPageDownload', array('uses' => 'hiptna\HiptnaController@showDashboarddownload', 'as' => 'hiptna_showdashboard'))->before('auth');//download test
// Route::any('/createPdfReport', array('uses' => 'hiptna\HiptnaController@createPdfReport', 'as' => 'hiptna_showdashboard'))->before('auth');//auto download test
Route::any('/pdftest', array('uses' => 'hiptna\HiptnaController@pdftest', 'as' => 'pdftest'));//auto download test

Route::any('/downloadDrilldown', array('uses' => 'hiptna\HiptnaController@showDrilldownDownload', 'as' => 'hiptna_showdashboard'))->before('auth');//download test
Route::any('/downloadExceptionReport', array('uses' => 'hiptna\HiptnaController@downloadExceptionReport', 'as' => 'hiptna_showdashboard'))->before('auth');//download test
Route::any('/hiptnaStaffLookupDownload', array('uses' => 'hiptna\HiptnaController@hiptnaStaffLookupDownload', 'as' => 'hiptna_showdashboard'))->before('auth');//download test


Route::any('/hiptna_convertsvgtoimage', array('uses' => 'hiptna\HiptnaController@convertSvgToImage', 'as' => 'hiptna_showdashboard'));//download test
Route::any('/hiptna_managementconsole', array('uses' => 'hiptna\HiptnaController@showManagementConsole', 'as' => 'hiptna_managementconsole'))->before('auth');
Route::any('/hiptna_exceptionreports', array('uses' => 'hiptna\HiptnaController@showExceptionreports', 'as' => 'hiptna_exceptionreports'))->before('auth');
Route::any('/hiptna_showgraphdrilldown', array('uses' => 'hiptna\HiptnaController@showGraphdrilldown', 'as' => 'hiptna_showgraphdrilldown'))->before('auth');
Route::get('/hiptna_cleanupstaff', array('uses' => 'hiptna\HiptnaController@cleanupStaff', 'as' => 'hiptna_cleanupstaff'))->before('auth');

Route::get('hiptna/getstafftoday','hiptna\HiptnaController@getstafftoday');
Route::get('hiptna/getabsentstafftoday','hiptna\HiptnaController@getabsentstafftoday');
Route::get('hiptna/getabsentstaffyesterday','hiptna\HiptnaController@getabsentstaffyesterday');
Route::get('hiptna/getearlyleftyesterday','hiptna\HiptnaController@getearlyleftyesterday');
Route::get('hiptna/getarrivedlatetoday','hiptna\HiptnaController@getarrivedlatetoday');
Route::get('hiptna/getarrivedlateyesterday','hiptna\HiptnaController@getarrivedlateyesterday');
Route::get('hiptna/getproximityfailtoday','hiptna\HiptnaController@getproximityfailtoday');
Route::get('hiptna/getproximityfailyesterday','hiptna\HiptnaController@getproximityfailyesterday');
Route::get('hiptna/periodchartJsondata','hiptna\HiptnaController@periodchartJsondata');
Route::get('hiptna/customchartJsondata','hiptna\HiptnaController@customchartJsondata');
Route::post('hiptna/fileupload','hiptna\HiptnaController@fileupload');
Route::get('hiptna/exceptionchart','hiptna\HiptnaController@exceptionchart');
Route::any('hiptna/periodExceptionchartJsondata','hiptna\HiptnaController@periodExceptionchartJsondata');
Route::get('hiptna/memberGraph','hiptna\HiptnaController@memberGraph');
Route::get('hiptna/memberGraphToday','hiptna\HiptnaController@memberGraphToday');
Route::post('hiptna/csvdownload','hiptna\HiptnaController@csvdownload');

Route::any('/hiptna_showSettings', array('uses' => 'hiptna\HiptnaController@showSettings', 'as' => 'hiptna_showSettings'))->before('auth');
Route::any('/hiptna_updateThresholds', array('uses' => 'hiptna\HiptnaController@updateThresholds', 'as' => 'hiptna_updateThresholds'))->before('auth');

Route::any('/hiptna_addReportuser', array('uses' => 'hiptna\HiptnaController@addReportuser', 'as' => 'hiptna_addReportuser'))->before('auth');

Route::any('/hiptna_updateReportUser', array('uses' => 'hiptna\HiptnaController@updateReportUser', 'as' => 'hiptna_updateReportUser'))->before('auth');

Route::any('/hiptna_deleteReportUser', array('uses' => 'hiptna\HiptnaController@deleteReportUser', 'as' => 'hiptna_deleteReportUser'))->before('auth');

Route::any('/hiptna_showReportRecipients', array('uses' => 'hiptna\HiptnaController@showReportRecipients', 'as' => 'hiptna_showReportRecipients'))->before('auth');

Route::any('/hiptna_showStafflookup', array('uses' => 'hiptna\HiptnaController@showStafflookup', 'as' => 'hiptna_showStafflookup'))->before('auth');
Route::any('hiptna_gettnastaff', array('uses' => 'hiptna\HiptnaController@gettnastaff', 'as' => 'hiptna_gettnastaff'));
Route::any('/hiptna_sendPushNotifications', array('uses' => 'hiptna\HiptnaController@sendPushNotifications', 'as' => 'hiptna_sendPushNotifications'))->before('auth');
Route::any('/hiptna_sendGroupNotifications', array('uses' => 'hiptna\HiptnaController@sendGroupNotifications', 'as' => 'hiptna_sendGroupNotifications'))->before('auth');
Route::any('/hiptna_showInstantmessaging', array('uses' => 'hiptna\HiptnaController@showInstantmessaging', 'as' => 'hiptna_showInstantmessaging'))->before('auth');
Route::any('/hiptna_showBeaconbatterymonitor', array('uses' => 'hiptna\HiptnaController@showBeaconbatterymonitor', 'as' => 'hiptna_showBeaconbatterymonitor'))->before('auth');

Route::get('hiptna/periodchartJsondata','hiptna\HiptnaController@periodchartJsondata');
Route::get('hiptna/customchartJsondata','hiptna\HiptnaController@customchartJsondata');
Route::get('hiptna/latenessthreshold','hiptna\HiptnaController@latenessthreshold');
Route::get('hiptna/proximitythreshold','hiptna\HiptnaController@proximitythreshold');
Route::get('hiptna/getstaffthismonth','hiptna\HiptnaController@getstaffthismonth');
Route::get('hiptna/getstaffexpected','hiptna\HiptnaController@getstaffexpected');


Route::any('/sendemailreports', array('uses' => 'hiptna\HiptnaEmailReportsController@sendemails', 'as' => 'sendemailreports'));


// Notification Management
Route::get('hipengage_shownotifications/{json?}', array('uses' => 'hipengage\HipengageController@showNotifications', 'as' => 'hipengage_shownotifications'))->before('auth');

Route::get('hipengage_selectaddnotification/{type}', array('uses' => 'hipengage\HipengageController@selectAddNotification', 'as' => 'hipengage_selectaddnotification'))->before('auth');

Route::get('hipengage_addpush', array('uses' => 'hipengage\HipengageController@addPush', 'as' => 'hipengage_addpush'))->before('auth');
Route::post('hipengage_addpush', array('uses' => 'hipengage\HipengageController@addPushSave', 'as' => 'hipengage_addpush'))->before('auth');
Route::get('hipengage_editpush/{id}', array('uses' => 'hipengage\HipengageController@editPush', 'as' => 'hipengage_editpush'))->before('auth');
Route::post('hipengage_editpush', array('uses' => 'hipengage\HipengageController@editPushSave', 'as' => 'hipengage_editpush'))->before('auth');
Route::get('hipengage_deletepush/{id}', array('uses' => 'hipengage\HipengageController@deletePush', 'as' => 'hipengage_deletepush'))->before('auth');

Route::get('hipengage_addsms', array('uses' => 'hipengage\HipengageController@addSms', 'as' => 'hipengage_addsms'))->before('auth');
Route::post('hipengage_addsms', array('uses' => 'hipengage\HipengageController@addSmsSave', 'as' => 'hipengage_addsms'))->before('auth');
Route::get('hipengage_editsms/{id}', array('uses' => 'hipengage\HipengageController@editSms', 'as' => 'hipengage_editsms'))->before('auth');
Route::post('hipengage_editsms', array('uses' => 'hipengage\HipengageController@editSmsSave', 'as' => 'hipengage_editsms'))->before('auth');
Route::get('hipengage_deletesms/{id}', array('uses' => 'hipengage\HipengageController@deleteSms', 'as' => 'hipengage_deletesms'))->before('auth');

Route::get('hipengage_addemail', array('uses' => 'hipengage\HipengageController@addEmail', 'as' => 'hipengage_addemail'))->before('auth');
Route::post('hipengage_addemail', array('uses' => 'hipengage\HipengageController@addEmailSave', 'as' => 'hipengage_addemail'))->before('auth');
Route::get('hipengage_editemail/{id}', array('uses' => 'hipengage\HipengageController@editEmail', 'as' => 'hipengage_editemail'))->before('auth');
Route::post('hipengage_editemail', array('uses' => 'hipengage\HipengageController@editEmailSave', 'as' => 'hipengage_editemail'))->before('auth');
Route::get('hipengage_deleteemail/{id}', array('uses' => 'hipengage\HipengageController@deleteEmail', 'as' => 'hipengage_deleteemail'))->before('auth');

Route::get('hipengage_addmgr', array('uses' => 'hipengage\HipengageController@addMgr', 'as' => 'hipengage_addmgr'))->before('auth');
Route::post('hipengage_addmgr', array('uses' => 'hipengage\HipengageController@addMgrSave', 'as' => 'hipengage_addmgr'))->before('auth');
Route::get('hipengage_editmgr/{id}', array('uses' => 'hipengage\HipengageController@editMgr', 'as' => 'hipengage_editmgr'))->before('auth');
Route::post('hipengage_editmgr', array('uses' => 'hipengage\HipengageController@editMgrSave', 'as' => 'hipengage_editmgr'))->before('auth');
Route::get('hipengage_deletemgr/{id}', array('uses' => 'hipengage\HipengageController@deleteMgr', 'as' => 'hipengage_deletemgr'))->before('auth');

Route::get('hipengage_addapi', array('uses' => 'hipengage\HipengageController@addApi', 'as' => 'hipengage_addapi'))->before('auth');
Route::post('hipengage_addapi', array('uses' => 'hipengage\HipengageController@addApiSave', 'as' => 'hipengage_addapi'))->before('auth');
Route::get('hipengage_editapi/{id}', array('uses' => 'hipengage\HipengageController@editApi', 'as' => 'hipengage_editapi'))->before('auth');
Route::post('hipengage_editapi', array('uses' => 'hipengage\HipengageController@editApiSave', 'as' => 'hipengage_editapi'))->before('auth');
Route::get('hipengage_deleteapi/{id}', array('uses' => 'hipengage\HipengageController@deleteApi', 'as' => 'hipengage_deleteapi'))->before('auth');


// Event Management
Route::get('hipengage_showevents/{json?}', array('uses' => 'hipengage\HipengageController@showEvents', 'as' => 'hipengage_showevents'))->before('auth');
Route::get('hipengage_addevent', array('uses' => 'hipengage\HipengageController@addEvent', 'as' => 'hipengage_addevent'))->before('auth');
Route::post('hipengage_addevent', array('uses' => 'hipengage\HipengageController@addEventSave', 'as' => 'hipengage_addevent'))->before('auth');
Route::get('hipengage_editevent/{id}', array('uses' => 'hipengage\HipengageController@editEvent', 'as' => 'hipengage_editevent'))->before('auth');
Route::post('hipengage_editevent', array('uses' => 'hipengage\HipengageController@editEventSave', 'as' => 'hipengage_editevent'))->before('auth');
Route::get('hipengage_deleteevent/{id}', array('uses' => 'hipengage\HipengageController@deleteEvent', 'as' => 'hipengage_deleteevent'))->before('auth');


// Route::post('hipengage_addapi', array('uses' => 'hipengage\HipengageController@addApiSave', 'as' => 'hipengage_addapi'))->before('auth');
// Route::get('hipengage_editapi/{id}', array('uses' => 'hipengage\HipengageController@editApi', 'as' => 'hipengage_editapi'))->before('auth');
// Route::post('hipengage_editapi', array('uses' => 'hipengage\HipengageController@editApiSave', 'as' => 'hipengage_editapi'))->before('auth');

// Route::post('hipengage_addsms', array('uses' => 'hipengage\HipengageController@addSmsSave', 'as' => 'hipengage_addsms'))->before('auth');
// Route::get('hipengage_editsms/{id}', array('uses' => 'hipengage\HipengageController@editSms', 'as' => 'hipengage_editsms'))->before('auth');
// Route::post('hipengage_editsms', array('uses' => 'hipengage\HipengageController@editSmsSave', 'as' => 'hipengage_editsms'))->before('auth');

// Route::post('hipengage_addemail', array('uses' => 'hipengage\HipengageController@addEmailSave', 'as' => 'hipengage_addemail'))->before('auth');
// Route::get('hipengage_editemail/{id}', array('uses' => 'hipengage\HipengageController@editEmail', 'as' => 'hipengage_editemail'))->before('auth');
// Route::post('hipengage_editemail', array('uses' => 'hipengage\HipengageController@editEmailSave', 'as' => 'hipengage_editemail'))->before('auth');

// Venue Management
Route::get('hipengage_showvenues/{json?}', array('uses' => 'hipengage\HipengageController@showVenues', 'as' => 'hipengage_showvenues'))->before('auth');
Route::get('hipengage_showbeacons/{json?}', array('uses' => 'hipengage\HipengageController@showBeacons', 'as' => 'hipengage_showbeacons'))->before('auth');
Route::get('hipengage_showsensors/{json?}', array('uses' => 'hipengage\HipengageController@showSensors', 'as' => 'hipengage_showsensors'))->before('auth');
Route::get('hipengage_addvenue', array('uses' => 'hipengage\HipengageController@addVenue', 'as' => 'hipengage_addvenue'))->before('auth');
Route::post('hipengage_addvenue', array('uses' => 'hipengage\HipengageController@addVenueSave', 'as' => 'hipengage_addvenue'))->before('auth');
Route::get('hipengage_editvenuepositions/{id}', array('uses' => 'hipengage\HipengageController@editVenuePositions', 'as' => 'hipengage_editvenuepositions'))->before('auth');
Route::post('hipengage_editvenuepositions', array('uses' => 'hipengage\HipengageController@editVenuePositionsSave', 'as' => 'hipengage_editvenuepositions'))->before('auth');
Route::get('hipengage_deletevenue/{id}', array('uses' => 'hipengage\HipengageController@deleteVenue', 'as' => 'hipengage_deletevenue'))->before('auth');

Route::get('hipengage_venuemonitoring/{json?}', array('uses' => 'hipengage\HipengageController@venueMonitoring', 'as' => 'hipengage_venuemonitoring'))->before('auth');


// Bulk Email Tool
Route::get('hipengage_showemailtool/{json?}', array('uses' => 'hipengage\HipengageController@showEmailtool', 'as' => 'hipengage_showemailtool'))->before('auth');
// Bulk Sms Tool
Route::get('hipengage_showsmstool/{json?}', array('uses' => 'hipengage\HipengageController@showSmstool', 'as' => 'hipengage_showsmstool'))->before('auth');
Route::get('hipengage_engagebrands/{json?}', array('uses' => 'hipengage\HipengageController@showEngagebrands', 'as' => 'hipengage_engagebrands'))->before('auth');



// HipREPORTS routes //////////////////////////////////////////////////////////////
Route::any('hipreports_showdashboard', array('uses' => 'hipreports\HipreportsController@showDashboard', 'as' => 'hipreports_showdashboard'))->before('auth');
Route::get('hipreports_hipwifi/{json?}', array('uses' => 'hipreports\HipreportsController@hipWifi', 'as' => 'hipreports_hipwifi'))->before('auth');
Route::get('hipreports_hipwifi_statistcsjson/{json?}', array('uses' => 'hipreports\HipreportsController@hipreports_hipwifi_statistcsjson', 'as' => 'hipreports_hipwifi_statistcsjson'))->before('auth');
Route::get('hipreports_hipwifi_venuedatajson/{json?}', array('uses' => 'hipreports\HipreportsController@hipreports_hipwifi_venuedatajson', 'as' => 'hipreports_hipwifi_venuedatajson'))->before('auth');
Route::get('hipreports_hipwifi_venuedatajsonsingle/{json?}', array('uses' => 'hipreports\HipreportsController@hipreports_hipwifi_venuedatajsonsingle', 'as' => 'hipreports_hipwifi_venuedatajsonsingle'))->before('auth');
Route::get('hipreports_hipwifi_branddatajson/{json?}', array('uses' => 'hipreports\HipreportsController@hipreports_hipwifi_branddatajson', 'as' => 'hipreports_hipwifi_branddatajson'))->before('auth');
Route::get('hipreports_hipwifi_branddatajsonsingle/{json?}', array('uses' => 'hipreports\HipreportsController@hipreports_hipwifi_branddatajsonsingle', 'as' => 'hipreports_hipwifi_branddatajsonsingle'))->before('auth');

Route::get('hipreports_hipwifi_downloaduserprofiledata', array('uses' => 'hipreports\HipreportsController@hipreports_hipwifi_downloaduserprofiledata', 'as' => 'hipreports_hipwifi_downloaduserprofiledata'))->before('auth');
Route::get('hipreports_hipwifi_downloadlistcustomerusage', array('uses' => 'hipreports\HipreportsController@hipreports_hipwifi_downloadlistcustomerusage', 'as' => 'hipreports_hipwifi_downloadlistcustomerusage'))->before('auth');

Route::get('hipreports_hipwifi_logdowntime', array('uses' => 'hipreports\HipreportsController@hipreports_hipwifi_logdowntime', 'as' => 'hipreports_hipwifi_logdowntime'));
Route::get('hipreports_hipwifi_generatedowntimehistory', array('uses' => 'hipreports\HipreportsController@hipreports_hipwifi_generatedowntimehistory', 'as' => 'hipreports_hipwifi_generatedowntimehistory'));

Route::any('/hipwifiBrandPdfDownload', array('uses' => 'hipreports\HipreportsController@hipwifi_Brand_Pdf_Download', 'as' => 'hipreports_showdashboard'));
Route::any('/hipwifiVenuePdfDownload', array('uses' => 'hipreports\HipreportsController@hipwifi_Venue_Pdf_Download', 'as' => 'hipreports_showdashboard'));
Route::any('/hipwifi_convertsvgtoimage', array('uses' => 'hipreports\HipreportsController@convertSvgToImage', 'as' => 'hipreports_showdashboard'))->before('auth');




// HipJam routes //////////////////////////////////////////////////////////////
Route::any('hipjam_testapi', array('uses' => 'hipjam\HipjamController@testApi', 'as' => 'hipjam_testapi'));

// JSON Routes
####################################
Route::get('lib_getrmcrossrefcsv/{quickie_id}', array('uses' => 'lib\LibController@lib_getrmcrossrefcsv', 'as' => 'lib_getrmcrossrefcsv'));
Route::get('lib_getrmcrossrefcsv_519_520/{quickie_id}', array('uses' => 'lib\LibController@lib_getrmcrossrefcsv_519_520', 'as' => 'lib_getrmcrossrefcsv_519_520'));
Route::get('lib_getrmcrossrefcsv_581_582/{quickie_id}', array('uses' => 'lib\LibController@lib_getrmcrossrefcsv_581_582', 'as' => 'lib_getrmcrossrefcsv_581_582'));
Route::get('lib_getrmcrossrefcsv_hp', array('uses' => 'lib\LibController@lib_getrmcrossrefcsv_hp', 'as' => 'lib_getrmcrossrefcsv_hp'));

Route::get('lib_getprovinces/{countrie_id}', array('uses' => 'lib\LibController@getProvinces', 'as' => 'lib_getprovinces'));
Route::get('lib_getcities/{province_id}', array('uses' => 'lib\LibController@getCities', 'as' => 'lib_getcities'));
Route::any('lib_getvenues/', array('uses' => 'lib\LibController@getVenues', 'as' => 'lib_getvenues'));
Route::any('lib_getservers/{brand_id}', array('uses' => 'lib\LibController@getServers', 'as' => 'lib_getservers'));
Route::get('lib_buildlocationcode', array('uses' => 'lib\LibController@buildLocationCode', 'as' => 'lib_buildlocationcode'));
Route::get('lib_buildMatchLocationCode', array('uses' => 'lib\LibController@buildMatchLocationCode', 'as' => 'lib_buildMatchLocationCode'));
Route::get('lib_isduplicate', array('uses' => 'lib\LibController@isDuplicate', 'as' => 'lib_isduplicate'));
Route::get('lib_issitenameduplicate', array('uses' => 'lib\LibController@isSitenameDuplicate', 'as' => 'lib_issitenameduplicate'));
Route::get('lib_getserversfordatabase', array('uses' => 'lib\LibController@getServersForDatabase', 'as' => 'lib_getserversfordatabase'));
Route::get('lib_getbrandsfordatabase', array('uses' => 'lib\LibController@getBrandsForDatabase', 'as' => 'lib_getbrandsfordatabase'));
Route::get('lib_filterdvenues', array('uses' => 'lib\LibController@filterVenues', 'as' => 'lib_filterdvenues'));
Route::get('lib_filterservers', array('uses' => 'lib\LibController@filterServers', 'as' => 'lib_filterservers'));
Route::get('lib_getbrands/{countrie_id}', array('uses' => 'lib\LibController@getBrands', 'as' => 'lib_getbrands'));
Route::any('lib_savedtmedia', array('uses' => 'lib\LibController@saveDtMedia', 'as' => 'lib_savedtmedia'));
Route::any('lib_savembmedia', array('uses' => 'lib\LibController@saveMbMedia', 'as' => 'lib_savembmedia'));
Route::any('lib_savefpmedia', array('uses' => 'lib\LibController@saveFpMedia', 'as' => 'lib_savefpmedia'));
Route::any('lib_savepushmedia', array('uses' => 'lib\LibController@savePushMedia', 'as' => 'lib_savepushmedia'));
Route::any('lib_savelookupmedia', array('uses' => 'lib\LibController@savelookupMedia', 'as' => 'lib_savelookupmedia'));
Route::any('lib_getbranddata', array('uses' => 'lib\LibController@getBrandData', 'as' => 'lib_getbranddata'));
Route::any('lib_getserverurl', array('uses' => 'lib\LibController@getServerUrl', 'as' => 'lib_getserverurl'));
//Engage
Route::any('lib_getengageapplications', array('uses' => 'lib\LibController@getEngageApplications', 'as' => 'lib_getengageapplications'));
Route::any('lib_getengagetriggers/{application_code}', array('uses' => 'lib\LibController@getEngageTriggers', 'as' => 'lib_getengagetriggers'));
Route::any('lib_getengagemeasures/{measure_code}', array('uses' => 'lib\LibController@getEngageMeasures', 'as' => 'lib_getengagemeasures'));
Route::any('lib_getengageoperators/{operator_code}', array('uses' => 'lib\LibController@getEngageOperators', 'as' => 'lib_getengageoperators'));

Route::any('lib_getrmquickies/{brand_id}', array('uses' => 'lib\LibController@getRmQuickies', 'as' => 'lib_getrmquickies'));
Route::any('lib_getrmquickieanswers/{quickie_id}', array('uses' => 'lib\LibController@getRmQuickieAnswers', 'as' => 'lib_getrmquickieanswers'));

Route::any('lib_getengagenotifications/{type}', array('uses' => 'lib\LibController@getEngageNotifications', 'as' => 'lib_getengagenotifications'));

Route::any('lib_getengagepushnotifications', array('uses' => 'lib\LibController@getEngagePushNotifications', 'as' => 'lib_getengagepushnotifications'));
Route::any('lib_getengagepushnotification/{id}', array('uses' => 'lib\LibController@getEngagePushNotification', 'as' => 'lib_getengagepushnotification'));

Route::any('lib_getengageapinotifications', array('uses' => 'lib\LibController@getEngageApiNotifications', 'as' => 'lib_getengageapinotifications'));
Route::any('lib_getengageapinotification/{id}', array('uses' => 'lib\LibController@getEngageApiNotification', 'as' => 'lib_getengageapinotification'));

Route::any('lib_getengagemgrnotifications', array('uses' => 'lib\LibController@getEngageApiNotifications', 'as' => 'lib_getengagemgrnotifications'));
Route::any('lib_getengagemgrnotification/{id}', array('uses' => 'lib\LibController@getEngageApiNotification', 'as' => 'lib_getengagemgrnotification'));

Route::any('lib_getengagesmsnotifications', array('uses' => 'lib\LibController@getEngageSmsNotifications', 'as' => 'lib_getengagesmsnotifications'));
Route::any('lib_getengagesmsnotification/{id}', array('uses' => 'lib\LibController@getEngageSmsNotification', 'as' => 'lib_getengagesmsnotification'));

Route::any('lib_getengagemgrnotifications', array('uses' => 'lib\LibController@getEngageMgrNotifications', 'as' => 'lib_getengagemgrnotifications'));
Route::any('lib_getengagemgrnotification/{id}', array('uses' => 'lib\LibController@getEngageMgrNotification', 'as' => 'lib_getengagemgrnotification'));

Route::any('lib_getengageemailnotifications', array('uses' => 'lib\LibController@getEngageEmailNotifications', 'as' => 'lib_getengageemailnotifications'));
Route::any('lib_getengageemailnotification/{id}', array('uses' => 'lib\LibController@getEngageEmailNotification', 'as' => 'lib_getengageemailnotification'));

Route::any('lib_getvenuepositions/{brandcode}', array('uses' => 'lib\LibController@getVenuePositions', 'as' => 'lib_getvenuepositions'));
Route::any('lib_getbeacons/{brandcode}', array('uses' => 'lib\LibController@getBeacons', 'as' => 'lib_getbeacons'));
Route::any('lib_savevenueposition', array('uses' => 'lib\LibController@saveVenuePosition', 'as' => 'lib_savevenueposition'));
Route::any('lib_updatevenueposition', array('uses' => 'lib\LibController@updateVenuePosition', 'as' => 'lib_updatevenueposition'));
Route::any('lib_deletevenueposition', array('uses' => 'lib\LibController@deleteVenuePosition', 'as' => 'lib_deletevenueposition'));
Route::any('lib_getpositionnames/{brandcode}', array('uses' => 'lib\LibController@getPositionNames', 'as' => 'lib_getpositionnames'));

//Other routes
Route::any('/dashboard', array('as' => 'dashboard', 'uses' => 'DashboardController@showDashboard'))->before('auth');
Route::any('/special_synchvenues', array('uses' => 'special\SpecialController@synchVenues', 'as' => 'special_synchvenues'))->before('auth');
Route::any('/special_synchvenuesexecute', array('uses' => 'special\SpecialController@synchVenuesExecute', 'as' => 'special_synchvenuesexecute'))->before('auth');
Route::any('/special_importbrands', array('uses' => 'special\SpecialController@importBrands', 'as' => 'special_importbrands'))->before('auth');
Route::any('/special_importbrandsexecute', array('uses' => 'special\SpecialController@importBrandsExecute', 'as' => 'special_importbrandsexecute'))->before('auth');
Route::any('/special_generatereportsdata', array('uses' => 'special\SpecialController@generateReportsData', 'as' => 'special_generatereportsdata'));
Route::any('/special_dropreportstmptables', array('uses' => 'special\SpecialController@dropReportsTmpTables', 'as' => 'special_dropreportstmptables'));

Route::get('lib_filterbeacons', array('uses' => 'lib\LibController@filterBeacons', 'as' => 'lib_filterbeacons'));

Route::get('profile', array('as' => 'profile', function () {
    $users = User::all();
    return View::make('profile')->with('users', $users);
}))->before('auth');

Route::get('users', function()
{
	$users = User::all();
	// $users = DB::select('select * from users', array(1));
	error_log("IN ROUTES");

	return View::make('users')->with('users', $users);
});


Route::any('hipengage_sendtestapinotification', array('uses' => 'hipengage\HipengageController@hipengage_sendtestapinotification', 'as' => 'hipengage_sendtestapinotification'))->before('auth');
Route::any('hipengage_testapi', array('uses' => 'hipengage\HipengageController@hipengage_testapi', 'as' => 'hipengage_testapi'));

Route::any('general_test', array('uses' => 'TestController@general_test', 'as' => 'general_test'));


Route::get('test/{id}', array('uses' => 'hipwifi\HipwifiController@editvenue'));



Route::get('dashboard_details1', array('uses' => 'lib\LibController@dashboard_details1')); //added by Dare for ajax calls that displays the dashboard data

Route::get('dashboard_details2', array('uses' => 'lib\LibController@dashboard_details2')); //added by Dare for ajax calls that displays the dashboard data.

Route::get('deladminssid/{id}/{adminssid}', array( 'as' => 'deladminssid', 'uses' => 'hipwifi\HipwifiController@deleteadminssid') ); //added by Dare, used for deleting an already added adminssid.

Route::get('delmacbypass/{id}/{macaddress}', array( 'as' => 'deladminssid', 'uses' => 'hipwifi\HipwifiController@deletemacbypass') );

