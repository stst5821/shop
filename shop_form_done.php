<?php
    session_start();
    session_regenerate_id(true); // 合言葉を変える
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

// shop_form_check.phpでもサニタイズしているが、このページで受け取るデータ($_POST)は、まだサニタイズされていないデータなので、ここでもサニタイズが必要。
    $post = sanitize($_POST);
    $onamae = $post['onamae'];
    $email = $post['email'];
    $postal1 = $post['postal1'];
    $postal2 = $post['postal2'];
    $address = $post['address'];
    $tel = $post['tel'];

    print $onamae.'様<br>';
    print 'ご注文ありがとうございました。<br>';
    print $email.'にメールを送りましたのでご確認ください。<br>';
    print '商品は以下の住所に発送させて頂きます。<br>';
    print $postal1.'-'.$postal2.'<br>';
    print $address.'<br>';
    print $tel.'<br>';

    $honbun = "";
    $honbun .= $onamae."様\n\n このたびはご注文ありがとうございました。\n";
    $honbun .= "\n";
    $honbun .= "ご注文商品 \n";
    $honbun .= "---------------\n";

    $cart = $_SESSION['cart'];
    $kazu = $_SESSION['kazu'];
    $max = count($cart); //$cartの要素の数をカウントして、その数を$maxに入れている

    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '1234';
    $dbh = new PDO ($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    

}
catch(Exception $e)
{
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    exit();
}
?>

</body>

</html>