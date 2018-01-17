<?php
/**
 * Created by PhpStorm.
 * User: TPL
 * Date: 2017/12/26
 * Time: 下午8:15
 */
header("Content-Type:text/html;charset=utf-8");

//TODO:获取传来的数据，如果ajax的datatype为json，则获取的是json对象

//判断变量是否存在或是否为空
//if(!isset($_GET["username"]) || empty($_GET["username"]))
//$user=$_GET["username"];
//$email=$_GET["email"];
//$pwd=$_GET["password"];
echo '222';
//echo '获取到数据:','username:',$user,'email:';
echo '123';
//query_sql();

function query_sql(){
    $arr = array('a' => 1, 'b' => 2, 'c' => 3, 'd' => 4, 'e' => 5);
    echo json_encode($arr);
}
//$json = json_encode(array(
//    "resultCode"=>200,
//    "message"=>"查询成功！"
//),JSON_UNESCAPED_UNICODE);
//echo $json;


//$dsn="mysql:dbname=testschema;host=localhost";
//$db_user='root';
//$db_pass='root';
//try{
//    $pdo=new PDO($dsn,$db_user,$db_pass);
//	 //foreach ($dbh->query('SELECT * from mytable') as $row) {
//     //   print_r($row); //你可以用 echo($GLOBAL); 来看到这些值
//    //}
//}catch(PDOException $e){
//    echo '数据库连接失败'.$e->getMessage();
//}
//
//
////TODO:先查询是否已经存在，存在返回0，不存在就写入数据返回1
////查询user是否存在
//$sql="select * from mytable WHERE username='$user'";
//$res=$pdo->query($sql);


//if($res->fetchColumn()){
//	//存在此user
//    echo 'no';
//    return 1;
//}else{
//	//不存在此user
//    echo 'yes';
//    return 2;
//}














?>