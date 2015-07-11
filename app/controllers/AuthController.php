<?php
use Betacie\Google\Tracker\EventTracker;
use Betacie\Google\Storage\ArrayStorage;

class AuthController extends BaseController {
	protected $layout = 'layouts.fronthome';
	
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
	
	public function deleteUser($user_id) {
		$userToBeDeleted = User::find($user_id);
		// Make sure we can't delete a top level user.
		if(!$userToBeDeleted->has_parent()) {
			return Redirect::to('/users')->withMessage("Can't delete a top-level user.");
		} else {
			// Make sure this subuser is owned by the currently logged in user
			if(!Auth::user()->owns_subuser($user_id)) {
				return Redirect::to('/users')->withMessage("Can't delete a subuser that doesn't belong to you.");
			} else {
				if($userToBeDeleted->delete()) {
					return Redirect::to('/users')->withMessage("User has been deleted.");
				} else {
					return Redirect::to('/users')->withMessage("User could not be deleted.");
				}
				
			}
			
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
			Mail::queue('emails.auth.invitesubuser', array('data' => $limbo_user->toArray()), function($message) use($limbo_user){
				$message->to($limbo_user->email)->subject('Please join ' . User::find($limbo_user->parent_user_id)->company_name . '\'s NoSprawl account.');
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
			$user->stripe_customer_id = $parent_u->stripe_customer_id;
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
			if(App::environment('local')) {
				Stripe::setApiKey(Config::get('stripe.development.secret'));
			} else {
				Stripe::setApiKey(Config::get('stripe.production.secret'));
			}
			
			$user = new User;
			$user->email = $input['email'];
			$user->name = $input['full_name'];
			$user->full_name = $input['full_name'];
			$user->company_name = $input['company'];
			$user->password = Hash::make($input['password']);
			
			// Once we know the user has been created on our end, create the stripe customer
			try {
				$customer = Stripe_Customer::create(array(
					"email" => $input['email'],
				  "description" => "User ID: " . $user->id . "\nName: " . $user->full_name,
				  "card" => $input['stripe_token'] // obtained with Stripe.js
				));
				
			} catch (Exception $exception) {
				 return Redirect::to('register')->withErrors(array('message' => array("card_error" => print_r($exception))));
			}
			
			$subscription = $customer->subscriptions->create(array(
				"plan" => $input['plan'],
			));
			
			$user->stripe_customer_id = $customer->id;
			
			try {
				$user->save();
			} catch(Exception $e) {
				return Redirect::to('register')->withErrors(array('message' => array("card_error" => "Couldn't validate billing info.")));
			}
			
			// In theory the customer has been charged at this point
			Mail::queue('emails.auth.welcome', [], function($message) use($user){
				$message->to($user->email)->subject('Welcome to NoSprawl!');
			});
			
			//$storage = new ArrayStorage();
			//$tracker = new EventTracker($storage);
			
			//$tracker->trackEvent(['action' => 'created_account']);
			
			Auth::login($user, true);

			//return Redirect::to('/')->with('tracker', $tracker);
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