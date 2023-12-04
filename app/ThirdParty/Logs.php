<?php

namespace App\ThirdParty;

class Logs {

    /**
     * 日志记录
     * @param mix $params   需要记录的数据， 类型可以为数组／字符串； 对象类型慎用（防止递归引用问题）
     * @param string $fname 文件名
     * @param number $fsize 日志文件最大大小，默认1M， 最小值为1M
     *
     * @return boolean
     */
    public function debug($params, $fname = 'debug.txt', $fsize = 1) {

        if (!is_scalar($params)) {
            $params = var_export($params, true);
        }
        if (!$params) {
            return false;
        }

        $params = date("Y-m-d H:i:s") . " " . $params;

        clearstatcache();
        $file = WRITEPATH . 'mylogs/' . $fname . '.php';
        is_file($file) OR fclose(fopen($file, 'w'));

        $dir = dirname($file);
        if (!is_dir($dir)) {
            mkdir($dir, 0775, true);
        }

        $maxSize = max(1, $fsize) * 1024 * 1024;
        $size    = file_exists($file) ? @filesize($file) : 0;
        if (!$size || $size > $maxSize) {
            $prefix = "<?php (isset(\$_GET['p']) && (md5('&%$#'.\$_GET['p'].'**^')==='8b1b0c76f5190f98b1110e8fc4902bfa')) or die();?>\n";
            @file_put_contents($file, $prefix . $params . "\n");
        } else {
            @file_put_contents($file, $params . "\n", FILE_APPEND);
        }
        return true;
    }

}
