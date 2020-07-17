<?php
session_start();
session_regenerate_id(true); // 合言葉を変える
if(isset($_SESSION['member_login'])==false)
{
    print 'ようこそゲスト様<br>';
    print'<a href="member_login.html">会員ログイン</a>';
    print '<br />';
}
else
{
    print 'ようこそ';
    print $_SESSION['member_name'];
    print '様<br>';
    print '<a href="member_logout.php">ログアウト</a>';
    print '<br>';
}
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

    // shop_product.phpからURLパラメータでprocodeを$_GETで受け取る。
    $pro_code = $_GET['procode'];

    // カートに値が入っている時だけ、$_SESSION['cart']の中身を$cartに移動させる。
    // SESSIONには1つのデータしか保存できないので、$cartに今のデータを移動させる。
    if(isset($_SESSION['cart']) == true) {
        $cart = $_SESSION['cart'];
        $kazu = $_SESSION['kazu'];

        // in_array('存在するか知りたいデータ,検索対象の配列名)
        if(in_array($pro_code,$cart)==true)
        {
            print 'その商品はすでにカートに入っています。<br>';
            print '<a href="shop_list.php">商品一覧に戻る</a>';
            exit();
        }
    }

    // $cart配列に、$_GETで取得した商品コードを追加する。
    $cart[] = $pro_code;
    $kazu[] = 1;

    // $cartに入れた配列をまた$_SESSION['cart']に戻してshop_productページへ戻ってもデータが消えないようにする。
    // 変数に入れた値は、ページを移動すると消えるが、$_SESSIONに入れた値は消えない。ということは絶対に覚えておく。
    $_SESSION['cart'] = $cart;
    $_SESSION['kazu'] = $kazu;
    
}
catch(Exception $e) 
{
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    exit();
}

?>

    カートに追加しました。<br>
    <br>
    <a href="shop_list.php">商品一覧に戻る</a>

</body>

</html>