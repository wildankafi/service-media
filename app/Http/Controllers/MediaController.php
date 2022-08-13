<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use App\Models\Media;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class MediaController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function getAll(Request $request) {
        $media = Media::all();
        return response()->json($media);
    }
    public function create(Request $request) {
        
        $this->validate($request,[
            "media" => "required"
        ]);
        if (!$request->appname) {
            $folderPath = "";
        }else {
            $folderPath = $request->appname."/";
        } 
        $img = $request->media;
        if(!File::isDirectory($folderPath)){
            File::makeDirectory($folderPath, 0777, true, true);
        }
        $image_parts = explode(";base64,", $img);
        $image_type_aux = explode("image/", $image_parts[0]);
        $image_type = $image_type_aux[1];
        $image_base64 = base64_decode($image_parts[1]);
        $strrandom =\Illuminate\Support\Str::random(32);
        $uniqid = uniqid().''.$strrandom;
        $file = $folderPath . $uniqid . '.'.$image_type;
        file_put_contents($file, $image_base64);
        $media = New Media();
        $media -> images = $file;
        $media -> app =  $request->appname;
        $media -> type = $request->mediatype;
        $media -> iddata = $request->dataid;
        $media -> save();
        $data = Media::find($media->id);
        $res = [
            'code' => http_response_code(), 
            'message' => 'Success',
            'data' => [
                "media" => url('') . "/" . $data->images,
                "appname" => $data->app,
                "mediatype" => $data->type,
                "test" => $uniqid
            ]
        ];
        return response()->json($res);
    }

    public function getById($id){
        $media = Media::find($id);
        if(!$media){
            $media = [
                "Message" => "Data tidak tersedia"
            ];
        }
        $res = [
            'code' => http_response_code(), 
            'message' => 'Success',
            'data' => $media
        ];
        return response()->json($res);
    }
    public function getByApp(Request $request){
        $input = $request->app;
        $media = Media::where('app', $input)
        ->orderByDesc('id')->get(); 
        if(!$media){
            $media = [
                "Message" => "Data tidak tersedia"
            ];
        }
        $res = [
            'code' => http_response_code(), 
            'message' => 'Success',
            'data' => $media
        ];
        return response()->json($res);
    }

    public function getByData(Request $request){
        $input = $request->dataid;
        $media = Media::where('iddata', $input)
        ->orderByDesc('id')->get(); 
        if(!$media){
            $media = [
                "Message" => "Data tidak tersedia"
            ];
        }
        return response()->json($media);
    }

    public function destroy($id)
    {
        $media = Media::find($id);
        
        if (!$media) {
            return response()->json(['message' => 'Data not found'], 404);
        }

        $media->delete();

        return response()->json(['message' => 'Data deleted successfully'], 200);
    }

   
}
