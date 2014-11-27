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

	public function about()
	{
		return View::make('about');
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
	        
	        $user = User::where('email', $result['email'])->first();
	       
	        if ( !$user ) {
	        	$user = new User;	        	
	        	$user->email = $result['email'];
	        	$user->name = $result['name'];
	        	$user->given_name = $result['given_name'];
	        	$user->family_name = $result['family_name'];
	        	$user->picture = $result['picture'];
	        	$user->save();
	        }

	        Auth::login($user);
	        

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


	public function ImportTags()
	{
		$tomboy_path =  app_path() . '/storage/private/tomboy-notes/';
				
			$files = FindFiles($tomboy_path, "note");
			$taglist = array();
			foreach($files as $key => $file){
				$raw_xml = file_get_contents($key);
				$parsed_xml = Parser::xml($raw_xml);
				
				if (isset($parsed_xml['tags'])){			
					foreach($parsed_xml['tags'] as $tags){
						if ( is_array($tags)){
							foreach($tags as $tag){
								$tag = str_replace('system:', '', $tag);
								$tag = str_replace('notebook:', '', $tag);
								$taglist = array_merge($taglist, array($tag) );
							}
						} else {
							$tags = str_replace('system:', '', $tags);
							$tags = str_replace('notebook:', '', $tags);
							$taglist = array_merge($taglist, array($tags) );
						}					
					}			
				}			
			}

			$taglist = array_unique($taglist);
			foreach($taglist as $tag){
				Tag::create(['name' => $tag]);
				echo "$tag <br />";
			}
			return '<h3>Importing tags complete</h3>';
	}

	public function ImportNotes()
	{
		$tomboy_path =  app_path() . '/storage/private/tomboy-notes/';
				
			$files = FindFiles($tomboy_path, "note");
			
			foreach($files as $key => $file){
				$raw_xml = file_get_contents($key);
				$parsed_xml = Parser::xml($raw_xml);
				echo "Importing $key <br/>";
				$tomboy_id = str_replace('.note', '', basename($key));

				//Skip if already imported
				if ( Note::where('tomboy_id', $tomboy_id)->count() > 0){
					continue;
				}
				$taglist = array();
				if (isset($parsed_xml['tags'])){			
					foreach($parsed_xml['tags'] as $tags){
						if ( is_array($tags)){
							foreach($tags as $tag){
								$tag = str_replace('system:', '', $tag);
								$tag = str_replace('notebook:', '', $tag);
								$taglist = array_merge($taglist, array($tag) );
							}
						} else {
							$tags = str_replace('system:', '', $tags);
							$tags = str_replace('notebook:', '', $tags);
							$taglist = array_merge($taglist, array($tags) );
						}					
					}			
				}	
				if ( isset($parsed_xml["title"]) && !empty($parsed_xml["title"])){
					$note = new Note;
					$note->title = (isset($parsed_xml["title"])?$parsed_xml["title"]:'');
					if ( isset($parsed_xml["text"]) ){
						if (is_array($parsed_xml["text"]["note-content"])){
							#var_dump($parsed_xml);
							$note->content = '';
						} else {
							$note->content = $parsed_xml["text"]["note-content"];	
						}
					}
					
					$note->author_id = 1;
					$note->tomboy_id = $tomboy_id;
					
					if (isset($parsed_xml['last-change-date'])){
						$note->updated_at = Carbon\Carbon::parse($parsed_xml['last-change-date']);
					}
					
					if (isset($parsed_xml['create-date'])){
						$note->created_at = Carbon\Carbon::parse($parsed_xml['create-date']);
					}
					

					$note->save();
					if (count($taglist)>0){
						foreach( $taglist as $tag ){
							$t = Tag::where('name', $tag)->first();
							$note->tags()->attach($t);
						}
					}
				}
			}
			
			return '<h3>Importing notes complete</h3>';
	}

}
