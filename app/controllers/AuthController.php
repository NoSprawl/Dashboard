<?php

class AuthController extends Controller {

	public function getRegistration() {

		return View::make('registration');

	}

	public function postRegistration() {
		
		$input = Input::all();
		$rules = [
			'full_name' => 'required',
			'email' => 	'required|email|unique:users,email',
			'password' => 'required|min:7',
			'confirm_password' => 'required|same:password'
		];

		$validator = Validator::make($input, $rules);
		if($validator->passes()) {
			Stripe::setApiKey(Config::get('stripe.stripe.secret'));
			$user = new User;
			$user->email = $input['email'];
			$user->full_name = $input['full_name'];
			$user->password = Hash::make($input['password']);
			$user->save();
			
			// Once we know the user has been created on our end, create the stripe customer
			$customer = Stripe_Customer::create(array(
				"email" => $input['email'],
			  "description" => "User ID: " . $user->id . "\nName: " . $user->full_name,
			  "card" => $input['stripe_token'] // obtained with Stripe.js
			));
			
			$subscription = $customer->subscriptions->create(array(
				"plan" => $input['plan'],
			));
			
			$user->stripe_customer_id = $customer->id;
			$user->save();
			
			// In theory the customer has been charged at this point
			Mail::queue('emails.auth.welcome', [], function($message) use($user){
				$message->to($user->email)->subject('Welcome to NoSprawl!');
			});

			Auth::login($user, true);

			return Redirect::to('/');
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

			return Redirect::intended('nodes');

		}

	}

}