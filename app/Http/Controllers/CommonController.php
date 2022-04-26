<?php

namespace App\Http\Controllers;

use Auth;
use JWTAuth;
use Validator;
use App\User;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CommonController extends Controller
{
    public function home(){
        return response()->json(['success' => true, 'data' => [], 'message' => 'Welcome to DMS! Unauthorised access!'], 200); 
    }

    public function modaratorList(){
        $users = User::where('user_type', 'Moderator')->get();
        return response()->json(['success' => true, 'data' => $users, 'message' => 'Moderator List'], 200); 
    }
}
