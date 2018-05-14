<?php

  /*
      观察者模式
      
      一个对象通过添加一个方法（该方法允许另一个对象，即观察者自己）使本身变得可观察。
      
      当观察的对象更改时，它会将消息发送到已注册的观察者。
      
      这些观察者使用该消息执行的操作与可观察的对象无关。
      
      结果是对象可以互助对话，而不必了解原因。
      
  */
  
  // 观察者接口
  
  interface IObserver {
    function onChanged($sender, $args);
  }
  
  // 可观察接口
  
  interface IObServable {
  
    function addObserver($observer);
  }
  
  class UserList implements IObservable {
  
    public function addCustomer($name) {
      foreach ($this->_observers as $obs) {
        $obs->onChanged($this, $name);
      }
    }
    
    public function addObserver($observer) {
      $this->_observers[] = $observer;
    }
      
   }
    
   class UserListLogger implements IObserver
   {
      public function onChanged($sender, $args) {
        echo '$args' . 'added to user list'; 
      }
   }
   
   class UserDB implements IObserver {
   
      public function onChanged($sender, $args) {
          /*
              实时更新存储的用户名
              mysql 实时操作开销大
              
              实际中会使用消息队列进行处理
          */
      }
   }
   
   $ul = new UserList();
   $ul->addObServer(new newUserListLogger());
   $ul->addCustomer("jamesddd");
  
