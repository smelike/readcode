<?php

/*
	static
	final
*/

class StaticExample{
	static public $aNum = 0;
	static public function sayHello(){
		self::$aNum++;
		print "hello(".self::$aNum.")\n";
	}

}

abstract class ShopProductWriter{
	protected $products = array();
	
	public function addProduct(ShopProduct $shopProduct){
		$this->products[] = $shopProduct;
	}
	
	abstract public function write();//抽象方法的方法体必须为空
}

interface Chargeable{
	public function getPrice();//接口中方法的方法体必须为空
	public function getDiscount();
}

//任何实现接口的类都要实现接口中所定义的所有方法，否则该类必须声明为abstract
abstract class ShopProduct implements Chargeable{
	
	public function getPrice(){
		return $this->price;
	}
}
