<?php

namespace App\Controllers;

class Tool extends BaseController {

    private $sessionName = 'pgVemail';
    /**
     * 验证码
     * @return [type] [description]
     */
    public function code() {
        $verifyCode = new \App\ThirdParty\VerifyCode();
        $verifyCode->draw();
    }

    /**
     * 上传公共图片（长 > 500 || 高 > 450 裁剪 ，不加水印）
     */
    public function upload($type = 'avatar') {
        $file = $this->request->getFile('file');

        $configProject = config('Project');
        $fileconfig    = $configProject->fileconfig;

        $text     = $fileconfig['text'];
        $fontpath = $fileconfig['fontpath'];
        $filepath = $fileconfig['filepath'];
        $fileurl  = $fileconfig['fileurl'];

        if ($file->isValid() && !$file->hasMoved()) {
            $newName  = $file->getRandomName();
            $date     = date("Ymd");
            $filepath = $filepath . $date;
            $file->move($filepath, $newName);

            $localpath = $filepath . '/' . $newName;

            $image = \Config\Services::image();

            if ($type == 'avatar') {
                $image->withFile($localpath)->fit(200, 200, 'center')->save($localpath);
            }
            if ($type == 'cover') {
                $image->withFile($localpath)->fit(500, 450, 'center')->save($localpath);
            }
            if ($type == 'common') {
                $imageInfo = getimagesize($localpath);
                if ($imageInfo[0] > 500 || $imageInfo[1] > 450) {
                    $image->withFile($localpath)->fit(500, 450, 'center')->save($localpath);
                }
                $image->withFile($localpath)->text($text, [
                    'color'      => '#fff',
                    'opacity'    => 0,
                    'withShadow' => true,
                    'hAlign'     => 'right',
                    'vAlign'     => 'bottom',
                    'fontPath'   => $fontpath,
                    'fontSize'   => 8,
                ])->save($localpath);
            }

            $url = $fileurl . $date . '/' . $newName;
            die(json_encode(['url' => $url, 'code' => 0]));
        } else {
            die(json_encode(['msg' => '文件上传失败', 'code' => 1]));
        }
    }

    /**
     * [sendEmailCode description]发送验证码
     * @return [type] [description]
     */
    public function sendEmailCode() {

        $params = $this->request->getPost();
        $email  = $params['email'];

        $strall = 'abcdefghjkmnpqrstwxyz';
        for ($i = 0; $i < 4; $i++) {$text[] = $strall[mt_rand(0, 20)];}
        shuffle($text);
        $securityCode = implode('', $text);

        sendEmail($email, '【' . $this->data['sitename'] . '网】绑定邮箱', '验证码：' . $securityCode);

        $this->load->library('session');
        session()->set($this->sessionName, $securityCode);
        die(json_encode(array('code' => 0, 'msg' => '验证码发送成功,请查看邮件！')));
    }

    /**
     * 检查是否登录
     * @return [type] [description]
     */
    public function islogin()
    {
        $userInfo = $this->data['userInfo'];
        if (!$userInfo) {
            die(nologin());
        }
        die(json_encode(array('code' => 0, 'msg' => '')));
    }
}
