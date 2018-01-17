<?php
/**
 * Created by PhpStorm.
 * User: TPL
 * Date: 2018/1/17
 * Time: 上午10:08
 */
header("Content-Type:text/html;charset=utf-8");

//设置数据库参数,php用.连接字符串
$schema='testschema';
$host='127.0.0.1';
$dsn="mysql:dbname=$schema;host=$host";
$db_user='root';
$db_pass='root';

//判断act变量是否传入
if(!isset($_REQUEST['act'])) {
    echo json_encode('Need act');
    return;
}
//获取act,判断调用哪一个函数
$action=$_REQUEST['act'];

switch ($action){
    case 'register':
        Register();
        break;
}

//注册
function Register(){

    //判断传入参数是否正确
    if(!isset($_REQUEST["username"]) || empty($_REQUEST["username"])){
        echo json_encode('没有传入username或为空');
        return;
    }
    if(!isset($_REQUEST["email"]) || empty($_REQUEST["email"])){
        echo json_encode('没有传入email或为空');
        return;
    }
    if(!isset($_REQUEST["password"]) || empty($_REQUEST["password"])){
        echo json_encode('没有传入password或为空');
        return;
    }

    //获取参数
    $username=$_REQUEST["username"];
    $email=$_REQUEST["email"];
    $pwd=$_REQUEST["password"];

    //数据库相关
    $pdo =ConnectMysql();
    $sql="select * from mytable WHERE username='$username'";
    //query查询SQL语句，返回PDOstatement对象
    $res=$pdo->query($sql);

    if($res->fetchColumn()){
        //存在此user
        echo json_encode('已存在用户');
        return ;
    }else{
        //不存在此user，可以创建用户
        $insertsql="INSERT INTO `mytable` (`username`, `password`, `email`) VALUES ('$username', '$pwd', '$email')";
        //exec执行SQL语句增删改查，返回影响行数
        $execres=$pdo->exec($insertsql);
        if($execres){
            echo json_encode('注册成功');
        }


    }

}

//数据库连接
function ConnectMysql(){
    //引用全局变量
    global $dsn,$db_user,$db_pass;

    try{
        $mypdo=new PDO($dsn,$db_user,$db_pass);
        return $mypdo;
    }catch(PDOException $e){
        echo '数据库连接失败'.$e->getMessage();
    }

}