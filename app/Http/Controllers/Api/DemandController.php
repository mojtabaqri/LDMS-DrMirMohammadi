<?php

namespace App\Http\Controllers\Api;

use App\Demand;
use App\File;
use App\Http\Controllers\Controller;
use App\Http\Resources\DemandCollection;
use App\Http\Resources\DemandResource;
use App\Http\Resources\singleDemandResource;
use App\Http\Resources\UserCollection;
use App\Reply;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use function GuzzleHttp\Promise\all;

class DemandController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request ) //صفحه بندی مطالبه ها
    {
        $perPage = $request->per_page;
        $demands = new DemandCollection(Demand::with(['replies','users'])->paginate($perPage));
        return response()->json(['demand'=>$demands],200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     * @throws \Illuminate\Validation\ValidationException
     */
    public function store(Request $request)
    {
        // دریافت دایرکتوری مطالبه مربوطه :  $demand=Demand::find(72)->files->first()->file_directoryس
        //{"title":"this is test title","demandContent":"this is test content "} send as form-data request
        //------------------------------------------- Valid Uploaded File ---------------------------------
        $rules = [
            'file'  => 'required',
            'file.*' => 'required|file|mimes:doc,pdf,docx,zip,jpg,jpeg,rar',
        ];
        $error = Validator::make($request->all(), $rules);
        if($error->fails())
            return response()->json(['errors' => $error->errors()->all()]);
        //-------------------------------------------- Valid Uploaded File -------------------------------
        $request->data=json_decode($request->data); //دریافت به صورت جیسون و تبدیل به شی
        $demand=new Demand(['title' => $request->data->title,'tracking'=>rand(38722,102266).rand(1321,2163),'content'=>$request->data->demandContent,'user_id'=>auth('api')->user()->id]);
        $path='demands/'.$demand->id.'/files/';
        if($demand->save()) //اگر درخواست در دیتابیس قبت شد
        {
            //----------------------------File Upload Scope---------------------------------------
            if($request->hasfile('file'))
            {
                $path='demands/'.$demand->id.'/files/';
                foreach($request->file('file') as $file)
                {
                    $filename=$file->getClientOriginalName();
                    Storage::putFileAs($path,$file,$filename);
                }
                    $demand->file_directory=$path;
                    $demand->save();
            }
            //----------------------------File Upload Scope---------------------------------------
            return response()->json(['tracking'=>$demand->tracking],200);
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
      $demand=Demand::where('title','like',"%$id%")->paginate();
      return response()->json(['demand'=>$demand],200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id) //متد پاسخ به مطالبه توسط ادمین
    {
        //فقط کافیه از طریق متد Put شماره مطالبه و متن پاسخ ادمین رو ارسال کنین !
        //متد پاسخ دادن به مطالبه
        $reply=$request->reply;
        $demand=Demand::find($id);
        if($demand)
        {
            $demand->replies->text=$reply;
            $demand->replies->admin_id=auth('api')->user()->id;
            $demand->replies()->save($demand->replies);
            $demand->updated_at=now();
            $demand->save();
            return response()->json(['demand'=>new DemandResource($demand)],200);
        }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if(Demand::destroy($id)||Reply::where('demand_id',$id)->delete())
            return response()->json(['state'=>'ok'],200);
        return response()->json(['state'=>'حذف ناموفق بود'],403);

    }
    public function deleteAll(Request $request)
    {
    // get All Demands Id that we want to remove them from database
        if (empty($request->demands))
            return response()->json(['state'=>'آیتمی برای حذف موجود نیست!'],'403');
        if(Demand::whereIn('id',$request->demands)->delete()){
            Reply::whereIn('demand_id',$request->demands)->delete();
            return response()->json(['state'=>'ok'],200);
        }
        return response()->json(['state'=>'حذف ناموفق بود'],403);
    }
    public function trackingDemand($id){
        $demand=Demand::where('tracking',$id)->first();
        if($demand)
            return response()->json(['demand'=>new DemandResource($demand)],200);
        return response()->json(['state'=>'یافت نشد!'],200);
    }

    public function singleDemand($id) // Show Single Demand with files
    {
        $demand=Demand::find($id);
        if($demand){
            return new singleDemandResource($demand->with(['replies','users']));
        }
     }
}

