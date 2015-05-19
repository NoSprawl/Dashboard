<?php

class AuthController extends BaseController {
	protected $layout = 'layouts.front';
	
	public function getRegistration() {

		return View::make('registration');

	}
	
	public function onboard($token)
	{
		$user = LimboUser::where('user_confirmation_token', $token)->first();
		
		if(count($user) > 0) {
			$this->layout->content = View::make('onboard.main')->with('user', $user)->with('parent_user', User::find($user->parent_user_id));
		} else {
			return Redirect::to('/');
		}
		
	}
	
	public function createSubuserInLimbo() {
		$input = Input::all();
		$rules = [
			'full_name' => 'required',
			'email' => 	'required|email|unique:users,email'
		];
		
		$validator = Validator::make($input, $rules);
		if($validator->passes()) {
			$limbo_user = new LimboUser;
			$limbo_user->email = $input['email'];
			$limbo_user->name = $input['full_name'];
			$limbo_user->parent_user_id = Auth::user()->id;
			$limbo_user->user_confirmation_token = uniqid("", true);
			$limbo_user->save();
			Mail::queue('emails.auth.invitesubuser', [], function($message) use($limbo_user){
				$message->to($input['email'])->subject('Please join ' + Auth::user()->company_name + '\'s NoSprawl account.');
			});
			
			return Redirect::to('/users')->withMessage("Instructions Sent!");
		} else {
			return Redirect::to('register')->withErrors($validator);
		}
		
	}
	
	public function postRegistrationFromSubuser() {
		Auth::logout();
		$input = Input::all();
		$rules = [
			'full_name' => 'required',
			'email' => 	'required|email|unique:users,email',
			'password' => 'required|min:7',
			'confirm_password' => 'required|same:password'
		];
		
		$validator = Validator::make($input, $rules);
		$user = null;
		
		$tempUser = LimboUser::where('user_confirmation_token', $input['user_confirmation_token'])->first();

		if(sizeof($tempUser) == 0) {
			return Redirect::back()->withMessage("Couldn't validate token.");
		}
		
		$user = null;
		
		if($validator->passes()) {
			$parent_u = User::find($tempUser->parent_user_id);
			$user = new User;
			$user->email = $input['email'];
			$user->name = $input['full_name'];
			$user->full_name = $input['full_name'];
			$user->password = Hash::make($input['password']);
			$user->parent_user_id = $parent_u->id;
			$user->last_login = new DateTime;
			$user->company_name = $parent_u->company_name;
			$user->save();
			Mail::queue('emails.auth.welcomeSubuser', [], function($message) use($user){
				$message->to($user->email)->subject('Welcome to NoSprawl!');
			});
			
			$tempUser->delete();
			
			Auth::login($user, true);
			return Redirect::to('/');
			
		} else {
			return Redirect::back()->withErrors($validator);
		}
		
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
			$user->name = $input['full_name'];
			$user->full_name = $input['full_name'];
			$user->company_name = $input['company'];
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