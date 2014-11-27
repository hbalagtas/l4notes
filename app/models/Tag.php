<?php

class Tag extends \Eloquent {
	protected $fillable = ['name'];
	public $timestamps = false;

	protected $rules = ['name' => 'required|unique:tags'];

	public function notes()
	{
		return $this->belongsToMany('Note');
	}
}