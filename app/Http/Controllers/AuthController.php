<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        try{
            $data = $request->validate([
                'name' => ['required', 'string'],
                'phone_number' => ['required', 'regex:/^[0-9]+$/', 'unique:users,phone_number', 'string',
                                    'min:3', 'max:25'],
                'password' => ['required', 'min:8', 'max:30'],
            ]);

            $user = User::create($data);

            $token = $user->createToken('auth_token',['*'], now()->addDays(3))->plainTextToken;

            return response()->json(['user' => $user,'token' => $token],
             201, [], JSON_PRETTY_PRINT);
        }
        catch(ValidationException $e){
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }
    }

    public function login(Request $request)
    {
        try
        {
            $data = $request->validate([
                'phone_number' => ['required', 'regex:/^[0-9]+$/', 'exists:users,phone_number', 'string',
                                    'min:3', 'max:25'],
                'password' => ['required', 'min:8'],
            ]);

            $user = User::where('phone_number', $data['phone_number'])->first();

            if (!Hash::check($data['password'], $user->password)) {
                throw ValidationException::withMessages([
                    'password' => ['The provided password is incorrect.'],
                ]);
            }

            $token = $user->createToken('auth_token',['*'], now()->addDays(3))->plainTextToken;

            return response()->json(['user' => $user,'token' => $token],
             200, [], JSON_PRETTY_PRINT);
        }
        catch(ValidationException $e){
            return response()->json([
                'message' => 'Validation failed.',
                'errors' => $e->errors(),
            ], 422);
        }
    }
}
