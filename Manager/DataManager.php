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
    case 'login':
        Login();
        break;
    case 'confirm':
        Confirm();
        break;
    case 'SetAnswer':
        SubmitAnswer();
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
        $insertsql="INSERT INTO `mytable` (`username`,`password`, `email`) VALUES ('$username', '$pwd', '$email')";
        //exec执行SQL语句增删改查，返回影响行数
        $execres=$pdo->exec($insertsql);
        if($execres){
            echo json_encode(1);
            return;
        }else{
            echo json_encode('注册失败'.$execres);
            return;
        }



    }

}

function Login(){

    //判断传入参数是否正确
    if(!isset($_REQUEST["username"]) || empty($_REQUEST["username"])){
        echo json_encode('没有传入username或为空');
        return;
    }
    if(!isset($_REQUEST["password"]) || empty($_REQUEST["password"])){
        echo json_encode('没有传入password或为空');
        return;
    }


    //获取参数
    $username=$_REQUEST["username"];
    $pwd=$_REQUEST["password"];

    //数据库相关
    $pdo =ConnectMysql();
    $sql="select * from mytable WHERE username='$username' AND password='$pwd'";
    //query查询SQL语句，返回PDOstatement对象
    $res=$pdo->query($sql);
    //返回第一行数组，没有就返回false
    $resultdata=$res->fetch(PDO::FETCH_ASSOC);
    if($resultdata){
        //有数据则说明存在此用户，进行登录成功的操作，写入session
//        $path = 'tmp/';
//        session_save_path($path);
        session_start();
        $sessid=session_id();
        $_SESSION['myuname']=$username;
        $_SESSION['mysessid']=$sessid;
        $data = array('data' => 1,'sessid'=>$sessid);
//        $data = array('data' => 1,'userid'=>$resultdata['userid']);
        echo json_encode($data);
        //存在此user,成功返回1
//        echo json_encode('登陆成功');
//        echo json_encode(1);
        return ;
    }else{
        //用户名或密码错误
        echo json_encode(0);
        return;
        }
}

function Confirm()
{

    //判断传入参数是否正确
    if (!isset($_REQUEST["sessid"]) || empty($_REQUEST["sessid"])) {
        echo json_encode('没有传入 sessid 或为空');
        return;
    }
    $sessId = $_REQUEST["sessid"];
    session_id($sessId);
    session_start();
    if (isset($_SESSION['mysessid']) && $_SESSION['mysessid'] == $sessId) {
        $confirmsucess = 1;
        echo json_encode(1);
        return;
    } else {
        echo json_encode(0);
        return;
    }
}
function SubmitAnswer()
{

    //判断传入参数是否正确
    if (!isset($_REQUEST["answer"]) || empty($_REQUEST["answer"])) {
        echo json_encode('没有传入answer或为空');
        return;
    }


    //获取参数
    $answer = $_REQUEST["answer"];

    $pdo = ConnectMysql();
        $insertsql = "INSERT INTO `questiontable` (`answer`) VALUES ('$answer')";
        //exec执行SQL语句增删改查，返回影响行数
        $execres = $pdo->exec($insertsql);
        if ($execres) {
            echo json_encode(1);
            return;
        } else {
            echo json_encode('提交失败' . $execres);
            return;
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
        echo json_encode('数据库连接失败'.$e->getMessage());

    }

}