<?php

// 定义常数
define('PUBNUB_LIB_BASE_DIR', __DIR__);

// 定义函数 pubnubAutoloader
function pubnubAutoloader($className)
{
    $className = ltrim($className, '\\');
    $fileName  = '';
    
    // 以 \A\B\C 区分的类名
    if ($lastNsPos = strrpos($className, '\\')) {
        $namespace = substr($className, 0, $lastNsPos);
        $className = substr($className, $lastNsPos + 1);
        // 将 \ 替换为目录间隔符，拼接得到完整的文件路径
        $fileName .= str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
    }
    
    // 以下划线(underline _) 方式类间隔的类名
    $fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';
    $fileName = PUBNUB_LIB_BASE_DIR.DIRECTORY_SEPARATOR.$fileName;
    if(file_exists($fileName)){
        require_once $fileName;
    }
}
spl_autoload_register('pubnubAutoloader', true, true);
