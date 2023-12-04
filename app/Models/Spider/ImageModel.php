<?php namespace App\Models\Spider;

use CodeIgniter\Model;

class ImageModel extends Model
{
    protected $table = 'spiderurl_';
    protected $primaryKey = 'id';

    protected $allowedFields = [];
    protected $beforeInsert = ['beforeInsert'];
    protected $beforeUpdate = ['beforeUpdate'];

    protected $db_spider;

    public function __construct()
    {
        $this->db_spider = db_connect('spider');
    }

    protected function beforeInsert(array $data)
    {
        return $data;
    }

    protected function beforeUpdate(array $data)
    {
        return $data;
    }

    public function getImage($images, $get_cover = 1)
    {

        $myImage = new \App\ThirdParty\MyImage();

        $configProject = config('Project');
        $fileConfig = $configProject->fileConfig;

        $text = $fileConfig['text'];
        $fontpath = $fileConfig['fontpath'];
        $filepath = $fileConfig['filepath'];
        $fileurl = $fileConfig['fileurl'];

        $res = ['cover' => '', 'desImages' => []];
        $today = date("Ymd");
        foreach ($images as $k => $v) {
            $fd = @fopen($v, 'r');
            if ($fd != null) {
                $fix = pathinfo($v, PATHINFO_EXTENSION);
                if (!$fix) {
                    $fix = 'jpg';
                }
                $path = $filepath . $today . '/';
                if (!file_exists($path) || !is_dir($path)) {
                    mkdir($path, 0777, true);
                }
                $writeFile = md5(time() . $v) . '.' . $fix;
                $realPath = $path . $writeFile;
                file_put_contents($realPath, $fd);

                $newimage = $fileurl . $today . '/' . $writeFile;

                list($width, $height, $type, $attr) = @getimagesize($v);

                if ($width == 768 && $height = 510) {
                    $res['desImages'][] = '';
                    continue;
                }

                if ($get_cover && !$res['cover']) {

                    if ($width > 50 && $height > 50) {
                        $fd = fopen($v, 'r');
                        $writeFileCover = md5(time() . $v . 'cover') . '.' . $fix;
                        $realPathCover = $path . $writeFileCover;
                        file_put_contents($realPathCover, $fd);

                        $myImage->crop($realPathCover, 400, 280, $realPathCover);

                        $cover = $fileurl . $today . '/' . $writeFileCover;

                        $res['cover'] = $cover;
                    }
                }

                $myImage->crop($realPath, 700, 560, $realPath);

                $myImage->text($realPath, $text, $fontpath);

                $res['desImages'][] = $newimage;
            } else {
                $res['desImages'][] = '';
            }
        }
        return $res;
    }

    public function getImageCover($image)
    {
        $configProject = config('Project');
        $fileConfig = $configProject->fileConfig;

        $filepath = $fileConfig['filepath'];
        $fileurl = $fileConfig['fileurl'];

        $cover = '';
        $today = date("Ymd");

        $fd = @fopen($image, 'r');
        if ($fd != null) {
            $fix = pathinfo($image, PATHINFO_EXTENSION);
            if (!$fix) {
                $fix = 'jpg';
            }
            $path = $filepath . $today . '/';
            if (!file_exists($path) || !is_dir($path)) {
                mkdir($path, 0777, true);
            }
            $writeFile = md5(time() . $image) . '.' . $fix;
            $realPath = $path . $writeFile;
            file_put_contents($realPath, $fd);

            $newimage = $fileurl . $today . '/' . $writeFile;

            $cover = $newimage;
        } else {
            $cover = '';
        }

        return $cover;
    }
}
