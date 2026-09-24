<?php

namespace App\Services;

use Illuminate\Http\UploadedFile;

class ImageService
{
    public function uploadAvatar(UploadedFile $file)
    {
        $avatarsPath = public_path('avatars');
        if (!file_exists($avatarsPath)) {
            mkdir($avatarsPath, 0777, true);
        }

        $filename = uniqid() . '.' . $file->getClientOriginalExtension();
        $file->move($avatarsPath, $filename);

        return '/avatars/' . $filename;
    }

    public function deleteAvatar($path)
    {
        $fullPath = public_path(ltrim($path, '/'));
        if (file_exists($fullPath)) {
            unlink($fullPath);
        }
    }
}