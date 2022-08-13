<?php

namespace App\Http\Controllers;

use App\Models\Image;
use Nette\Utils\Html;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ImageController extends Controller
{
    public function index(){

    }

    public function create()
    {
        return view('images.create');
    }

    public function show(Image $image)
    {
        // return Storage::disk('s3')->response('images/'.$image->filename);
        $data = Storage::disk('s3')->url('images/profile/911/13dZOgoN7SCK5dSTTMRT0hR9is1GIZVQJgbFW0I0.jpg');
        $keyExists = @fopen($data, 'r');
        if($keyExists){
            return Storage::disk('s3')->response('images/profile/911/13dZOgoN7SCK5dSTTMRT0hR9is1GIZVQJgbFW0I0.jpg');
        }else{
            dd('wkwkwk');
        }
    }

    public function store(Request $request)
    {
        $path = $request->file('image')->store('images','s3');

        Storage::disk('s3')->setVisibility($path, 'public');
        // Storage::disk('s3')->setVisibility($path, 'private');
        
        $image = Image::create([
            'filename'=>basename($path),
            'path'=>$path,
            'url'=>Storage::disk('s3')->url($path)
        ]);

        return $image;
    }
    public function delete(Image $image){
        // $data = Storage::disk('s3')->delete($image->path);
        $data = Storage::disk('s3')->delete('images/'.$image->filename);
        Image::find($image->id)->delete();
        return redirect('/');
    }
}
