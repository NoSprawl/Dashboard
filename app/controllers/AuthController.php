<?php

class AuthController extends Controller {

	public function getRegistration() {

		return View::make('registration');

	}

	public function postRegistration() {

		$input = Input::all();

		$rules = [
					'email' => 	'required|email|unique:users,email',
					'password' => 'required|min:7'
				 ];

		$validator = Validator::make($input, $rules);

		if($validator->passes()) {
			$user = new User;
			$user->email = $input['email'];
			$user->password = Hash::make($input['password']);
			$user->save();

			Mail::queue('emails.auth.welcome', [], function($message) use($user){
				$message->to($user->email)->subject('Welcome to NoSprawl!');
			});

			Auth::login($user, true);

			return Redirect::to('dashboard');

		}

		return Redirect::to('register')->withErrors($validator);

	}

	public function getLogout() {

		Auth::logout();

		return Redirect::to('/');

	}

	public function getLogin() {

		return View::make('login');

	}

	public function postLogin() {

		if(Auth::attempt(['email' => Input::get('email'), 'password' => Input::get('password')], true))
		{

			return Redirect::intended('dashboard');

		}

	}

}