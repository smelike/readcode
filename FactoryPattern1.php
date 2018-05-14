<?php

  /*
    五种常见的 PHP 设计模式
    
    软件中，设计模式意味着更快开发健壮软件的方法，还提供了以友好的术语封装大型理念的方法.
    
    如，你可以说你正在编写一个提供松散耦合的消息传递系统，也可以说正在编写名称为观察者的模式。
    
    模式，实际上是在大型代码库中发挥作用。
    
    
   */
   
   /*
      工厂模式，可以使系统代码实现松散耦合，避免出现紧密耦合。
      
      紧密耦合，就是函数和类严重依赖其他的函数和类的行为和结构。
      
      修改一个代码片段，就会发生问题。
      
   */
   
   // 第一种模式 - Factory1.php
   
   interface IUser
   {
      function getName();
   }
   
   // 实现接口
   class User implements IUser
   {
      public function __construct($id) {}
      
      public function getName() {
        return "Jack";
      }
   }
  
  // 创建对象
  class UserFactory
  {
      public static function Create($id) {
        return new User($id);
      }
  }
  
  
  $uo = UserFactory::Create(1);
  
  echo $uo->getName() . "\n";
  
  /* 
    IUser 接口定义了用户对象执行什么操作。
    IUser 的实现称为 User。
    
    UserFactory 工厂类创建  IUser 对象。
   */
  
