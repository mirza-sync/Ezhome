<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
// use Illuminate\Foundation\Auth\RegistersUsers;
use Auth;
use App\Admin;
use App\User;
use App\Landlord;
use App\Facility;
use Hash;

class AdminController extends Controller
{
    // use RegistersUsers;
    use AuthenticatesUsers;
    protected $redirectTo = 'admin';

    public function __construct()
    {
        $this->middleware('guest:admin',['only' => ['login']]);
    }

    public function showRegisterForm()
    {
        return view('admin.register');
    }
    
    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255'],
            'password' => ['required', 'string', 'min:3', 'confirmed'],
        ]);

        $admin = new Admin;
        $admin->name = $request->input('name');
        $admin->email = $request->input('email');
        $admin->password = Hash::make($request->input('password'));
        $admin->save();

        return redirect($this->redirectPath());
    }

    public function showLoginForm()
    {
        return view('admin.admin-login');

    }

    public function login(Request $request)
    {
        //Validate the form date
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required|min:3'
        ]);

        //Attempt to log the user in
        if(Auth::guard('admin')->attempt(['email'=> $request->email, 'password'=> $request->password], $request->remember)) {
                //If successful, then redirect to their intended location
                // dd("LOGIN");
                return redirect()->intended(route('admin.dashboard'));
        }
        //If unsuccesstul, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('email','remember'));
    }
    
    public function all1(){
        $users = User::all();
        return view('admin.all-user')->with('users', $users);
    }
    public function all2(){
        $landlords = Landlord::all();
        return view('admin.all-landlord')->with('landlords', $landlords);
    }
    public function all3(){
        $facilitys = Facility::all();
        return view('facility.index')->with('facilitys', $facilitys);
    }

    public function index()
    {
        return view('admin.dashboard');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $type = $request->input('type');
        if ($type=='landlord') {
            $landlord = App\Landlord::find($id);
            $landlord->delete();
            return redirect()->route('admin.users');
        } else {
            $user = App\User::find($id);
            $user->delete();
            return redirect()->route('admin.landlords');
        }
    }
}
