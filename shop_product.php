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
    // shop_list.phpの<a href="shop_product.php?procode=からパラメータを$_GETで受取り、$pro_codeに代入している。 
    $pro_code = $_GET['procode'];

    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '1234';
    $dbh = new PDO ($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    $sql='SELECT name,price,gazou FROM mst_product WHERE code=?';
    $stmt=$dbh->prepare($sql);
    $data[]=$pro_code;
    $stmt->execute($data);

    $rec=$stmt->fetch(PDO::FETCH_ASSOC);
    $pro_name=$rec['name'];
    $pro_price=$rec['price'];
    $pro_gazou_name=$rec['gazou'];

    $dbn = null;
    
    if($pro_gazou_name=='') // 画像が無ければなにも表示しない。有れば表示させる。
    {
        $disp_gazou='';
    }
    else
    {
        $disp_gazou='<img src="../product/gazou/'.$pro_gazou_name.'">';
    }
    // shop_cartin.phpに$pro_codeをパラメータとして送る。
    print '<a href="shop_cartin.php?procode='.$pro_code.'">カートに入れる</a><br /><br />';
    
}
catch(Exception $e) 
{
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    exit();
}

?>

    商品情報参照<br />
    <br />
    商品コード<br />
    <?php print $pro_code; ?>
    <br />
    商品名<br />
    <?php print $pro_name; ?>
    <br />
    価格<br />
    <?php print $pro_price; ?>円
    <br />
    <?php print $disp_gazou; ?>
    <br />
    <br />
    <form>
        <input type="button" onclick="history.back()" value="戻る">
    </form>

</body>

</html>