<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;
use Illuminate\Support\Carbon;


class UserController extends Controller
{
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
        ]);
        if ($validator->fails())
        {
            return response()->json(['errors'=>$validator->errors()]);
        }
        $user = User::create([
            'first_name'=>$request->first_name,
            'last_name'=>$request->last_name,
            'email'=>$request->email,
            'password'=>Hash::make($request->password),
            'status'=> 'Inactive',

        ]);
        $random = Str::random(40);
        $domain = URL::to('/');
        $url = $domain.'/verify-mail/'.$random;
        $data['url'] = $url;
        $data['email'] = $user->email;
        $data['title'] = 'Email Verification';
        $data['body'] = 'Please click here to below verify your mail';

        Mail::send('verifyMail', ['data'=>$data], function($message) use ($data){
            $message->to($data['email'])->subject($data['title']);
        });
        $user->remember_token = $random;
        $user->save();
        return response()->json([
            'msg'=>'User Registerd Successfully',
            'user'=>$user,
        ]);
    }

    // created Login API
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'=>'required|string|email|',
            'password'=>'required|string|min:6',
        ]);

        if ($validator->fails()){
            return response()->json($validator->errors());
        }
        $user = User::where('email', $request->email)->first();
        if (!$user || $user->status != 'Active') {
            return response()->json(['error' => 'User is inactive or does not exist.'], 401);
        }
        $token = auth()->guard('api')->attempt($validator->validated());
        if (!$token)
        {
            return response()->json([
                'success'=>false,
                'msg' =>'Username and Password is uncorrect',
            ]);

        }
        return response()->json([
            'success' => true,
            'msg'=>'Successfully Login',
            'token' => $token,
            'token_type' => 'Bearer',
            'expires_in' => auth()->factory()->getTTL()*60
        ]);
    }

    // created profile-update API
    public function updateProfile(Request $request)
    {
        if (auth()->user()){
            $validator = Validator::make($request->all(), [
                'first_name' => 'string|max:255',
                'last_name' => 'string|max:255',
                'phone_number' => 'string|max:20',
                'gender' => 'in:Male,Female,Other',
                'address' => 'string',
                'street' => 'string|max:255',
                'city' => 'string|max:255',
                'state' => 'string|max:255',
                'country' => 'string|max:255',
                'postal_code' => 'string|max:10',
            ]);

            if ($validator->fails()){
                return response()->json($validator->errors());
            }
            $user = User::find(auth()->user()->id);
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->phone_number = $request->phone_number;
            $user->gender = $request->gender;
            $user->address = $request->address;
            $user->street = $request->street;
            $user->city = $request->city;
            $user->state = $request->state;
            $user->country = $request->country;
            $user->postal_code = $request->postal_code;
            $user->save();
            return response()->json([
                'success' => true,
                'msg' => 'User Updated Successfully',
                'data' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'msg' => 'User is Not Authenticated',
            ]);
        }
    }
    public function sendVerifyMail($email)
    {
        if (auth()->user()){
            $user = User::where('email', $email)->get();
            if (count($user) > 0) {
                $random = Str::random(40);
                $domain = URL::to('/');
                $url = $domain.'/verify-mail/'.$random;
                $data['url'] = $url;
                $data['email'] = $email;
                $data['title'] = 'Email Verification';
                $data['body'] = 'Please click here to below verify your mail';

                Mail::send('verifyMail', ['data'=>$data], function($message) use ($data){
                    $message->to($data['email'])->subject($data['title']);
                });
                $user = User::find($user[0]['id']);
                $user->remember_token = $random;
                $user->save();
                return response()->json([
                    'success' => true,
                    'msg' => 'Mail Sent Successfully',
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'msg' => 'User not found.',
                ]);
            }

        } else {
            return response()->json([
                'success' => false,
                'msg' => 'User is Not Authenticated',
            ]);
        }
    }

    // token link after verification message
    public function verificationMail($token)
    {
        $user = User::where('remember_token', $token)->get();
        if (count($user) > 0) {
            $datetime = Carbon::now()->format('Y-m-d H:i:s');
            $user = User::find($user[0]['id']);
            $user->remember_token = '';
            $user->status = 'active';
            $user->email_verified_at = $datetime;
            $user->save();
            return "<h1>Email Verified Successfully</h1>";
        } else {
            return view('404');
        }
    }
}
