<?php

namespace App\Http\Controllers;

use App\Image;
use App\ImagePart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageUploadController extends Controller
{



    public function uploadAndDivideImage(Request $request){

        if($request->has('file')){

            $filename = $request->file->getClientOriginalName();

            $request->file->storeAs('public/upload', $filename);

            $image = new Image();

            $image->image = $filename;

            $image->save();

            $storagePath = Storage::disk('local')->getDriver()->getAdapter()->getPathPrefix();

            $file = $storagePath."/public/upload/".$filename;
            $info = getimagesize($file);

            $width=$info[0];
            $height=$info[1];


            $orig = imagecreatefromjpeg($file);

            $firstPart = imagecreatetruecolor($width/4, $height);
            $secondPart = imagecreatetruecolor($width/4, $height);
            $thirdPart = imagecreatetruecolor($width/4, $height);
            $fourthPart = imagecreatetruecolor($width/4, $height);

            $image_names = array();

            imagecopy($firstPart, $orig, 0, 0, 0, 0, $width/4, $height);
            imagecopy($secondPart, $orig, 0, 0, $width/4, 0, $width/4, $height);
            imagecopy($thirdPart, $orig, 0, 0, $width/2, 0, $width/4, $height);
            imagecopy($fourthPart, $orig, 0, 0, 3*$width/4, 0, $width/4, $height);
            $filename1 = $this->generateRandomString();
            $filename2 = $this->generateRandomString();
            $filename3 = $this->generateRandomString();
            $filename4 = $this->generateRandomString();
            imagejpeg($firstPart, $storagePath."/public/upload/".$filename1.'.jpg');
            imagejpeg($secondPart, $storagePath."/public/upload/".$filename2.'.jpg');
            imagejpeg($thirdPart, $storagePath."/public/upload/".$filename3.'.jpg');
            imagejpeg($fourthPart, $storagePath."/public/upload/".$filename4.'.jpg');
            $image_names[] = $filename1.".jpg";
            $image_names[] = $filename2.".jpg";
            $image_names[] = $filename3.".jpg";
            $image_names[] = $filename4.".jpg";


            for($i = 0; $i< count($image_names); $i++){
                $part = new ImagePart;
                $part->image_id = $image->id;
                $part->name = $image_names[$i];
                $part->save();
            }

        }
    }

    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }


    function getImagePart($id){

        $image_part = ImagePart::find($id);

        if($image_part){
            $path = url('storage/upload/'.$image_part->name);
            return response()->json([
                'id' => $image_part->id,
                'image' => $path
            ]);
        }
        else{
            return "Image does not exist";
        }
    }
}
