<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\UserCollection;
use App\Profile;
use App\Role;
use Illuminate\Http\Request;
use App\User;
use App\Http\Resources\User as UserResource;
class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request )
    {
        $perPage=$request->per_page;
        $roles=Role::all()->pluck('name');
        return response()->json(['user'=>new UserCollection(User::paginate($perPage)),'roles'=>$roles],200);
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
        $role=Role::where('name',$request->role)->first(); //get roleName of user
        $user=new User(
            [
            'name' => $request->name,'email'=>$request->email,'password'=>bcrypt('12345678')]
            );
        $user->roles()->associate($role);
        $user->save();
        $user->profiles()->save(new Profile());
        return response()->json(['user'=>new UserResource($user)],200);
    }

    public function verify(Request $request)
    {
       return $request->only('name','email');
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
        $role=Role::where('name',$request->role)->first(); //get roleName of user
        $user->roles()->dissociate($user->roles); //remove current role
        $user->roles()->associate($role); //add new role id
        $user->save();
        return response()->json(['user'=>new UserResource($user)],200);
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
}
