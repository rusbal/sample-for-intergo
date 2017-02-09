<?php

namespace App\Http\Controllers\Auth;

use App\User;
use App\Mail\AppMailer;
use Illuminate\Http\Request;
use App\Notifications\UserSignedUp;
use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\Registered;
use Illuminate\Support\Facades\Validator;
use Illuminate\Foundation\Auth\RegistersUsers;

class RegisterController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Register Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users as well as their
    | validation and creation. By default this controller uses a trait to
    | provide this functionality without requiring any additional code.
    |
    */

    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Handle a registration request for the application.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function register(Request $request)
    {
        $this->validator($request->all())->validate();

        $user = $this->create($request->all());
        event(new Registered($user));

        $user->notify(new UserSignedUp($user));

        flash('Please confirm your email address.');

        return $this->registered($request, $user)
            ?: redirect($this->redirectPath());
    }

    public function confirmEmail($token)
    {
        $user = User::confirmEmail($token);

        if ($user) {
            flash("Welcome $user->name.  Your registration is now complete!", 'success');
            $this->guard()->login($user);
        } else {
            flash("Sorry, the confirmation link you clicked is not valid.", 'danger');
        }

        return redirect($this->redirectPath());
    }

    /**
     * Shows a form that accepts email address.
     * Submits to @resendVerificationLink
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function unverified()
    {
        return view('auth.unverified');
    }

    public function resendVerificationLink(Request $request)
    {
        $email = $request->get('email');

        if ($user = User::getUnverifiedEmail($email)) {
            $user->notify(new UserSignedUp($user));
            flash('Email with verification link has been resent.');
            return redirect()->route('login');
        }

        flash('Email is not registered or is already verified.', 'danger');

        return view('auth.unverified', ['email' => $email]);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        $conditions = [
            'name' => 'required|max:255',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|min:6|confirmed',
        ];

        if (config('app.url') == 'http://dev.com:8000') {
            /**
             * Local development server
             *
             * Development is throwing error * cURL error 51: SSL: certificate verification failed (result: 5)
             * (see http://curl.haxx.se/libcurl/c/libcurl-errors.html)
             *
             * Development: No Google recaptcha
             */
        } else {
            // Live: With Google recaptcha
            $conditions['g-recaptcha-response'] = 'required|captcha';
        }

        return Validator::make($data, $conditions);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
        ]);
    }
}
