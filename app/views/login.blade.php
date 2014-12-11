@extends('layouts.front')

@section('header')
	@parent
@stop

@section('content')
	{{ Form::open(['url' => 'login']) }}
		{{ Form::label('email', 'E-Mail Address'); }}
		{{ Form::text('email') }}
		{{ Form::label('password', 'Password'); }}
		{{ Form::password('password') }}
		{{ Form::submit('Come on in!') }}
	{{ Form::close() }}
@stop

