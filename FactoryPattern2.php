<?php

    // 定义接口
    
    interface IUser
    {
      function getName() {}
    }
    
    
    // 实现接口
    
    class User implements IUser {
    
        public static Load($id) {
          return new User($id);
        }
        
        public static function Create() {
          return new User(null)
        }
        
        public function __construct($id) {
        
        }
        
        public function getName() {
          return "Jack";
        }
    }
    
    $uo = User::Load(1);
    echo $uo->getName();
    
    
    
    
    
