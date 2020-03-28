<?php

namespace App\Http\Controllers\Api;

use App\Demand;
use App\Http\Controllers\Controller;
use App\Http\Resources\DemandCollection;
use App\Http\Resources\UserCollection;
use Illuminate\Http\Request;

class DemandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request ) //جستجو و صفحه بندی مطالبه ها
    {
        $perPage = $request->per_page;
        $demands = new DemandCollection(Demand::with(['replies','users'])->paginate($perPage));
        return response()->json(['demand'=>$demands],200);

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
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
//        $user=User::where('name','like',"%$id%")->paginate();
//        return response()->json(['user'=>$user],200);
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
    public function deleteAll(Request $request)
    {
//        if (empty($request->users))
//            return response()->json(['state'=>'آیتمی برای حذف موجود نیست!'],'403');
//
//        if(User::whereIn('id',$request->users)->delete()){
//            if(Profile::whereIn('user_id',$request->users)->delete()){
//                return response()->json(['state','ok'],200);
//            }
//        }
    }
}
