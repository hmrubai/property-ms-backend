<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use JWTAuth;
use Validator;
use App\User;
use App\Http\Requests\RegisterAuthRequest;
use Tymon\JWTAuth\Exceptions\JWTException;
use Symfony\Component\HttpFoundation\Response;

class JwtAuthController extends Controller
{
    public $token = true;

    public function register(Request $request)
    {
        $user_info = User::where("email", $request->email)->first();
        if(!empty($user_info) || !$request->email){
            return response()->json(['success' => false, 'data' => [], 'message' => 'The email has already been taken!'], 400); 
        }

        if(!$request->email){
            return response()->json(['success' => false, 'data' => [], 'message' => 'Please, Enter email!'], 400); 
        }

        $validator = Validator::make($request->all(), 
        [ 
            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:6',  
            'c_password' => 'required|same:password', 
        ]);  

        if ($validator->fails()) {  
            $message = null;
            if(!$request->name){
                $message = "Name is required";
            }
            
            if($request->password != $request->c_password){
                $message = "Password does not match!";
            }
            return response()->json(['success' => false, 'data' => [], 'message' => $message], 400); 
        } 

        $user = new User();
        $user->name = $request->name;
        $user->email = $request->email;
        $user->contact_no = $request->contact_no ? $request->contact_no : null;
        $user->user_type = $request->user_type ? $request->user_type : "Administrator";
        $user->address = $request->address ? $request->address : null;
        $user->department = $request->department ? $request->department : null;
        $user->designation = $request->designation ? $request->designation : null;
        $user->is_active = $request->is_active ? true : false;
        $user->password = bcrypt($request->password);
        $user->save();
        
        $input = $request->only('email', 'password');
        $jwt_token = null;
        $jwt_token = JWTAuth::attempt($input);
        $user->token = $jwt_token;

        return response()->json([
            'success' => true,
            'data' => $user,
            'message' => "Registration successful."
        ], Response::HTTP_OK);
    }

    public function login(Request $request)
    {
        $input = $request->only('email', 'password');

        $jwt_token = null;
        if (!$jwt_token = JWTAuth::attempt($input)) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Invalid Email or Password',
            ], Response::HTTP_UNAUTHORIZED);
        }

        $user_info = User::where("email", $request->email)->first();
        $user_info->token = $jwt_token;
        $param = ["user" => $user_info, "token" => $jwt_token];

        return response()->json([
            'success' => true,
            'data' => $user_info,
            'message' => "Login successful."
        ]);
    }

    public function forget_password(Request $request)
    {
        $user_info = User::where("email", $request->email)->first();

        if(empty($user_info)){
            return response()->json(['success' => false, 'data' => [], 'message' => 'No user has been registered using this email!'], 400); 
        }

        $reset_token = random_int(100000, 999999);
        User::where('email', $request->email)->update(["reset_token" => $reset_token]);
        
        //app('App\Http\Controllers\MailController')->send_forget_password_email($user_info->name, $user_info->email, $reset_token);

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => "Token has been sent to your email. Please, check your email!"
        ]);
    }

    public function code_verification(Request $request)
    {
        $user_info = User::where("email", $request->email)->first();

        if(empty($user_info)){
            return response()->json(['success' => false, 'data' => [], 'message' => 'No user has been registered using this email!'], 400); 
        }

        if(!$request->email){
            return response()->json(['success' => false, 'data' => [], 'message' => 'Please, Enter email!'], 400); 
        }

        if(!$request->verification_code){
            return response()->json(['success' => false, 'data' => [], 'message' => 'Please, Enter verification code!'], 400); 
        }

        if($user_info->reset_token != $request->verification_code){
            return response()->json(['success' => false, 'data' => [], 'message' => 'Invalid verification code!'], 400); 
        }

        return response()->json([
            'success' => true,
            'data' => [],
            'message' => "Verification code is valid"
        ], Response::HTTP_OK);        
    }

    public function reset_password(Request $request)
    {
        $user_info = User::where("email", $request->email)->first();

        if(empty($user_info)){
            return response()->json(['success' => false, 'data' => [], 'message' => 'No user has been registered using this email!'], 400); 
        }

        if(!$request->email){
            return response()->json(['success' => false, 'data' => [], 'message' => 'Please, Enter email!'], 400); 
        }

        if(!$request->password){
            return response()->json(['success' => false, 'data' => [], 'message' => 'Please, Enter password!'], 400); 
        }

        if(!$request->verification_code){
            return response()->json(['success' => false, 'data' => [], 'message' => 'Please, Enter verification code!'], 400); 
        }

        if($user_info->reset_token != $request->verification_code){
            return response()->json(['success' => false, 'data' => [], 'message' => 'Invalid verification code!'], 400); 
        }

        User::where('email', $request->email)->update(["password" => bcrypt($request->password), "reset_token" => null]);

        $input = $request->only('email', 'password');
        $jwt_token = null;
        $jwt_token = JWTAuth::attempt($input);
        $user_info->token = $jwt_token;

        return response()->json([
            'success' => true,
            'data' => $user_info,
            'message' => "Password reset successful."
        ], Response::HTTP_OK);
    }

    public function logout(Request $request)
    {
        $this->validate($request, [
            'token' => 'required'
        ]);

        try {
            JWTAuth::invalidate($request->token);
            return response()->json([
                'success' => true,
                'data' => [],
                'message' => 'User logged out successfully'
            ]);
        } catch (JWTException $exception) {
            return response()->json([
                'success' => false,
                'data' => [],
                'message' => 'Sorry, the user cannot be logged out'
            ], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }

    public function get_user(Request $request)
    {
        $user = Auth::user();
        return response()->json(['success' => true, 'data' => $user, 'message' => 'User featched successful']);
    }
}
