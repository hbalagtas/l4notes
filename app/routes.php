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

Route::get('login', ['as'=>'login', 'uses' => 'HomeController@loginWithGoogle']);

Route::get('/', ['as'=>'homepage', 'uses' => 'NoteController@index']);

Route::resource('note', 'NoteController');

Route::get('/import-tags', function()
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

		/*$note = new Note;
		$note->title = $parsed_xml["title"];
		$note->content = $parsed_xml["text"]["note-content"];
		$note->updated_at = Carbon\Carbon::parse($parsed_xml['last-change-date']);
		$note->created_at = Carbon\Carbon::parse($parsed_xml['create-date']);*/
		
	}

	$taglist = array_unique($taglist);
	foreach($taglist as $tag){
		Tag::create(['name' => $tag]);
	}
	return 'done';
});

Route::get('/import-notes', function()
{
	$tomboy_path =  app_path() . '/storage/private/tomboy-notes/';
		
	$files = FindFiles($tomboy_path, "note");
	
	foreach($files as $key => $file){
		$raw_xml = file_get_contents($key);
		$parsed_xml = Parser::xml($raw_xml);
		echo "$key <br/>";
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
	
	return 'done';
});
