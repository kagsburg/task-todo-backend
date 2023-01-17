<?php


namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\ColumnService;
use App\Http\Resources\UserResource;
use App\Models\User;

use Illuminate\Support\Facades\Hash;
class UserService {

    public function addUser (Request $request) {
        $data=$request->validate([
            'username' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:6'
        ]);

        $user = User ::create($data);
        $token = $user->createToken('token')->plainTextToken;
        $response = [
            'status' => 'success',
            'user' => $user,
            'token' => $token
        ];
        return response($response)->setStatusCode(Response::HTTP_OK);

    }
    public function login(Request $request){
        $fields = $request->validate([
            'username'=>'required|string',
            'password'=>'required|string'
        ]);
        $user =User::where('username',$fields['username'])->first();

        if(!$user ||!Hash::check($fields['password'],$user->password)){
            $response=[
                'statusCode'=>401,
                'message'=>'Invalid Password or Email',                
            ];
            return response($response);
        }
        $token = $user->createToken('myapptoken')->plainTextToken;
        $response=[
            'status'=>'success',
            'user'=>new UserResource($user),
            'token'=>$token
        ];
        return response($response)->setStatusCode(Response::HTTP_OK);
    }
    public function logout(Request $request){
        if ($request->user()) { 
            $request->user()->tokens()->delete();
        }
        // auth('sanctum')->user()->tokens()->delete();
        return response( [
            'message'=>'User Logged out'
        ],200);
    }

}

