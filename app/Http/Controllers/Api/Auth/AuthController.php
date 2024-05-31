<?php

namespace App\Http\Controllers\Api\Auth;

use App\Helpers\HttpResponseFormatter;
use App\Http\Controllers\Controller;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    protected $responseFormatter;

    public function __construct(HttpResponseFormatter $responseFormatter)
    {
        $this->responseFormatter = $responseFormatter;
    }

    public function register(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:6',
            'address' => 'required|string',
            'gender' => 'required|in:male,female',
            'phone_number' => 'required|string',
            'images' => 'required|string',
            'isAuth' => 'required|string|in:passenger,conductor,driver',
        ]);

        // Handle validation errors
        if ($validator->fails()) {
            return $this->responseFormatter->setStatusCode(400)
                ->setMessage('Error!')
                ->setResult(['errors' => $validator->errors()])
                ->format();
        }

        try {
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'gender' => $request->gender,
                'phone_number' => $request->phone_number,
                'images' => 'default.jpg',
            ]);

            $roleName = $request->isAuth;
            $role = Role::where('name', $roleName === 'conductor' ? 'Bus_Conductor' : ucfirst($roleName))->firstOrFail();
            $user->assignRole($role);

            $token = $user->createToken('API Token')->plainTextToken;

            return $this->responseFormatter->setStatusCode(201)
                ->setMessage('Success!')
                ->setResult(['user' => $user, 'token' => $token])
                ->format();
        } catch (\Exception $e) {

            return $this->responseFormatter->setStatusCode(500)
                ->setMessage('An error occurred while processing your request.')
                ->setResult(['error' => $e->getMessage()])
                ->format();
        }
    }

    public function login(Request $request)
{
    $credentials = $request->only('email', 'password');

    if (Auth::attempt($credentials)) {
        try {
            $user = $request->user();
            $token = $user->createToken('API Token')->plainTextToken;
            $roles = $user->getRoleNames();


            $role = $roles->isNotEmpty() ? $roles->first() : null;


            if ($role === 'driver') {
                return $this->responseFormatter->setStatusCode(200)
                    ->setMessage('Login successful! Welcome driver.')
                    ->setResult([
                        'user' => $user->toArray(),
                        'token' => $token,
                        'role' => $role
                    ])
                    ->format();
            }

            $userArray = $user->toArray();
            unset($userArray['roles']);

            return $this->responseFormatter->setStatusCode(201)
                ->setMessage('Success!')
                ->setResult([
                    'user' => $userArray,
                    'token' => $token,
                    'role' => $role
                ])
                ->format();
        } catch (\Exception $e) {
            return $this->responseFormatter->setStatusCode(500)
                ->setMessage('An error occurred while processing your request.')
                ->setResult(['error' => $e->getMessage()])
                ->format();
        }
    }

    return $this->responseFormatter->setStatusCode(401)
        ->setMessage('Invalid credentials!')
        ->setResult(['error' => 'Email or password is incorrect.'])
        ->format();
}


    public function logout(Request $request)
    {
        try {
            $user = $request->user();
            if ($user) {
                $user->tokens()->delete();


                return $this->responseFormatter->setStatusCode(200)
                    ->setMessage('Logout Success!')
                    ->format();
            } else {

                return $this->responseFormatter->setStatusCode(401)
                    ->setMessage('User not authenticated')
                    ->format();
            }
        } catch (\Exception $e) {

            return $this->responseFormatter->setStatusCode(500)
                ->setMessage('An error occurred during logout.')
                ->setResult(['error' => $e->getMessage()])
                ->format();
        }
    }
}
