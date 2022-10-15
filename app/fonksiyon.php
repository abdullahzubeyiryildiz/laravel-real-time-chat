<?php


if (!function_exists('dosyasil')) {
    function dosyasil($dosyayol)
    {

        if (\Illuminate\Support\Facades\Storage::disk('public')->exists($dosyayol)) {
            \Illuminate\Support\Facades\Storage::disk('public')->delete($dosyayol);
        }

    }
}

if (!function_exists('klasorac')) {
    function klasorac($dosyayol)
    {

        if(!\Illuminate\Support\Facades\Storage::disk('public')->exists($dosyayol)) {
            \Illuminate\Support\Facades\Storage::disk('public')->makeDirectory($dosyayol, 0777, true, true);
        }
    }
}

if (!function_exists('uploadimage')) {
    function uploadimage($image,$pathyol,$paththumb,$with=NULL,$height=NULL)
    {

        $fullName = $image->getClientOriginalName();
        $extension = $image->getClientOriginalExtension();
        $onlyName = implode('', explode('.' . $extension, $fullName));
        $filename =  Str::slug($onlyName) . '-' . time(); //generateOTP(6).'-'.time();

        if ($image->extension() == 'svg' || $image->extension() == 'webp' || $image->extension() == 'pdf') {
            $orjinalurl = $pathyol . $filename . '.' . $image->extension();


            \Illuminate\Support\Facades\Storage::disk('public')->putFileAs('',$image->path(), $orjinalurl);

            $imagear['orj'] = $orjinalurl;

            if(!empty($paththumb)) {
                $thumbnailurl = $orjinalurl;
                $imagear['thum'] = $thumbnailurl;
            }else {
                $imagear['thum'] = NULL;
            }

        } else {
            $orjinalurl = $pathyol . $filename . '.webp';
            \Illuminate\Support\Facades\Storage::disk('public')->put($orjinalurl, \ImageResize::make($image->path())->encode('webp', 90));

            $imagear['orj'] = $orjinalurl;

            if(!empty($paththumb)) {

                $thumbnailurl = $paththumb . 'thumb_' . $filename . '.webp';
                \Illuminate\Support\Facades\Storage::disk('public')->put($thumbnailurl, \ImageResize::make($image->path())
                ->resize($with, $height, function ($constraint) {$constraint->aspectRatio();})
                ->encode('webp', 90));

                $imagear['thum'] = $thumbnailurl;
            }else {
                $imagear['thum'] = NULL;
            }

        }

        return  $imagear;
    }
}

