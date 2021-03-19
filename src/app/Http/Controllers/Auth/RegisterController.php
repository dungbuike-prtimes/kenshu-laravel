<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\RegisterFormRequest;
use App\Providers\RouteServiceProvider;
use App\Repositories\BaseRepository;
use App\Repositories\ImageRepositoryInterface;
use App\Repositories\UserRepository;
use App\Repositories\UserRepositoryInterface;
use App\User;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

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

    private $userRepository;


    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @param UserRepositoryInterface $userRepository
     */
    public function __construct(UserRepositoryInterface $userRepository)
    {
        $this->middleware('guest');
        $this->userRepository = $userRepository;
    }

//    /**
//     * Get a validator for an incoming registration request.
//     *
//     * @param  array  $data
//     * @return \Illuminate\Contracts\Validation\Validator
//     */
//    protected function validator(array $data)
//    {
//        return Validator::make($data, [
//            'name' => ['required', 'string', 'max:255'],
//            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
//            'password' => ['required', 'string', 'min:8', 'confirmed'],
//        ]);
//    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param RegisterFormRequest $request
     * @return User
     */
    protected function create(RegisterFormRequest $request)
    {
        $params = $request->validated();
        $params['password'] = Hash::make($request['password']);
        if ($this->userRepository->create($params))
            return redirect()->route('login');
    }

    public function showRegistrationForm()
    {
        return view('auth.register');
    }
}
