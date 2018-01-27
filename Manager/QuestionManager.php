<?php
/**
 * Created by PhpStorm.
 * User: TPL
 * Date: 2018/1/17
 * Time: 上午10:08
 */
header("Content-Type:text/html;charset=utf-8");
date_default_timezone_set('PRC');

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
     case 'SubmitComment':
         SubmitComment();
         break;
    case 'showcomment':
        ShowComment();
        break;
    case 'showreview':
        ShowReview();
        break;
    case 'deletecomment':
        DeleteComment();
        break;
    case 'likecomment':
        LikeComment();
            break;
    case 'unlikecomment':
        UnLikeComment();
            break;

}
return;
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
    if(!isset($_REQUEST["quesNo"]) || empty($_REQUEST["quesNo"])){
        echo json_encode('没有传入quesNo或为空');
        return;
    }
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
    if(!isset($_REQUEST["Settime"]) || empty($_REQUEST["Settime"])){
        echo json_encode('没有传入Settime或为空');
        return;
    }


    //获取参数
    $NUM=$_REQUEST["quesNo"];
    $quescontent=$_REQUEST["quescontent"];
    $a=$_REQUEST["Aitem"];
    $b=$_REQUEST["Bitem"];
    $c=$_REQUEST["Citem"];
    $d=$_REQUEST["Ditem"];
    $true=$_REQUEST["Answer"];
    $set=$_REQUEST["Settime"];

    //数据库相关
    $pdo =ConnectMysql();
    //创建题目
        $insertsql="INSERT INTO `qctable` (`QuestionNum`,`quescontent`,`Aitem`, `Bitem`, `Citem`, `Ditem`, `TrueAns`,`SetTime`) VALUES ('$NUM','$quescontent', '$a', '$b', '$c', '$d', '$true','$set')";
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
    $time=date("Ymd",time());
    $sql="select * from qctable WHERE SetTime='$time'";
    //query查询SQL语句，返回PDOstatement对象
    $res=$pdo->query($sql);
    //取返回结果的第一行数据，istoday应该只有1个为1，有多个也只第一个有效,没取到就是false
    $resultdata=$res->fetch(PDO::FETCH_ASSOC);
    //如果有数据
    if($resultdata){
        //中括号里是数据库列名
        $questionNum=$resultdata['QuestionNum'];
        $question=$resultdata['quescontent'];
        $selectA=$resultdata['Aitem'];
        $selectB=$resultdata['Bitem'];
        $selectC=$resultdata['Citem'];
        $selectD=$resultdata['Ditem'];
        $Settime=$resultdata['SetTime'];
        $Showans=$resultdata['TrueAns'];
        //创建数组
        //成功时data为1
        $data = array('data'=> 1,'questionnum'=>$questionNum,'question'=>$question,'itemA' => $selectA,
            'itemB' => $selectB,'itemC' => $selectC,'itemD' => $selectD,'Settime'=>$Settime,'showanswer'=>$Showans);
        //变成json格式，返回
        echo json_encode($data);
        return;
    }else{
        $data = array('data'=> 0);
        echo json_encode($data);
        return;
    }
}

