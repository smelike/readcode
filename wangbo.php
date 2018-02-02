if($user_name==''){
		echo '请输入用户名';
		exit;
	}
	if($password==''){
		echo '请输入密码';
		exit;
	}
	if($repassword==''){
		echo '请再次输入密码';	
		exit;
	}
	
	if($repassword != $password){
		echo '密码不一致';
		exit;
	}
	
	if($repassword == $password){
		echo '';
	}
	
	if($e_mail==''){
		echo'请输入邮箱地址';
		exit;
	}
	
	
	$email='1909970983@qq.com';
	$pattern="/([a-z0-9]*[-_.]?[a-z0-9]+)*@([a-z0-9]*[-_]?[a-z0-9]+)+[.][a-z]{2,3}([.][a-z]{2})?/i";
	if(preg_match($pattern,$email)){
    echo ' ';
	} else{
    echo '邮箱格式错误！';
	exit;
	}
	if($indent ==''){
		echo '请输入验证码';
	}
	
	if($indent !==$_SESSION['code']){
		echo'验证码错误';
		exit;
	}
	if($indent ==$_SESSION['code']){
		echo '';
	}
	
	
	
	
	
	if($number==''){
		echo '请输入正确的手机号码';
		exit;
	}
	
	if(preg_match("/1[3458]{1}\d{9}$/",$number)){  
   
     echo "";  
   
	}else{  
   
	echo "您输入的手机号码不正确";  
   
	}
	
	$db = mysqli_connect("localhost","root","","register");
	
	mysqli_query($db,"set names utf8");
	
	$sql = "SELECT * FROM user";
	
	$result = mysqli_query($db,$sql);
	
	$new ="insert into user(accout,password,phone,email) value('$_POST[user_name]','$_POST[password]','$_POST[number]','$_POST[e_mail]')";
	
	$result1 = mysqli_query($db,$new);
	
	if($result1){
		
		echo "注册成功<a href='entry.html'>点击登录</a>";
	
	}else{
		echo"注册失败";
	}
	
