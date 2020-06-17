<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Intervention\Image\Facades\Image;
use DOMDocument;
use Countries;
use Illuminate\Support\Str;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    protected function getCountries()
    {
        $countries = Countries::lookup('it')->reject(function($country, $key) {
            return in_array($key, config('app.rejected_countries'));
        });

        return $countries;
    }

    protected function getPath() : string
    {
        $path = date('Y').'/'.date('m').'/';
        if (!Storage::disk('public')->exists($path)) {
            File::makeDirectory(public_path('storage/'.$path), 0777, true);
        }

        return $path;
    }

    protected function saveFile($file, $nome_file) : string
    {
        $path = $this->getPath();
        $extension = $file->getClientOriginalExtension() ?: 'png';
        $nome = Str::slug($nome_file).'_'.uniqid().'.'.$extension;
        $file->storeAs($path, $nome, 'public');
        return '/storage/'.$path.$nome;
    }

    protected function saveContentImage($message) : string
    {
        $path = $this->getPath();

        $dom = new DomDocument();
        $internalErrors = libxml_use_internal_errors(true);

        $dom->loadHtml(mb_convert_encoding('<html>'.$message.'<html>', 'HTML-ENTITIES', 'UTF-8'), LIBXML_HTML_NODEFDTD | LIBXML_NOXMLDECL | LIBXML_HTML_NOIMPLIED);
        libxml_use_internal_errors($internalErrors);
        $images = $dom->getElementsByTagName('img');
        foreach($images as $img){
            $src = $img->getAttribute('src');
            if(preg_match('/data:image/', $src)){
                preg_match('/data:image\/(?<mime>.*?)\;/', $src, $groups);
                $mimetype = $groups['mime'];
                $filename = uniqid();
                $filepath = 'public/'.'storage/'.$path.$filename.'.'.$mimetype;
                $image = Image::make($src)
                    ->encode($mimetype, 100)  // encode file to the specified mimetype
                    ->save(base_path($filepath));
                $new_src = '/storage/'.$path.$filename.'.'.$mimetype;
                $img->removeAttribute('src');
                $img->setAttribute('src', $new_src);
            }
        }

        return str_replace(array('<html>','</html>') , '' , $dom->saveHTML());
    }
    protected function makeImage($file)
    {
        return Image::make($file);
    }

    protected function cropImage($image, $w, $h, $x, $y)
    {
        return $image->crop($w,$h,$x,$y);
    }

    protected function saveImage($image, $file_name, $original_name, $widen, $is_thumb = false, $dafault_path = false) : string
    {
        if($dafault_path){
            $path = '';
        }else{
            $path = $this->getPath();
        }
        if(!$widen){
            $image->save(base_path('public/storage/'.$path.$file_name));
            return '/storage/'.$path.$file_name;
        }

        if($image->width() > $widen){
            $image->widen($widen)->save(base_path('public/storage/'.$path.$file_name));
            return '/storage/'.$path.$file_name;
        } else {
            if($is_thumb){
                $image->save(base_path('public/storage/'.$path.$file_name));
                return '/storage/'.$path.$file_name;
            }
            return '/storage/'.$path.$original_name;
        }
    }

    protected function updateImage($path, $image, $url, $widen)
    {
        if ($image->width() > $widen) {
            $image->widen($widen);
        }

        $image->save(base_path('public/'.$url));
    }

    protected function deleteFiles($files)
    {
        if(is_array($files)){
            foreach ($files as $file) {
                if($file && File::exists(public_path($file))){
                    File::delete(public_path($file));
                    // File::delete(public_path(strrchr($filename, 'storage')));
                }
            }
        }else{
            if($files && File::exists(public_path($files))){
                File::delete(public_path($files));
            }
        }
    }
}
