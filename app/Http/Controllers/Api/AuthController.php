<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
class AuthController extends Controller
{
    public function login(Request $request){
        $user = User::whereEmail($request->email)->first();
        //پاس دادن آرایه به متد اعتبار سنجی کاربر
        if($user && Hash::check($request->password, $user->password)){//اگر کاربر موجود بود و پسورد نیز درست بود توکن را بساز وبه کاربر پاسخ بده
            $success['token'] =  $user->createToken('tp_@1')-> accessToken;
            return response()->json(['success' => $success],200);
        }
        else{
            return response()->json(['status'=>'نام کاربری یا رمز عبور صحیح نیست !'], 401);
        }
    }

}
