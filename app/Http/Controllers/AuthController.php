<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use GuzzleHttp\Client;
use \Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Hash;

class AuthController extends Controller
{
    public function login(Request $request, $register = false, $user = null)
    {
        try {
            $this->validate($request, [
                'email'    => 'required|email',
                'password' => 'required|min:6'
            ]);

            $email    = $request->email;
            $password = $request->password;
            if (!$register){
                $user = User::where('email', $email)->first();
    
                if (!$user || !Hash::check($password, $user->password)) {
                    return response()->json(['error' => 'Provied email address or password is incorrect!'], 401);
                }
            }
            
            $client = new Client();
            $token  = $client->post(config('service.passport.login_endpoint'), [
                "form_params" => [
                    "client_secret" => config('service.passport.client_secret'),
                    "grant_type"    => "password",
                    "client_id"     => config('service.passport.client_id'),
                    "username"      => $email,
                    "password"      => $password
                ]
            ]);

            $responseBody = $token->getBody()->getContents();
            $token = json_decode($responseBody, true);
            $token = $token['access_token'];

            if ($register) {
                return response()->json([
                    'token' => $token,
                    'user'  => $user
                ]);
            } else {
                return response()->json([
                    'token' => $token,
                    'user'  => $user
                ]);
            }
        } catch (ValidationException $e) {

            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {

            return response()->json(['errors' => $e->getMessage()], 500);
        }
    }
    public function signup(Request $request)
    {
        try {
            $this->validate($request, [
                'name'     => 'required|max:55',
                'email'    => 'required|email|unique:users',
                'password' => 'required|min:6|confirmed'
            ]);

                $name     = $request->name;
                $email    = $request->email;
                $password = $request->password;

                $user           = new User();
                $user->name     = $name;
                $user->email    = $email;
                $user->password = app('hash')->make($password);
    
                if ($user->save()) {
                      // Will call login method
                    return $this->login($request, true, $user);
                }
        } catch (ValidationException $e) {

            return response()->json(['errors' => $e->errors()], 422);
        } catch (\Exception $e) {

            return response()->json(['errors' => $e], 500);
        }
    }

    public function logout(Request $request)
    {
        try {
            auth()->user()->tokens()->each(function ($token, $key) {
                $token->delete();
            });

            return response()->json(['status' => 'success', 'message' => 'Logged out successfully']);
        } catch (\Exception $e) {
            return response()->json(['status' => 'error', 'message' => $e->getMessage()]);
        }
    }

}
