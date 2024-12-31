<?php

namespace App\Http\Controllers\v2;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Media;
use App\Models\MediaImage;
use App\Models\MediaImageDecode;
use App\Models\QueueDelete;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
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
    
    public function createbase64(Request $request) {
        
        $this->validate($request,[
            "media" => "required"
        ]);
        $imagebase64 = $request->media;
        $finfo = finfo_open(FILEINFO_MIME_TYPE);
        $decodedString = base64_decode($imagebase64);
        $mimeType = finfo_buffer($finfo, $decodedString);

        $strrandom =\Illuminate\Support\Str::random(32);
        $uniqid = uniqid().''.$strrandom;
        // Save On Base64
        $mediaimage = New MediaImage();
        $mediaimage -> images = $imagebase64;
        $mediaimage -> app =  $request->appname;
        $mediaimage -> type = $request->mediatype;
        $mediaimage -> iddata = $request->dataid;
        $mediaimage -> mimetype = $mimeType.";base64,";
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

    public function RequestDelete(Request $request) {
        $url = $request->link;
        $parts = explode('/', $url);
        $queuedelete = New QueueDelete();
        $queuedelete -> requesparam =  $request->link;
        $queuedelete -> app = $request->appname;
        $queuedelete -> type =  isset($parts[4]) ? pathinfo($parts[4], PATHINFO_EXTENSION) : '';
        $queuedelete -> folder = isset($parts[3]) ? $parts[3] : '';
        $queuedelete -> uniqkey = isset($parts[4]) ? pathinfo($parts[4], PATHINFO_FILENAME) : '';
        $queuedelete -> save();
        $response = [
            'code' => http_response_code(), 
            'message' => 'Success',
            'data' => [
                "media" => $queuedelete->requesparam,
                "info" => "Request Delete",
            ]
        ];
        return response()->json($response);
    }

    public function DeleteQueue(Request $request) {
        if ($request->orderby !== 'asc' && $request->orderby !== 'desc') {
            return response()->json([
                'code' => 422, 
                'message' => 'Failed',
                'data' => [
                    "info" => "Format orderby salah. Harap gunakan asc atau desc",
                ]
            ], 422);
        } if (!is_numeric($request->limit) || $request->limit <= 0) {
            return response()->json([
                'code' => 422, 
                'message' => 'Failed',
                'data' => [
                    "info" => "Format limit salah. Harap gunakan integer atau number",
                ]
            ], 422);
        } 
        $filedelete =  QueueDelete::where('status', 0)
                        ->whereIn('is_prosess', [0, ''])
                        ->whereNotNull('is_prosess')
                        ->orderBy('id', $request->orderby)
                        ->take($request->limit)
                        ->get();
        foreach ($filedelete as $fd) {
            $path = $fd['folder'].'/' . $fd['uniqkey'] . '.' . $fd['type'];
            $filePath = public_path($path);
            if (file_exists($filePath)) {
                unlink($filePath);
                $queuedelete = QueueDelete::find($fd['id']);
                $queuedelete->status = 1;
                $queuedelete->is_prosess =1;
                $queuedelete->message = 'File ditemukan';
                $queuedelete->save();
            } else {
                $queuedelete = QueueDelete::find($fd['id']);
                $queuedelete->status = 0;
                $queuedelete->is_prosess =1;
                $queuedelete->message = 'File tidak ditemukan';
                $queuedelete->save();
            }
        }
        $response = [
            'code' => http_response_code(), 
            'message' => 'Success',
            'data' => [
                "info" => $filedelete->count() . " Delete Queue",
            ]
        ];
        
        return response()->json($response);
    }
}
