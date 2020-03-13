<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Role;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage=$request->per_page;
        return response()->json(['role'=>Role::paginate($perPage)],200);
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
        $role = Role::updateOrCreate(['name' => $request->name,'id'=>$request->id]);
        return response()->json(['role'=>$role],200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $role=Role::where('name','like',"%$id%")->paginate();
        return response()->json(['role'=>$role],200);
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
        $role=Role::find($id);
        $role->name=$request->name;
        $role->id=$request->id;
        $role->save();
        return response()->json(['role'=>$role],200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Role::destroy($id))
            return response()->json(['state'=>'deleted'],200);
    }
    public function deleteAll(Request $request)
    {
        if (empty($request->roles))
            return response()->json(['state'=>'نقشی برای حذف موجود نیست!'],'403');

        if(Role::whereIn('id',$request->roles)->delete())
            return response()->json(['state','ok'],200);
    }
}
