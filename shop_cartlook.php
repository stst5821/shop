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

    // $_SESSION['cart']にはこれまで選んできた商品がすべて入っている。それを$cartに代入している。
    if(isset($_SESSION['cart'])==true)
    {
        $cart = $_SESSION['cart'];
        $kazu = $_SESSION['kazu'];
        $max = count($cart); //$cartの要素の数をカウントして、その数を$maxに入れている
    } else {
        $max = 0; // $_SESSION['cart'] に何も入っていない場合、$maxには何も代入されないため下のif($max==0)文でエラーになる。そのため$maxに0を代入してエラーを回避している。
    }

    // カートの中身$maxが0なら、カートに何も入っていないということなので、以下を実行する。
    // また何も入ってないので、if文以降のスクリプトの実行は必要ないためexit()で抜けている。
    // 上記のelse文で$max=0にしていないと、ここでエラーになる。$max==0 で比較しようとしても$maxの中に何も入っていないと比較しようがないのでエラーとなる。
    if($max==0) {
        print 'カートに商品が入っていません。<br>';
        print '<br>';
        print '<a href="shop_list.php">商品一覧に戻る</a>';
        exit(); // これ以降のスクリプト($dsnから)は実行しない。
    }

    $dsn = 'mysql:dbname=shop;host=localhost;charset=utf8';
    $user = 'root';
    $password = '1234';
    $dbh = new PDO ($dsn,$user,$password);
    $dbh->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);

    // foreach文で、カートの中身を1つずつ取り出して処理している。
    foreach($cart as $key => $val)
    {
        $sql = 'SELECT name,price,gazou FROM mst_product WHERE code=?';
        $stmt = $dbh->prepare($sql);
        $data[0] = $val;
        $stmt->execute($data);
    
        $rec = $stmt->fetch(PDO::FETCH_ASSOC);

        $pro_name[] = $rec['name'];
        $pro_price[] = $rec['price'];
        
        if($rec['gazou'] == '')
        {
            $disp_gazou[] = '';
        }
        else
        {
            $pro_gazou[] = '<img src="../product/gazou/'.$rec['gazou'].'">';
        }
    }
    
    $dbn = null;

}
catch(Exception $e) 
{
    print 'ただいま障害により大変ご迷惑をおかけしております。';
    exit();
}

?>

    カートの中身<br>
    <br>

    <!-- ここでinputしたデータをkazu_change.phpに渡している。 -->
    <form method="post" action="kazu_change.php">
        <table border="1">
            <tr>
                <td>商品</td>
                <td>商品画像</td>
                <td>価格</td>
                <td>数量</td>
                <td>小計</td>
                <td>削除</td>
            </tr>
            <!-- 商品の情報(名前、画像、価格) を表示する。$i < $maxでカートの中に入っている商品数になるまでfor文を回している。-->
            <?php for($i=0; $i < $max; $i++) : ?>
            <tr>
                <td><?= $pro_name[$i]; ?></td>
                <td> <?= $pro_gazou[$i]; ?></td>
                <td> <?= $pro_price[$i]; ?></td>
                <!-- name="kazu～～は、商品毎の数を管理するため、kazuの後に$iをつけてkazu0,kazu1…とnameを変えている。-->
                <!-- 商品ごとにnameを変えないと、どの商品の数を変更すればいいのかわからないのでエラーになる。 -->
                <td><input type="text" name="kazu<?php print $i; ?>" value="<?php print $kazu[$i]; ?>"></td>

                <td><?= $pro_price[$i] * $kazu[$i]; ?>円</td>
                <td><input type="checkbox" name="sakujo<?php print $i; ?>"></td>
                <br>
            </tr>
            <?php endfor ?>
        </table>
        <input type="hidden" name="max" value="<?php print $max; ?>">
        <input type="submit" value="数量変更"><br>
        <!-- <input type="button" onclick="history.back()" value="戻る"> -->
        <a href="../shop/shop_list.php">トップページへ戻る</a>
    </form>

    <br>
    <a href="shop_form.html">ご購入手続きへ進む</a><br>

</body>

</html>