function SubmitComment(){
    if(!isset($_REQUEST["CommentContent"]) || empty($_REQUEST["CommentContent"])){
        echo json_encode('没有传入CommentContent或为空');
        return;
    }

    //获取参数

    $CC=$_REQUEST["CommentContent"];
    $QID=$_REQUEST["QuestionID"];
    $UserName=$_REQUEST["SessName"];
    $ObjUser=$_REQUEST["ObjUsername"];
    $time=date("Y-m-d H:i:s",time());


    //数据库相关
    $pdo =ConnectMysql();
    $insertsql="INSERT INTO `commenttable` (`CommentContent`,`QuestionID`,`UserName`,`ObjUserName`,`CommentTime`) VALUES ('$CC','$QID','$UserName','$ObjUser','$time')";
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

//显示评论
function ShowComment(){

    $pdo =ConnectMysql();
    $sql="select * from commenttable WHERE QuestionID='105'";
    //query查询SQL语句，返回PDOstatement对象
    $res=$pdo->query($sql);
    //取返回结果的下一行数据，istoday应该只有1个为1，有多个也只第一个有效,没取到就是false
    //$resultdata=$res->fetch(PDO::FETCH_ASSOC);

    //fetchAll可以获取返回结果的剩余全部数据放在数组里
    $resultalldata=$res->fetchAll(PDO::FETCH_ASSOC);
    $zanresultdata=array();
    foreach ($resultalldata as $row){
        $comid=$row['id'];
        $comuser=$row['UserName'];
        $sql="select * from liketable WHERE CommentID='$comid' And LikeUser='$comuser'";
        //query查询SQL语句，返回PDOstatement对象
        $res=$pdo->query($sql);
        $resultdata=$res->fetch(PDO::FETCH_ASSOC);
        if($resultdata){
            //已经点赞
            $zanresultdata[]=1;
        }else{
            $zanresultdata[]=0;
        }
    }

    if($resultalldata){
        $data = array('data'=> 1,'allcomment'=>$resultalldata,'zandata'=>$zanresultdata);
        echo json_encode($data);
        return;
    }
    else{
        $data = array('data'=> 0);
        json_encode($data);
        return;
        }
}

//显示复习题
function ShowReview(){
    //数据库相关
    $pdo =ConnectMysql();
    $sql="select * from qctable";
    //query查询SQL语句，返回PDOstatement对象
    $res=$pdo->query($sql);
    //取返回结果的第一行数据，istoday应该只有1个为1，有多个也只第一个有效,没取到就是false
    $resultalldata=$res->fetchAll(PDO::FETCH_ASSOC);

    if($resultalldata){
        $data = array('data'=> 1,'allreview'=>$resultalldata);
        echo json_encode($data);
        return;
    }
    else{
        $data = array('data'=> 0);
        json_encode($data);
        return;
    }

    //$resultdata=$res->fetch(PDO::FETCH_ASSOC);
    //如果有数据
  //  if($resultdata){
        //中括号里是数据库列名
      //  $questionNum=$resultdata['QuestionNum'];
     //   $question=$resultdata['quescontent'];
        //创建数组
        //成功时data为1
      //  $data = array('data'=> 1,'questionnum'=>$questionNum,'question'=>$question);
        //变成json格式，返回
      //  echo json_encode($data);
     //   return;
    //}else{
      //  $data = array('data'=> 0);
       // echo json_encode($data);
      //  return;
   // }
}

function DeleteComment(){

    if(!isset($_REQUEST["deleteid"]) || empty($_REQUEST["deleteid"])){
        echo json_encode('没有传入deleteid或为空');
        return;
    }
    $delid=$_REQUEST["deleteid"];
    //数据库相关
    $pdo =ConnectMysql();
    $insertsql="UPDATE `commenttable` SET `DeleteState`='1' WHERE `id`='$delid';";
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

function LikeComment(){
    if(!isset($_REQUEST["username"]) || empty($_REQUEST["username"])){
        echo json_encode('没有传入 username 或为空');
        return;
    }
    if(!isset($_REQUEST["commentid"]) || empty($_REQUEST["commentid"])){
        echo json_encode('没有传入 commentid 或为空');
        return;
    }

    $username=$_REQUEST["username"];
    $commentid=$_REQUEST["commentid"];
    //date_default_timezone_set(PRC);
    $time=date("Y-m-d H:i:s",time());
    //数据库相关
    $pdo =ConnectMysql();

    $sql="select * from liketable WHERE CommentID='$commentid' AND LikeUser='$username'";
    //query查询SQL语句，返回PDOstatement对象
    $res=$pdo->query($sql);
    //取返回结果的第一行数据，istoday应该只有1个为1，有多个也只第一个有效,没取到就是false
    $resultdata=$res->fetch(PDO::FETCH_ASSOC);
    if($resultdata){
        //已点赞
        echo json_encode(2);
        return;
    }
    $updateLikeNum="UPDATE `commenttable` SET `TotalLike`=`TotalLike`+1 WHERE `id`='$commentid';";
    //exec执行SQL语句增删改查，返回影响行数
    $execres=$pdo->exec($updateLikeNum);

    $inserLikeTable="INSERT INTO `liketable` (`CommentID`, `LikeTime`, `LikeUser`) VALUES ('$commentid', '$time', '$username');";
    $execres2=$pdo->exec($inserLikeTable);
    if($execres&&$execres2){
        echo json_encode(1);
        return;
    }else{
        echo json_encode('提交失败'.$execres.'||insert||'.$execres2);
        return;
    }
}

function UnLikeComment(){
    if(!isset($_REQUEST["username"]) || empty($_REQUEST["username"])){
        echo json_encode('没有传入 username 或为空');
        return;
    }
    if(!isset($_REQUEST["commentid"]) || empty($_REQUEST["commentid"])){
        echo json_encode('没有传入 commentid 或为空');
        return;
    }

    $username=$_REQUEST["username"];
    $commentid=$_REQUEST["commentid"];
    //date_default_timezone_set(PRC);
    $time=date("Y-m-d H:i:s",time());
    //数据库相关
    $pdo =ConnectMysql();

    $sql="select * from liketable WHERE CommentID='$commentid' AND LikeUser='$username'";
    //query查询SQL语句，返回PDOstatement对象
    $res=$pdo->query($sql);
    //取返回结果的第一行数据，istoday应该只有1个为1，有多个也只第一个有效,没取到就是false
    $resultdata=$res->fetch(PDO::FETCH_ASSOC);
    if(!$resultdata){
        //没有数据不能取消点赞
        echo json_encode(2);
        return;
    }



    $updateLikeNum="UPDATE `commenttable` SET `TotalLike`=`TotalLike`-1 WHERE `id`='$commentid';";
    //exec执行SQL语句增删改查，返回影响行数
    $execres=$pdo->exec($updateLikeNum);

    $delLikeTable="DELETE FROM `testschema`.`liketable` WHERE `CommentID`='$commentid' AND `LikeUser`='$username';";
    $execres2=$pdo->exec($delLikeTable);
    if($execres&&$execres2){
        echo json_encode(1);
        return;
    }else{
        echo json_encode('提交失败'.$execres.'||insert||'.$execres2);
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
return;
    }
}

