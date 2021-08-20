<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Foundation\Auth\RegistersUsers;
use Auth;
use App\Landlord;
use Hash;

class LandlordAuthController extends Controller
{
    use RegistersUsers;
    protected $redirectTo = 'landlord';

    public function __construct()
    {
        $this->middleware('guest:landlord');
    }

    public function showRegisterForm()
    {
        return view('landlord.register');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'phone' => ['required', 'string', 'max:255'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
        ]);

        $landlord = new Landlord;
        $landlord->name = $request->input('name');
        $landlord->email = $request->input('email');
        $landlord->phone = $request->input('phone');
        $landlord->password = Hash::make($request->input('password'));
        $landlord->save();

        return redirect($this->redirectPath());
    }

    // public function register(array $data)
    // {
    //     return Validator::make($data, [
    //         'name' => ['required', 'string', 'max:255'],
    //         'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
    //         'phone' => ['required', 'string', 'max:255'],
    //         'password' => ['required', 'string', 'min:3', 'confirmed'],
    //     ]);

    //     return Landlord::create([
    //         'name' => $data['name'],
    //         'email' => $data['email'],
    //         'phone' => $data['phone'],
    //         'password' => Hash::make($data['password']),
    //     ]);
    // }

    public function showLoginForm()
    {
        return view('auth.landlord-login');

    }

    public function login(Request $request)
    {
        //Validate the form date
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:3'
        ]);

        //Attempt to log the user in
        if(Auth::guard('landlord')->attempt(['email'=> $request->email, 'password'=> $request->password], $request->remember)) {
                //If successful, then redirect to their intended location
                return redirect()->intended(route('landlord.dashboard'));
        }
        //If unsuccesstul, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('email','remember'));
    }
    
}
