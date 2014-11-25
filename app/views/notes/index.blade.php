@extends('layouts.master')

@section('page_title', 'Home')

@section('content')
<div class="container">
	{{ $notes->links() }}

	@foreach($notes as $note)		
		<div class="panel panel-info">
			  <div class="panel-heading">
					<h3 class="panel-title">{{$note->title}} <span class='pull-right'>{{ $note->created_at->diffForHumans() }}</span></h3>

			  </div>
			  <div class="panel-body">
			  		
					<pre>{{$note->content}}</pre>

			  </div>
		</div>
	@endforeach
	
	{{ $notes->links() }}
</div>
	
@stop