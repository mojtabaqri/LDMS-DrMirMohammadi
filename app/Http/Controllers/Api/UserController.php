<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Profile;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\User as UserResource;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

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

    public function index(Request $request )
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

}
