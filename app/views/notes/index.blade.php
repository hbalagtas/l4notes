@extends('layouts.master')

@section('page_title', 'Home')

@section('content')
<div class="container">
	
	@if (!empty($notes) )
	@if (method_exists($notes, 'links'))		
		{{ $notes->appends(['searchterm' => Input::get('searchterm')])->links() }}	
	@endif
	
	
		<?php 
			$toggle = 'in'; 
			$panel = "info";
		?>

		@foreach($notes as $note)		
			<div class="panel panel-{{$panel}}">
				  <div class="panel-heading" data-toggle="collapse" data-target="#{{$note->id}}">
						<h3 class="panel-title">#{{$note->id}} {{$note->title}} 
							<span class='pull-right'>
								@foreach($note->tags as $tag)
									<!-- <button type="button" class="btn btn-primary btn-xs">{{$tag->name}}</button> -->
									<a class="btn btn-primary btn-xs" href="{{route('homepage',['tag'=>$tag->name])}}">{{$tag->name}}</a>
								@endforeach
								
								{{ $note->created_at->diffForHumans() }}
							</span>

						</h3>

				  </div>
				  <div class="panel-body collapse {{$toggle}}" id="{{$note->id}}">
				  		
						<pre id="content{{$note->id}}"><i class="fa fa-quote-left fa-3x pull-left "></i>{{$note->content}}</pre>
						
						
						
						{{ Form::open(['route'=>['note.destroy', $note->id], 'method' => 'delete'])}}
						<a href="{{route('note.edit',['id'=>$note->id])}}" class="btn btn-info btn-xs"><i class="fa fa-pencil fa-fw"></i> Edit</a>						
						<button type="submit" onclick="return confirm('Are you sure you want to delete this note?');" class="btn btn-danger btn-xs"><i class="fa fa-remove fa-fw"></i> Delete</button>
						{{ Form::close() }}
						{{$note->tomboy_id}}
				  </div>
			</div>
		<?php 
			$toggle = ''; 
			$panel = ($panel == 'info'? 'warning':'info');
		?>
		@endforeach
	@if (method_exists($notes, 'links'))		
		{{ $notes->appends(['searchterm' => Input::get('searchterm')])->links() }}	
	@endif		
		
	@else

		<div class="jumbotron">
			<div class="container">
				<h1>Welcome to TBird Notes</h1>
				@if (Auth::check())
				<p>You haven't created any notes yet, click on the button below to create your first note.</p>
				<p>					
					{{ link_to_route('note.create', 'Create Note', null, ['class' => 'btn btn-primary btn-lg'])}}
				</p>
				@else
				<p>Please sign-in to create and view you previous notes.</p>
				<p>					
					{{ link_to_route('login', 'Sign-in', null, ['class' => 'btn btn-primary btn-lg'])}}
				</p>
				@endif
			</div>
		</div>

	@endif
</div>
	
@stop