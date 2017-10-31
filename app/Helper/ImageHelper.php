<?php

namespace App\Helper;

use Illuminate\Http\Request;

class ImageHelper
{
    public static function deleteImage($image, $type = 'avatar')
    {
        $path = '';
        $fileName = substr($image, -24);
        switch ($type){
            case "avatar":
                $path = base_path('public') . '/storage/avatars/' . $fileName;
            case "cover":
                $path = base_path('public') . '/storage/covers/' . $fileName;
            default:
        }
        if (file_exists($path)) {
            chmod($path, 0777);
            return unlink($path);
        }
        return false;
    }

    public static function cropImage($src, $dst, $data, $type, $width = 0, $height = 0)
    {
        if (!empty($src) && !empty($dst) && !empty($data)) {
            switch ($type) {
                case 'image/gif':
                    $src_img = imagecreatefromgif($src);
                    break;

                case 'image/jpeg':
                    $src_img = imagecreatefromjpeg($src);
                    break;

                case 'image/png':
                    $src_img = imagecreatefrompng($src);
                    break;

            }

            if (!$src_img) {
                $msg = "读取原图片失败！";
                return;
            }

            $size = getimagesize($src);
            $size_w = $size[0]; // natural width 200
            $size_h = $size[1]; // natural height 200

            $src_img_w = $size_w;
            $src_img_h = $size_h;

            $degrees = $data->rotate;

            // Rotate the source image，有旋转角度参数的
            if (is_numeric($degrees) && $degrees != 0) {
                // PHP's degrees is opposite to CSS's degrees
//                imagecolorallocatealpha 创建一个完全透明的图
//                imagerotate 按一定角度旋转图片，第三个参数设置旋转后没有覆盖到的
                $new_img = imagerotate($src_img, -$degrees, imagecolorallocatealpha($src_img, 0, 0, 0, 127));

                imagedestroy($src_img);
                $src_img = $new_img;

                $deg = abs($degrees) % 180;
                $arc = ($deg > 90 ? (180 - $deg) : $deg) * M_PI / 180;//弧度公式

                $src_img_w = $size_w * cos($arc) + $size_h * sin($arc);
                $src_img_h = $size_w * sin($arc) + $size_h * cos($arc);

                // Fix rotated image miss 1px issue when degrees < 0
                $src_img_w -= 1;
                $src_img_h -= 1;
            }

            $tmp_img_w = $data->width;//有参数 200
            $tmp_img_h = $data->height;//没参数 123.077
//            $dst_img_w = 220;
            $dst_img_w = $width ? $width : 220;
//            $dst_img_h = 220;
            $dst_img_h = $height ? $height : 220;

            $src_x = $data->x;//x轴坐标 0
            $src_y = $data->y;//y轴坐标 51.510989010989

            if ($src_x <= -$tmp_img_w || $src_x > $src_img_w) {
                $src_x = $src_w = $dst_x = $dst_w = 0;
            } else if ($src_x <= 0) {
                $dst_x = -$src_x;
                $src_x = 0;
                $src_w = $dst_w = min($src_img_w, $tmp_img_w + $src_x);
            } else if ($src_x <= $src_img_w) {
                $dst_x = 0;
                $src_w = $dst_w = min($tmp_img_w, $src_img_w - $src_x);
            }

            if ($src_w <= 0 || $src_y <= -$tmp_img_h || $src_y > $src_img_h) {
                $src_y = $src_h = $dst_y = $dst_h = 0;
            } else if ($src_y <= 0) {
                $dst_y = -$src_y;
                $src_y = 0;
                $src_h = $dst_h = min($src_img_h, $tmp_img_h + $src_y);
            } else if ($src_y <= $src_img_h) {
                $dst_y = 0;
                $src_h = $dst_h = min($tmp_img_h, $src_img_h - $src_y);
            }

            // Scale to destination position and size
            $ratio = $tmp_img_w / $dst_img_w; //0.90909090909091
            $dst_x /= $ratio;//0.0
            $dst_y /= $ratio;//0.0
            $dst_w /= $ratio;//220.0
            $dst_h /= $ratio;//135.3847

            $dst_img = imagecreatetruecolor($dst_img_w, $dst_img_h);

            // Add transparent background to destination image
            imagefill($dst_img, 0, 0, imagecolorallocatealpha($dst_img, 0, 0, 0, 127));
            imagesavealpha($dst_img, true);
//            两个后加的，不然会显示透明底图
            $dst_w += 1;
            $dst_h += 1;
            $result = imagecopyresampled($dst_img, $src_img, $dst_x, $dst_y, $src_x, $src_y, $dst_w, $dst_h, $src_w, $src_h);

            if ($result) {
                if (!imagepng($dst_img, $dst)) {
                    $msg = "保存裁剪图片失败！";
                }
            } else {
                $msg = "裁剪图片失败！";
            }

            imagedestroy($src_img);
            imagedestroy($dst_img);
        }
    }
}