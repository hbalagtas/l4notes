<?php

class Note extends \Eloquent {
	protected $fillable = ['title', 'content'];


	public function tags()
	{
		return $this->belongsToMany('tag');
	}
}