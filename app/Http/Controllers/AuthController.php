<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Mail\EmailConfirmed;
use \Illuminate\Support\Facades\URL;

class AuthController extends Controller
{
    public function register(Request $request){
        
        $request->validate([
            'name' => 'required',
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $user = new User;
        
        $user->name = $request->input('name');
        $user->email = $request->input('email');
        $user->password = hash::make($request->input('password'));
        $user->confirmed_email = FALSE;

        $user->save();
        
        return redirect('login');
    }

    public function login(Request $request){

        $request->validate([
            'email'=> 'required',
            'password'=> 'required',
        ]);

        $user = User::where('email', $request->input('email'))->first();
        
        if (! $user || ! Hash::check($request->input('password'), $user->password)) {
            return '<script type="text/javascript">alert("Email o Password incorrecto"); window.location.href = "/login";</script>';
        }
        
        $token = $user->createToken($request->email,['user'])->plainTextToken;
        $code = random_int(100000, 999999);
        $user->code = $code;
        $user->save();

        $verificacion = [
            'code'=>$code
        ];

        

        Mail::to($user->email)->send(new EmailConfirmed($verificacion));

        $urltemp = URL::temporarySignedRoute(
            'verificacion', 
            now()->addMinutes(5), 
            ['id' => $user->id]
        );

        return redirect($urltemp);
    }


    public function logout(Request $request){
        return response()->json(["status"=>TRUE,"Tokens afectados" => $request->user()->tokens()->delete()],200);
    }

    public function verificacion(Request $request,$id){
        $user = User::where('id',$id)->first();
        if($user->code == $request->input('codigo')){
            $user->confirmed_email = TRUE;
            $user->save();
            return redirect('/home');
        }else{
            return '<script type="text/javascript" href="/login">alert("Codigo invaldo");</script>';
        }
    }
}
