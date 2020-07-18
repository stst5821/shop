<?php
	session_start();
	session_regenerate_id(true);
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>ろくまる農園</title>
</head>

<body>

    <?php

try
{

require_once('../common/common.php');

$post=sanitize($_POST);

$onamae=$post['onamae'];
$email=$post['email'];
$postal1=$post['postal1'];
$postal2=$post['postal2'];
$address=$post['address'];
$tel=$post['tel'];

print $onamae.'様<br />';
print 'ご注文ありがとうござました。<br />';
print $email.'にメールを送りましたのでご確認ください。<br />';
print '商品は以下の住所に発送させていただきます。<br />';
print $postal1.'-'.$postal2.'<br />';
print $address.'<br />';
print $tel.'<br />';

$honbun='';
$honbun.=$onamae."様\n\nこのたびはご注文ありがとうございました。\n";
$honbun.="\n";
$honbun.="ご注文商品\n";
$honbun.="--------------------\n";

$cart=$_SESSION['cart'];
$kazu=$_SESSION['kazu'];
$max=count($cart);

$dsn='mysql:dbname=shop;host=localhost;charset=utf8';
$user='root';
$password='1234';
$dbh=new PDO($dsn,$user,$password);
$dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

for($i=0;$i<$max;$i++)
{
	$sql='SELECT name,price FROM mst_product WHERE code=?';
	$stmt=$dbh->prepare($sql);
	$data[0]=$cart[$i];
	$stmt->execute($data);

	$rec=$stmt->fetch(PDO::FETCH_ASSOC);

	$name=$rec['name'];
	$price=$rec['price'];
	$kakaku[] = $price; // 商品明細を追加するコード用に、商品価格を$priceから$kakakuに代入している。
	$suryo=$kazu[$i];
	$shokei=$price*$suryo;

	$honbun.=$name.' ';
	$honbun.=$price.'円 x ';
	$honbun.=$suryo.'個 = ';
	$honbun.=$shokei."円\n";
}

// データを更新する際にロックをかけて、同時にデータにアクセスできないようにする。
$sql = 'LOCK TABLES dat_sales WRITE,dat_sales_product WRITE';
$stmt = $dbh->prepare($sql);
$stmt->execute($data);

// 商品データを追加する ここから ------------------------------------------------------------

$sql = 'INSERT INTO dat_sales(code_member,name,email,postal1,postal2,address,tel) VALUES(?,?,?,?,?,?,?)';
$stmt = $dbh->prepare($sql);
$data = array();
$data[] = 0;
$data[] = $onamae;
$data[] = $email;
$data[] = $postal1;
$data[] = $postal2;
$data[] = $address;
$data[] = $tel;
$stmt->execute($data);

$sql = 'SELECT LAST_INSERT_ID()';
$stmt = $dbh->prepare($sql);
$stmt->execute($data);
$rec = $stmt->fetch(PDO::FETCH_ASSOC);
$lastcode = $rec['LAST_INSERT_ID()'];// auto_incrementで最近に発番された番号を取得する。この場合、dat_salesテーブルのcodeカラムの最近の番号を取得する。

// 商品データを追加する ここまで------------------------------------------------------------

// 商品明細を追加する ここから--------------------------------------------------------------

for($i=0; $i<$max; $i++)
{
	$sql = 'INSERT INTO dat_sales_product(code_sales,code_product,price,quantity) VALUES(?,?,?,?)';
	$stmt = $dbh->prepare($sql);
	$data = array();
	$data[] = $lastcode; // dat_salesテーブルで最近発番されたcodeを、code_salesに保存する。そのため、dat_sales_productテーブルのcode_salesカラムと、data_sales_productテーブルのcodeカラムの値は必ず同じになる。
	$data[] = $cart[$i];
	$data[] = $kakaku[$i];
	$data[] = $kazu[$i];
	$stmt->execute($data);
}

// DBへの書き込みが終わったら、ロックの解除をする。ロックが解除されると待たされていた次の処理が始まる。この待ち行列のことを「キュー」という。
$sql = 'UNLOCK TABLES';
$stmt = $dbh->prepare($sql);
$stmt->execute();

$dbh=null;

// 商品明細を追加する ここまで--------------------------------------------------------------





$honbun.="送料は無料です。\n";
$honbun.="--------------------\n";
$honbun.="\n";
$honbun.="代金は以下の口座にお振込ください。\n";
$honbun.="ろくまる銀行 やさい支店 普通口座 １２３４５６７\n";
$honbun.="入金確認が取れ次第、梱包、発送させていただきます。\n";
$honbun.="\n";
$honbun.="□□□□□□□□□□□□□□\n";
$honbun.="　～安心野菜のろくまる農園～\n";
$honbun.="\n";
$honbun.="○○県六丸郡六丸村123-4\n";
$honbun.="電話 090-6060-xxxx\n";
$honbun.="メール info@rokumarunouen.co.jp\n";
$honbun.="□□□□□□□□□□□□□□\n";

// print '<br>';
// print nl2br($honbun);

$title = 'ご注文ありがとうございます。';
$header = 'From:info@rokumarunouen.co.jp';
$honbun = html_entity_decode($honbun,ENT_QUOTES,'UTF-8');
mb_language('Japanese');
mb_internal_encoding('UTF-8');
mb_send_mail($email,$title,$honbun,$header);

$title = 'ご注文ありがとうございます。';
$header = 'From:'.$email;
$honbun = html_entity_decode($honbun,ENT_QUOTES,'UTF-8');
mb_language('Japanese');
mb_internal_encoding('UTF-8');
mb_send_mail('info@rokumarunouen.co.jp',$email,$title,$honbun,$header);

}
catch (Exception $e)
{
	print 'ただいま障害により大変ご迷惑をお掛けしております。';
	exit();
}

?>

    <br>
    <a href="shop_list.php">商品画面へ</a>

</body>

</html>