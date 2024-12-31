<?php

namespace App\Http\Controllers\v1;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\MediaImage;
use App\Models\MediaImageDecode;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;

class MediaImgController extends Controller
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
    public function create(Request $request) {
        $image = $request->file('image');
        $imageData = file_get_contents($image->getRealPath());
        $mimeType = $image->getClientmimeType();
        dd($mimeType);
        // Encode image data with base64 encoding
        if ($imageData !== false && $mimeType !== false) {
            $base64Image = base64_encode($imageData);
            // dd($base64Image);
            $strrandom =\Illuminate\Support\Str::random(32);
            $uniqid = uniqid().''.$strrandom;
            $mediaimage = New MediaImage();
            $mediaimage -> images = $base64Image;
            $mediaimage -> app =  $request->appname;
            $mediaimage -> type = $request->mediatype;
            $mediaimage -> iddata = $request->dataid;
            $mediaimage -> mimetype = "data:$mimeType;base64,";
            $mediaimage -> uniqkey = $uniqid;
            $mediaimage -> save();
            $datamediaimage = MediaImage::find($mediaimage->id);
            $mediaimagedecode = new MediaImageDecode();
            $mediaimagedecode -> uniqkey = $datamediaimage->uniqkey;
            $mediaimagedecode -> isDecode =  $request->decode;
            $mediaimagedecode ->save();
            
            
            if ($mediaimagedecode -> isDecode == 1) {
                if (!$request->appname ) {
                    $folderPath = "";
                }else {
                    $folderPath = $request->appname."/";
                } 
                if(!File::isDirectory($folderPath)){
                    File::makeDirectory($folderPath, 0777, true, true);
                }
                $image_parts = explode(";base64,", $mediaimage -> mimetype.$mediaimage -> images);
                $image_type_aux = explode("image/", $image_parts[0]);
                $image_type = $image_type_aux[1];
                $image_base64 = base64_decode($image_parts[1]);
                $file = $folderPath . $uniqid . '.'.$image_type;
                file_put_contents($file, $image_base64);
                $media = New Media();
                $media -> images = $file;
                $media -> app =  $request->appname;
                $media -> type = $request->mediatype;
                $media -> iddata = $request->dataid;
                $media -> save();
                $MediaImageUpdate = MediaImageDecode::find($mediaimagedecode->id);           
                $MediaImageUpdate->status = 1;       
                $MediaImageUpdate->idmedia = $media->id;
                $MediaImageUpdate->save();
                $data = Media::find($media->id);
                $response = [
                    'code' => http_response_code(), 
                    'message' => 'Success',
                    'data' => [
                        "media" => url('') . "/" . $data->images,
                        "appname" => $data->app,
                        "mediatype" => $data->type
                    ]
                ];
            } else {
                $response = [
                    'code' => http_response_code(), 
                    'message' => 'Success',
                    'data' => [
                        "media" => url('') . "/v1/file/" . $mediaimage->uniqkey . "/view",
                        "appname" => $mediaimage->app,
                        "mediatype" => $mediaimage->type
                    ]
                ];
            }
            // $response = [
            //     'code' => http_response_code(), 
            //     'message' => 'Success',
            //     'data' => [
            //         "media" => url('') . "/v1/file/" . $mediaimage->uniqkey,
            //         "appname" => $mediaimage->app,
            //         "mediatype" => $mediaimage->type
            //     ]
            // ];
            return response()->json($response, 200);
        } else {
            return response()->json(['error' => 'Error converting image to base64'], 500);
        }
    }

    public function read($param) {
        $mediaimage = MediaImage::where('uniqkey',$param)->where('rowstatus',0)->select('images','mimetype')->first();
        if (!$mediaimage) {
            $media = [
                    'code' => 404,
                    'message' => 'Data not found',
            ];
            return response()->json($media, 404);
        }
        $base64Image = $mediaimage['images'];
        $image_type = $mediaimage['mimetype'];
        $data = $image_type . $base64Image;
        return $data;
    }
    public function view($param) {
        $mediaimage = MediaImage::where('uniqkey',$param)->where('rowstatus',0)->select('images','mimetype')->first();
        if (!$mediaimage) {
            $media = [
                    'code' => 404,
                    'message' => 'Data not found',
            ];
            return response()->json($media, 404);
        }
        $base64Image = $mediaimage['images'];
        $image_type = $mediaimage['mimetype'];
        $data = $image_type . $base64Image;
        return "<img src='$data'>";
    }
    public function createbase64(Request $request) {
        
        $this->validate($request,[
            "media" => "required"
        ]);
        // $img = $request->media;
        dd(base64_decode($request->media));
        $imagebase64 = explode(";base64,", $request->media);
        $strrandom =\Illuminate\Support\Str::random(32);
        $uniqid = uniqid().''.$strrandom;
        // Save On Base64
        $mediaimage = New MediaImage();
        $mediaimage -> images = $imagebase64[1];
        $mediaimage -> app =  $request->appname;
        $mediaimage -> type = $request->mediatype;
        $mediaimage -> iddata = $request->dataid;
        $mediaimage -> mimetype = $imagebase64[0].";base64,";
        $mediaimage -> uniqkey = $uniqid;
        $mediaimage -> save();
        // End Save Base64
        $datamediaimage = MediaImage::find($mediaimage->id);
        $mediaimagedecode = new MediaImageDecode();
        $mediaimagedecode -> uniqkey = $datamediaimage->uniqkey;
        $mediaimagedecode -> isDecode =  $request->decode;
        $mediaimagedecode ->save();
        
        
        if ($mediaimagedecode -> isDecode == 1) {
            if (!$request->appname ) {
                $folderPath = "";
            }else {
                $folderPath = $request->appname."/";
            } 
            if(!File::isDirectory($folderPath)){
                File::makeDirectory($folderPath, 0777, true, true);
            }
            $image_parts = explode(";base64,", $mediaimage -> mimetype.$mediaimage -> images);
            $image_type_aux = explode("image/", $image_parts[0]);
            $image_type = $image_type_aux[1];
            $image_base64 = base64_decode($image_parts[1]);
            $file = $folderPath . $uniqid . '.'.$image_type;
            file_put_contents($file, $image_base64);
            $media = New Media();
            $media -> images = $file;
            $media -> app =  $request->appname;
            $media -> type = $request->mediatype;
            $media -> iddata = $request->dataid;
            $media -> save();
            $MediaImageUpdate = MediaImageDecode::find($mediaimagedecode->id);           
            $MediaImageUpdate->status = 1;       
            $MediaImageUpdate->idmedia = $media->id;
            $MediaImageUpdate->save();
            $data = Media::find($media->id);
            $response = [
                'code' => http_response_code(), 
                'message' => 'Success',
                'data' => [
                    "media" => url('') . "/" . $data->images,
                    "appname" => $data->app,
                    "mediatype" => $data->type
                ]
            ];
        } else {
            $response = [
                'code' => http_response_code(), 
                'message' => 'Success',
                'data' => [
                    "media" => url('') . "/v1/file/" . $mediaimage->uniqkey. "/view",
                    "appname" => $mediaimage->app,
                    "mediatype" => $mediaimage->type
                ]
            ];
        }
        
        return response()->json($response);
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
            return response()->json(['message' => 'Data not found'], 404);
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
