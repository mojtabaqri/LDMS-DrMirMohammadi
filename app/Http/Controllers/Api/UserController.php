<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Library\TokenManager;
use App\Http\Resources\UserCollection;
use App\MobileToken;
use App\Profile;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Carbon;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request){ //لاگین کاربر
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

    public function logout(Request $request){
        auth('api')->user()->token()->revoke();
    }
    // خروج کاربر از سیستم

    public function index(Request $request ) //جستجو و صفحه بندی کاربران
    {
        $perPage=$request->per_page;
        return response()->json(['user'=>new UserCollection(User::paginate($perPage))],200);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user=new User(
            [
            'name' => $request->name,'email'=>$request->email,'password'=>bcrypt('12345678')]
            );
        $user->save();
        $user->profiles()->save(new Profile());
        return response()->json(['user'=>new UserResource($user)],200);
    }
    public function register(Request $request)
    {
        $rules = [
            'name'  => 'required|string',
            'password'  => 'required|min:8',
            'email' => 'required|email|unique:users',
        ];
        $message = [
            'name.required'  => 'ورود نام الزامی است !',
            'name.string'  => 'نام باید به صورت متن باشد ورود اعداد غیر مجاز است!',
            'password.required'  => 'پسورد را وارد کنید',
            'password.min'  => 'حداثل طول پسورد باید 8 رقم باشد ',
            'email.required' => 'ورود ایمیل الزامی است!',
            'email.email' => 'فرمت ایمیل صحیح نیست !',
            'email.unique' => 'ایمیل تکراری است !',
        ];
        $error = Validator::make($request->all(), $rules,$message);
        if($error->fails())
            return response()->json(['status' => $error->errors()->first()]);
        $user=new User(
            [
                'name' => $request->name,'email'=>$request->email,'password'=>bcrypt($request->password)]
        );
        if($user->save()){
            $user->profiles()->save(new Profile());
            return response()->json(['status'=>'با موفقیت ثبت نام کردید ! هم اکنون میتوانید برای ورود اقدام کنید!'],200);
        }

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $user=User::where('name','like',"%$id%")->paginate();
        return response()->json(['user'=>$user],200);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user=User::find($id);
        $user->name=$request->name;
        $user->email=$request->email;
        $user->save();
        return response()->json(['user'=>new UserResource($user)],200);
    }
    public function verify(Request $request)
    {
        return $request->only('name','email');
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if((User::destroy($id))&&(Profile::where('user_id',$id)->delete())){
            return response()->json(['state'=>'deleted'],200);
        }
    }

    public function deleteAll(Request $request)
    {
        if (empty($request->users))
            return response()->json(['state'=>'آیتمی برای حذف موجود نیست!'],'403');

        if(User::whereIn('id',$request->users)->delete()){
            if(Profile::whereIn('user_id',$request->users)->delete()){
                return response()->json(['state','ok'],200);
            }
        }
    }

    public function verifyEmail(Request $request)
    {
         $request->validate([
             'email'=>'unique:users',
         ]);
        return response()->json(['msg','این ایمیل قابل ثبت نیست'],200);
    }



    public function updatePhoto(Request $request)
    {
           $user=User::find($request->user); //get user id => 3
           $profile=Profile::where('user_id',$user->id)->first(); //get profile of user
           $ext=$request->photo->extension();
           $photo=$request->photo->storeAs('profiles',Str::random(20).".{$ext}",'public');
           $profile->photo_path=$photo;
           $user->profiles()->save($profile);
           $isAdmin=$user->id===$request->user()->id?true:false;
           return response()->json(['user'=>new UserResource($user),'isAdmin'=>$isAdmin],200);
    }
    public function getProfile(Request $request)
    {
        $loggedInUser=$request->user()->first();
        return response()->json(['user'=>new UserResource($loggedInUser)],200) ;
    }
    public function mobileVerify(Request $request)
    {
        //اگر موبایل کاربر قلا تایید نشده بود --------------------------------------
        $user=User::find(\auth('api')->user()->id);
        if($user->first()->mobile_verified_at)
            return response()->json(['msg'=>'کاربر قبلا احراز هویت را انجام داده است'],200);
        // اگر احراز هویت نشده بود چک کن ببین قبلا احراز هویت را انجام داده یا نه
        $lastUserToken = MobileToken::where('user_id',$user->id)->first();
        if(($lastUserToken )&&($lastUserToken->first()->revoke!=1) )
        {//اگر زمانش نگذشته بود چک کن ببین کد وارد شده با توکن برابره یا نه اگه برابر هست موبایل رو وریفای کن
            if($lastUserToken->first()->expired <Carbon::now())
            {
                $lastUserToken->revoke=1;
                $lastUserToken->save();
                return response()->json(['msg'=>'کد منقضی شده است!'],200);
            }
            if ($lastUserToken->token==$request->code)
            {
                $upateUserVerify=User::find($user->id);
                $upateUserVerify->mobile_verified_at=now();
                $upateUserVerify->save();
                $msg='حساب شما با موفقیت تایید شد';
            }
            else{
                $msg= 'کد اشتباه میباشد ';
            }
            $lastUserToken->revoke=1;
            $lastUserToken->save();
            return response()->json(['msg'=>$msg],200);
        }
        else{
            $msg='کد به تلفن همراه شما ارسال شد ! 60 ثانیه فرصت دارید تا حساب کاربری خود را فعال نمایید!';
            //اگر قبلا احراز هویت را انجام نداده بود
            $randomToken=rand(1059,4506);
            $token=new MobileToken();
            $token->user_id=auth('api')->user()->id;
            $token->created=now();
            $token->expired=now()->addSeconds(60);
            $token->token=$randomToken;
            $token->save();
            return response()->json(['msg'=>$msg],200);

        }
    }




}
