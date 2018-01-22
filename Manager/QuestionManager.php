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
    case 'SetAnswer':
        SubmitAnswer();
        break;
    case 'SetQuestion':
        SubmitQuestion();
        break;
    case 'showquestion':
        ShowQuestion();
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

    if($res->fetchColumn()){
        //存在此user,成功返回 loginresult=1,失败 loginresult=0
//        $data = array('loginresult' => 1);
//        echo json_encode($data);
//        echo json_encode('登陆成功');
        echo json_encode(1);
        return ;
    }else{
        //用户名或密码错误
//        $data = array('loginresult' => 0);
//        echo json_encode('登陆失败');
//        echo json_encode($data);
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

function SubmitQuestion(){

    //判断传入参数是否正确
    if(!isset($_REQUEST["quescontent"]) || empty($_REQUEST["quescontent"])){
        echo json_encode('没有传入quescontent或为空');
        return;
    }
    if(!isset($_REQUEST["Aitem"]) || empty($_REQUEST["Aitem"])){
        echo json_encode('没有传入Aitem或为空');
        return;
    }
    if(!isset($_REQUEST["Bitem"]) || empty($_REQUEST["Bitem"])){
        echo json_encode('没有传入Bitem或为空');
        return;
    }
    if(!isset($_REQUEST["Citem"]) || empty($_REQUEST["Citem"])){
        echo json_encode('没有传入Citem或为空');
        return;
    }
    if(!isset($_REQUEST["Ditem"]) || empty($_REQUEST["Ditem"])){
        echo json_encode('没有传入Ditem或为空');
        return;
    }


    //获取参数
    $quescontent=$_REQUEST["quescontent"];
    $a=$_REQUEST["Aitem"];
    $b=$_REQUEST["Bitem"];
    $c=$_REQUEST["Citem"];
    $d=$_REQUEST["Ditem"];
    $true=$_REQUEST["Answer"];

    //数据库相关
    $pdo =ConnectMysql();
    //创建题目
        $insertsql="INSERT INTO `qctable` (`quescontent`,`Aitem`, `Bitem`, `Citem`, `Ditem`, `TrueAns`) VALUES ('$quescontent', '$a', '$b', '$c', '$d', '$true')";
        //exec执行SQL语句增删改查，返回影响行数
        $execres=$pdo->exec($insertsql);
        if($execres){
            echo json_encode(1);
            return;
        }else{
            echo json_encode('提交失败'.$execres);
            return;
        }





}

function ShowQuestion(){

    //数据库相关
    $pdo =ConnectMysql();
    $sql="select * from qctable WHERE istoday='1'";
    //query查询SQL语句，返回PDOstatement对象
    $res=$pdo->query($sql);
    //取返回结果的第一行数据，istoday应该只有1个为1，有多个也只第一个有效,没取到就是false
    $resultdata=$res->fetch(PDO::FETCH_ASSOC);
    //如果有数据
    if($resultdata){
        //中括号里是数据库列名
        $question=$resultdata['quescontent'];
        $selectA=$resultdata['Aitem'];
        $selectB=$resultdata['Bitem'];
        $selectC=$resultdata['Citem'];
        $selectD=$resultdata['Ditem'];
        //创建数组
        //成功时data为1
        $data = array('data'=> 1,'question'=>$question,'itemA' => $selectA,'itemB' => $selectB,'itemC' => $selectC,'itemD' => $selectD,);
        //变成json格式，返回
        echo json_encode($data);
        return;
    }else{
        $data = array('data'=> 0);
        echo json_encode($data);
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