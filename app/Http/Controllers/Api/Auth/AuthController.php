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
            'phone_number' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'statusCode' => 400,
                'message' => 'Error!',
                'result' => ['errors' => $validator->errors()]
            ], 400);
        }

        try {
            // Create the user
            $user = User::create([
                'name' => $request->name,
                'email' => $request->email,
                'password' => Hash::make($request->password),
                'address' => $request->address,
                'phone_number' => $request->phone_number,
            ]);

            $roleName = 'passenger';
            $role = Role::where('name', $roleName === 'conductor' ? 'Bus_Conductor' : ucfirst($roleName))->firstOrFail();
            $user->assignRole($role);

            return response()->json([
                'statusCode' => 200,
                'message' => 'Success!',
                'role' => $role->name,
                'result' => $user,
            ], 200);
        } catch (\Exception $e) {

            return response()->json([
                'statusCode' => 500,
                'message' => 'An error occurred while processing your request.',
                'result' => ['error' => $e->getMessage()]
            ], 500);
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
                    return response()->json([
                        'statusCode' => 400,
                        'message' => 'Invalid credentials!',
                        'result' => ['error' => 'Driver cannot login via this route.']
                    ], 400);
                }

                $userArray = $user->toArray();
                unset($userArray['roles']);

                return response()->json([
                    'statusCode' => 200,
                    'message' => 'Success!',
                    'token' => $token,
                    'role' => $role,
                    'result' => $userArray,
                ], 200);
            } catch (\Exception $e) {
                return response()->json([
                    'statusCode' => 500,
                    'message' => 'An error occurred while processing your request.',
                    'result' => ['error' => $e->getMessage()]
                ], 500);
            }
        }

        return response()->json([
            'statusCode' => 400,
            'message' => 'Invalid credentials!',
            'result' => ['error' => 'Unauthorized']
        ], 401);
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

                return $this->responseFormatter->setStatusCode(400)
                    ->setMessage('User not authenticated')
                    ->format();
            }
        } catch (\Exception $e) {

            return $this->responseFormatter->setStatusCode(500)
                ->setMessage('An error occurred while processing your request.')
                ->format();
        }
    }
}
