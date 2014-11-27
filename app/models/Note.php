<?php

class Note extends \Eloquent {
	protected $fillable = ['title', 'content', 'tomboy_id'];

	public static $rules = [
				'title' => 'required',
			];

	public function tags()
	{
		return $this->belongsToMany('Tag');
	}

	public function author()
	{
		return $this->hasOne('User', 'id', 'author_id');
	}
}