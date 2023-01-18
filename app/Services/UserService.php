<?php


namespace App\Services;


use Illuminate\Http\Request;
use Illuminate\Http\Response;
use App\Services\ColumnService;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
class UserService {

    public function addUser (Request $request) {
         
        $validator = Validator::make($request->all(), [
            'username' => 'required|max:255',
            'email' => 'required|max:255|Email|unique:users',
            // password must be at least 8 characters 
            'password' => 'required|min:8',
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $user = User::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => $request->password,
        ]);
        $token = $user->createToken($user->id)->plainTextToken;
        $user['token'] = $token;
        $response = [
            'status' => 'success',
            'data' => new UserResource($user),
            'token' => $token
        ];
          return response($response)->setStatusCode(Response::HTTP_CREATED);

    }
    public function login(Request $request){
        $validator = Validator::make($request->all(), [
            'username' => 'required',
            'password' => 'required',
        
        ]);
        if ($validator->fails()) {
            return response()->json(['error'=>$validator->errors()], 401);
        }
        $user =User::where('username',$request->username)->first();

        if(!$user ||!Hash::check($request->password,$user->password)){
            $response=[
                'statusCode'=>401,
                'message'=>'Invalid Password or Username',
                
            ];
            return response($response);
        }
        $token = $user->createToken($user->id)->plainTextToken;
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
            'message'=>'User Logged out',
            'status'=>'success'
        ],200);
    }

}

