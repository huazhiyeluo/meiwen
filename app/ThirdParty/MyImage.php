<?php

namespace App\ThirdParty;

class MyImage
{
    /**
     * 图像添加文字
     * @param  string  $text   添加的文字
     * @param  string  $font   字体路径
     * @param  integer $size   字号
     * @param  string  $color  文字颜色
     * @param  integer $locate 文字写入位置
     * @param  integer $offset 文字相对当前位置的偏移量
     * @param  integer $angle  文字倾斜角度
     */
    public function text($source_path, $text, $font)
    {
        list($source_width, $source_height, $source_mime, $attr) = @getimagesize($source_path);
        if ($source_mime == '') {
            return false;
        }
        switch ($source_mime) {
            case 'image/gif':
                return false;
                break;
            case 'image/jpeg':
                $img = imagecreatefromjpeg($source_path);
                break;

            case 'image/png':
                $img = imagecreatefrompng($source_path);
                break;
            default:
                return false;
                break;
        }
        if ($source_mime == 'image/gif') {
            return false;
        }
        $size = 8;
        $color = array(255, 255, 255);
        $offset = 0;
        $angle = 0;

        $info = imagettfbbox($size, $angle, $font, $text);
        $minx = min($info[0], $info[2], $info[4], $info[6]);
        $maxx = max($info[0], $info[2], $info[4], $info[6]);
        $miny = min($info[1], $info[3], $info[5], $info[7]);
        $maxy = max($info[1], $info[3], $info[5], $info[7]);
        $x = $minx;
        $y = abs($miny);
        $w = $maxx - $minx;
        $h = $maxy - $miny;
        $x += $source_width - $w;
        $y += $source_height - $h;

        if (is_array($offset)) {
            $offset = array_map('intval', $offset);
            list($ox, $oy) = $offset;
        } else {
            $offset = intval($offset);
            $ox = $oy = $offset;
        }
        $col = imagecolorallocatealpha($img, $color[0], $color[1], $color[2], 0.5);
        imagettftext($img, $size, $angle, $x + $ox - 10, $y + $oy - 5, $col, $font, $text);
        switch ($source_mime) {
            case 'image/gif':
                imagegif($img, $source_path);
                break;
            case 'image/jpeg':
                imagejpeg($img, $source_path);
                break;
            case 'image/png':
                imagejpeg($img, $source_path);
                break;
            default:
                return false;
        }
    }

    public function crop($source_path, $target_width, $target_height, $name, $flag = 0)
    {
        list($source_width, $source_height, $source_mime, $attr) = @getimagesize($source_path);
        if ($source_mime == '') {
            return false;
        }
        if ($flag == 1) {
            $source_ratio = $source_height / $source_width;
            $target_ratio = $target_height / $target_width;

            // 源图过高
            if ($source_ratio > $target_ratio) {
                $cropped_width = $source_width;
                $cropped_height = $source_width * $target_ratio;
                $source_x = 0;
                $source_y = ($source_height - $cropped_height) / 2;
            }
            // 源图过宽
            elseif ($source_ratio < $target_ratio) {
                $cropped_width = $source_height / $target_ratio;
                $cropped_height = $source_height;
                $source_x = ($source_width - $cropped_width) / 2;
                $source_y = 0;
            }
            // 源图适中
            else {
                $cropped_width = $source_width;
                $cropped_height = $source_height;
                $source_x = 0;
                $source_y = 0;
            }
        } else {
            if ($source_width < $target_width && $source_height < $target_height) {
                return;
            }

            $scale = min($target_width / $source_width, $target_height / $source_height);

            $target_width = $source_width * $scale;
            $target_height = $source_height * $scale;
        }

        switch ($source_mime) {
            case 'image/gif':
                return false;
                break;
            case 'image/jpeg':
                $source_image = imagecreatefromjpeg($source_path);
                break;
            case 'image/png':
                $source_image = imagecreatefrompng($source_path);
                break;
            default:
                return false;
                break;
        }

        if ($flag == 1) {
            $target_image = imagecreatetruecolor($target_width, $target_height);
            $cropped_image = imagecreatetruecolor($cropped_width, $cropped_height);

            // 图片裁剪
            imagecopy($cropped_image, $source_image, 0, 0, $source_x, $source_y, $cropped_width, $cropped_height);
            // 图片缩放
            imagecopyresampled($target_image, $cropped_image, 0, 0, 0, 0, $target_width, $target_height, $cropped_width, $cropped_height);
        } else {
            $target_image = imagecreatetruecolor($target_width, $target_height);
            // 图片缩放
            imagecopyresampled($target_image, $source_image, 0, 0, 0, 0, $target_width, $target_height, $source_width, $source_height);
        }

        switch ($source_mime) {
            case 'image/gif':
                imagegif($target_image, $name);
                break;
            case 'image/jpeg':
                imagejpeg($target_image, $name);
                break;
            case 'image/png':
                imagejpeg($target_image, $name);
                break;
            default:
                return false;
        }
        imagedestroy($source_image);
        imagedestroy($target_image);
    }

}
