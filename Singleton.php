<?php

  /*
      单元素(singleton)模式/单例模式
      
      某些应用程序资源是独占的。
      
      如：数据库连接句柄，而且每次打开或关闭都需要开销
      
      是否可以将数据库连接句柄，保存。作为后面的访问数据库请求共享。
      
  */
  require 'DB.php';
  class DatabaseConnect
  {
      public static function get() {
        static $db = null;
        if ($db == null) {
          // 实例化对象自己
          $db = new DatabaseConnect();
        }
        
        return $db;
      }
      
      private $_handle = null;
      
      private function __construct() {
        $dsn = "mysql://root:password@localhost/photos";
        $this->_handle = & DB::Connect($dsn, array());
      }
      
      public function handle() {
        return $this->_handle; 
      }
      
      private function __clone() {
      
      }
  }
  
  $db_handle = DatabaseConnect::get()->handle();
  
  
  /*
    使用 get 实例化对象本身，控制了对象只能被实例化一次。
    
    使用 __construct(构造函数)，打开数据库连接。
    
    这样处理有问题，如果不经过 get 获取对象，那么数据库连接还是会每次都被打开。
    
    
  */
  
