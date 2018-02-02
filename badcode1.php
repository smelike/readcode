<?php
    session_start();
    // 链接到数据库
    $db_link = mysqli_connect('localhost','root','','user');
    mysqli_query($db_link,"set names utf8");
    $sql = "select * from user";
    if(!empty($_POST['username'])){
        $username = $_POST['username'];
        // var_dump(isset($_POST['username']));
        if(!empty($_POST['pwd'])){
            $pwd = $_POST['pwd'];
            if(!empty($_POST['repwd'])){
                $repwd = $_POST['repwd'];
                if($_POST['pwd']===$_POST['repwd']){
                        if(!empty($_POST['code'])){
                            if($_POST['code']===$_SESSION['code']){
                                $user_name = "select * from user where username = '$username'";
                                $sql_user = mysqli_query($db_link,$user_name);
                                $userInfo = mysqli_fetch_assoc($sql_user);
                                // var_dump($userInfo);
                                if($userInfo){
                                        echo "<script>alert('该用户名已经被注册！');window.history.back()</script>";
                                }else{  
                                    $new = "insert into user(username,password,repassword) value('$username','$pwd','$repwd')";
                                    $result = mysqli_query($db_link,$new);
                                        if($result){
                                            echo "<script>alert('注册成功！');window.history.back()</script>";
                                        }   
                                }   
                            }else{
                                echo "<script>alert('验证码错误!');window.history.back()</script>";
                            }   
                        }else{
                            echo "<script>alert('请输入验证码');window.history.back()</script>";
                        }
                }else{
                    echo "<script>alert('两次密码不一致');window.history.back()</script>";
                }
            }else{
                echo "<script>alert('请再次输入密码');window.history.back()</script>";
            }
        }else{
            echo "<script>alert('请输入密码');window.history.back()</script>";
        }
    }else{
        echo "<script>alert('请输入用户名');window.history.back()</script>";
    }
?>
