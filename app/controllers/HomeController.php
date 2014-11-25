<?php

class HomeController extends BaseController {

	/*
	|--------------------------------------------------------------------------
	| Default Home Controller
	|--------------------------------------------------------------------------
	|
	| You may wish to use controllers instead of, or in addition to, Closure
	| based routes. That's great! Here is an example controller method to
	| get you started. To route to this controller, just add the route:
	|
	|	Route::get('/', 'HomeController@showWelcome');
	|
	*/

	public function showWelcome()
	{
		return View::make('hello');
	}


	public function loginWithGoogle() {

	    // get data from input
	    $code = Input::get( 'code' );

	    // get google service
	    $googleService = OAuth::consumer( 'Google' );

	    // check if code is valid

	    // if code is provided get user data and sign in
	    if ( !empty( $code ) ) {

	        // This was a callback request from google, get the token
	        $token = $googleService->requestAccessToken( $code );

	        // Send a request with it
	        $result = json_decode( $googleService->request( 'https://www.googleapis.com/oauth2/v1/userinfo' ), true );

	        $message = 'Your unique Google user id is: ' . $result['id'] . ' and your name is ' . $result['name'];
	        #echo $message. "<br/>";
	        $user = User::find($result['id'];
	        if ( !$user ) {
	        	$user = new User;
	        	$user->id = $result['id'];
	        	$user->email = $result['email'];
	        	$user->name = $result['name'];
	        	$user->given_name = $result['given_name'];
	        	$user->family_name = $result['family_name'];
	        	$user->picture = $result['picture'];
	        	$user->save();
	        }

	        Auth::login($user);*/
	        

	        //Var_dump
	        //display whole array().
	        #dd($result);
	        return Redirect::route('homepage');

	    }
	    // if not ask for permission first
	    else {
	    	if ( Input::has('error')){
	    		$error = Input::get('error');
	    		Session::flash('message', $error);
	    		return Redirect::to('/');
	    	}

	        // get googleService authorization
	        $url = $googleService->getAuthorizationUri();

	        // return to google login url
	        return Redirect::to( (string)$url );
	    }
	}

}
