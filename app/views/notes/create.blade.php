@extends('layouts.master')

@section('page_title', 'Create Note')

@section('content')
<div class="container">
	<div class="jumbotron">
		<div class="container">
						
			{{ Form::open(['route' => 'note.store', 'role'=>'form'])}}
				<legend>Create Note</legend>
			
				<div class="form-group">
					<label for="title">Title</label>
					<input type="text" class="form-control" id="title" name="title" placeholder="Lorem ipsum...">
				</div>

				<div class="form-group">
					<label for="content">Content</label>
					<textarea class="form-control" id="content" name="content" rows="10" placeholder="Type your note here"></textarea>
				</div>

				<div class="checkbox">
					@foreach(Tag::all()->sortBy('name') as $tag)
					<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
						<label>
							<input type="checkbox" name="tags[]" value="{{$tag->id}}">
							{{$tag->name}}
						</label>	
					</div>					
					@endforeach
				</div>
				
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 hgap">
					{{ Form::button('Save', ['type'=>'submit', 'class' => 'btn btn-primary btn-sm'])}}
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@stop