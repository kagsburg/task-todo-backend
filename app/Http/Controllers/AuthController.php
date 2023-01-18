<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\UserService;
class AuthController extends Controller
{
    //
    private $authService;
    public function __construct(UserService $authService)
    {
        $this->authService = $authService;
    }

    public function login(Request $request){
        return $this->authService->login($request);
    }
    public function register(Request $request){
        return $this->authService->addUser($request);
    }
}
