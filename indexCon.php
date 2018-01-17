<?php
/**
 * Created by PhpStorm.
 * User: TPL
 * Date: 2017/12/26
 * Time: 下午8:15
 */
header("Content-Type:text/html;charset=utf-8");
$dsn="mysql:dbname=testschema;host=localhost";
$db_user='root';
$db_pass='890123asd';
echo '123';
try{
    $pdo=new PDO($dsn,$db_user,$db_pass);
	 foreach ($dbh->query('SELECT * from mytable') as $row) {
        print_r($row); //你可以用 echo($GLOBAL); 来看到这些值
    }
}catch(PDOException $e){
    echo '数据库连接失败'.$e->getMessage();
}
$user=$_GET["username"];
$pwd=$_GET["password"];
//查询
$sql="select * from mytable WHERE username='$user' AND password='$pwd'";
$res=$pdo->query($sql);
if($res->fetchColumn()){
	echo '成功';
}else{
	echo '失败';
}
$num=0;
foreach($res as $row){
    $num++;
    echo $row['username'].$row['password'].'<br />';

}
echo $num;













?>