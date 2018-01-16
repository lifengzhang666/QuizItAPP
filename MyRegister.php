<?php
/**
 * Created by PhpStorm.
 * User: TPL
 * Date: 2017/12/26
 * Time: 下午8:15
 */
header("Content-Type:text/html;charset=utf-8");

//TODO:获取传来的数据
$user=$_GET["username"];
$email=$_GET["email"];
$pwd=$_GET["password"];

echo '获取到数据:','username:',$user,'email:',$email,'password:',$pwd;

$dsn="mysql:dbname=testschema;host=localhost";
$db_user='root';
$db_pass='890123asd';
try{
    $pdo=new PDO($dsn,$db_user,$db_pass);
	 //foreach ($dbh->query('SELECT * from mytable') as $row) {
     //   print_r($row); //你可以用 echo($GLOBAL); 来看到这些值
    //}
}catch(PDOException $e){
    echo '数据库连接失败'.$e->getMessage();
}


//TODO:先查询是否已经存在，存在返回0，不存在就写入数据返回1
//查询user是否存在
$sql="select * from mytable WHERE username='$user'";
$res=$pdo->query($sql);
if($res->fetchColumn()){
	//存在此user
    echo 'no';
    return 'no1';
}else{
	//不存在此user
    echo 'yes';
    return 'yes1';
}














?>