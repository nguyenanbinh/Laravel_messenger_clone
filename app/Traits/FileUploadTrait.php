<?php

namespace App\Traits;

use Illuminate\Http\Request;

trait FileUploadTrait {

    function uploadFile(Request $request, string $inputName, ?string $oldPath = null, string $path = '/uploads'): string | null {
        if($request->hasFile($inputName)) {
            $file = $request->file($inputName);
            $ext = $file->getClientOriginalExtension();
            $fileName = 'media_'. uniqid() . '.' . $ext;
            $file->move(public_path($path), $fileName);

            return $path . '/' . $fileName;
        }
        return null;
    }
}
