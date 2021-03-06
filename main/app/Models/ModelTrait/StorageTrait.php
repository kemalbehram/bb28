<?php
namespace App\Models\ModelTrait;

use Illuminate\Support\Facades\Storage;

trait StorageTrait
{
    public static function getImagePath($image)
    {
        $url   = Storage::url('/');
        $image = str_replace($url, '/', $image);
        if (strstr($image, '?')) {
            $image = substr($image, 0, strpos($image, '?'));
        }
        return $image;
    }

    public static function getOssImageStyle($image, $style = 'max')
    {
        if (!$image || strstr($image, 'http')) {
            return $image;
        }

        $str = '';
        if ($style !== 'full') {
            $str = '?x-oss-process=style/' . $style;
        }

        return Storage::url($image) . $str;
    }
}
