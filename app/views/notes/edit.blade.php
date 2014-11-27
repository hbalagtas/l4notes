@extends('layouts.master')

@section('page_title', 'Edit ')

@section('content')
<div class="container">
	<div class="jumbotron">
		<div class="container">						
			{{Form::model($note,['method'=>'patch','route'=>['note.update', $note->id]])}}
				<legend>Edit Note</legend>
			
				<div class="form-group">
					<label for="title">Title</label>					
					{{Form::text('title',null,['class'=>'form-control'])}}
				</div>

				<div class="form-group">
					<label for="content">Content</label>
					
					{{Form::textarea('content',null,['class'=>'form-control', 'rows'=>'10'])}}
				</div>

				<div class="checkbox">
					@foreach(Tag::all()->sortBy('name') as $tag)
					<div class="col-xs-2 col-sm-2 col-md-2 col-lg-2">
						<label>
							@if ( $note->tags->contains($tag->id))
							<input checked="checked" type="checkbox" name="tags[]" value="{{$tag->id}}">
							@else
							<input type="checkbox" name="tags[]" value="{{$tag->id}}">
							@endif
							{{$tag->name}}
						</label>	
					</div>					
					@endforeach
				</div>
				
				<div class="col-xs-12 col-sm-12 col-md-12 col-lg-12 hgap">
					{{ Form::button('Update', ['type'=>'submit', 'class' => 'btn btn-primary btn-sm'])}}
					<a href="{{route('homepage')}}" class='btn btn-info btn-sm'>Back to Notes</a>
				</div>
			{{ Form::close() }}
		</div>
	</div>
</div>
@stop