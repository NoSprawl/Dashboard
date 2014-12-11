@extends('layouts.front')

@section('header')
	@parent
@stop
<?php //dd($errors->first('email')); ?>
@section('content')
	{{ Form::open(['url' => 'register']) }}

    	{{ Form::label('email', 'E-mail') }}
    	{{ $errors->first('email', '<span class="error">:message</span>') }}
    	{{ Form::text('email') }}
		{{ Form::label('password', 'Password') }}
		{{ $errors->first('password', '<span class="error">:message</span>') }}
		{{ Form::password('password') }}
		{{ Form::submit('Register') }}

	{{ Form::close() }}
@stop
