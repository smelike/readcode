<?php
    session_start();
    // 链接到数据库
    $db_link = mysqli_connect('localhost','root','','user');
    mysqli_query($db_link,"set names utf8");
    $sql = "select * from user";

    $username = $_POST['username'];
    $pwd = $_POST['pwd'];
    $repwd = $_POST['repwd'];
    $code = $_POST['code'];
    $session_code = $_SESSION['code'];
    
    // 判断用户名是否为空
    if(empty($username)){
        echo '用户名不能为空';exit;
    }

    // 判断密码是否为空
    if(empty($pwd)){
         echo '密码不能为空';exit;
    }
    
    if(empty($repwd)){
         echo '确认密码不能为空';exit;
    }

    // 判断密码与确认密码是否为空
    if ($pwd != $repwd) {
        echo "两次密码输入不一致";exit;
    }

    if ($code != $session_code) {
        echo '验证码不正确';exit;
    }
    // 所有的规则都已经校验通过，才去链接数据库查询
    $user_name = "select * from user where username = '$username'";
    $sql_user = mysqli_query($db_link,$user_name);
    $userInfo = mysqli_fetch_assoc($sql_user);
    
    // 判断用户资料，检查是否已经存在相同的注册用户
    if($userInfo){
        echo "<script>alert('该用户名已经被注册！');window.history.back()</script>";
    }else{  
        $new = "insert into user(username,password,repassword) value('$username','$pwd','$repwd')";
        $result = mysqli_query($db_link,$new);
        if($result){
               echo "<script>alert('注册成功！');window.history.back()</script>";
        }
    }
