<?php

namespace App\Http\Controllers\Api;

use App\File;
use App\Http\Controllers\Controller;
use App\Http\Resources\ReportCollection;
use App\Report;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $perPage = $request->per_page;
        $reports = new ReportCollection(Report::with('users')->paginate($perPage));
        return response()->json(['report'=>$reports],200);
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
        $rules = [
            'file'  => 'required',
            'file.*' => 'required|file|mimes:doc,pdf,docx,zip,jpg,jpeg,rar',
        ];
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
            return response()->json(['errors' => $error->errors()->all()]);
        //-------------------------------------------- Valid Uploaded File -------------------------------
        $request->data=json_decode($request->data); //دریافت به صورت جیسون و تبدیل به شی
        $report=new Report(['title' => $request->data->title,'text'=>$request->data->text,'user_id'=>auth('api')->user()->id]);
        if($report->save()) //اگر درخواست در دیتابیس قبت شد
        {
            $path='reports/'.$report->id.'/files/';
            //----------------------------File Upload Scope---------------------------------------
            if($request->hasfile('file'))
            {
                foreach($request->file('file') as $file)
                {
                    $filename=$file->getClientOriginalName();
                    Storage::putFileAs($path,$file,$filename);
                }
                $report->file_directory=$path;
                $report->save();
            }
            //----------------------------File Upload Scope---------------------------------------
            return response()->json(['state'=>'success'],200);
        }
        return response()->json(['state'=>'false']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $report=Report::where('title','like',"%$id%")->paginate();
        return response()->json(['report'=>$report],200);
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

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Report::destroy($id))
            return response()->json(['state'=>'با موفقیت حذف شد'],200);
        return response()->json(['state'=>'حذف ناموفق بود'],403);
    }
    public function deleteAll(Request $request)
    {
        // get All Demands Id that we want to remove them from database
        if (empty($request->reports))
            return response()->json(['state'=>'آیتمی برای حذف موجود نیست!'],'403');
        if(Report::whereIn('id',$request->reports)->delete())
            return response()->json(['state'=>'ok'],200);
        return response()->json(['state'=>'حذف ناموفق بود'],403);
    }}
