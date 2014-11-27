<?php

class NoteController extends \BaseController {

	/**
	 * Display a listing of the resource.
	 * GET /note
	 *
	 * @return Response
	 */
	public function index()
	{
		//
		if (Auth::check()){
			if ( !Input::has('searchterm') && empty(Input::get('searchterm')) ){
				$notes = Note::where('author_id', Auth::user()->id)
					->orderBy('created_at', 'DESC')
					->paginate(15);	
			} else {
				$searchterm = Input::get('searchterm');
				Session::flash('message', 'Showing search results for ' . $searchterm);
				$notes = Note::where('title', 'LIKE', '%'.$searchterm.'%')
					->where('content', 'LIKE', '%'.$searchterm.'%', 'OR')
					->orderBy('created_at', 'DESC')
					->paginate(15);	
			}

			if ( Input::has('tag') && !empty(Input::get('tag')) ){
				$tag = Tag::where('name', Input::get('tag'))->first();
				$notes = $tag->notes->sortByDesc('created_at');
			}

			if ( $notes->count() === 0 ){
				$notes = array();
			}
			
		} else {
			$notes = array();
		}
		
		return View::make('notes.index')->withNotes($notes);
	}

	/**
	 * Show the form for creating a new resource.
	 * GET /note/create
	 *
	 * @return Response
	 */
	public function create()
	{
		//
		return View::make('notes.create');
	}

	/**
	 * Store a newly created resource in storage.
	 * POST /note
	 *
	 * @return Response
	 */
	public function store()
	{
		//
		if ( Auth::check() ) {
			$input = Input::all();

			$validation = Validator::make($input, Note::$rules);
			if ( $validation->passes() ){
				// create new note
				$note = new Note;
				$note->title = $input['title'];
				$note->content = $input['content'];
				$note->author_id = Auth::user()->id;
				$note->save();

				if (Input::has('tags') && !empty(Input::get('tags'))){
					$note->tags()->sync(Input::get('tags'));
				}

				Session::flash('message', 'Saved note: ' . $note->title);
				return Redirect::route('homepage');
			} else {
				return Redirect::back()
					->withInput()
					->withErrors($validation)
					->with('message', 'There were validation errors');
			}

		} else {
			return Redirect::route('homepage');
		}
		

	}

	/**
	 * Display the specified resource.
	 * GET /note/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		//
	}

	/**
	 * Show the form for editing the specified resource.
	 * GET /note/{id}/edit
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		//
		$note = Note::find($id);
		return View::make('notes.edit', compact('note'));
	}

	/**
	 * Update the specified resource in storage.
	 * PUT /note/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{
		//

		$note = Note::find($id);		
		$inputs = Input::all();

		if ($note->update($inputs)){
			if (Input::has('tags') && !empty(Input::get('tags'))){
				$note->tags()->sync(Input::get('tags'));
			} else {
				$note->tags()->sync([]);
			}
			Session::flash('message', 'Updated note: '. $note->title);	
		} else {
			Session::flash('message', 'Could not note: '. $note->title);
		}
		
		return Redirect::back();
	}

	/**
	 * Remove the specified resource from storage.
	 * DELETE /note/{id}
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		//
		Note::destroy($id);
		Session::flash('message', 'Removed note #' . $id);
		return Redirect::back();
	}

}