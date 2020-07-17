<?php

// セッションを開始するために必ずこの2行が必要。
session_start();
session_regenerate_id(true);

// sanitize関数が書かれている、common.phpを読み込んでいる。
require_once('../common/common.php');

// タグなどを無効化する処理をして、$postに代入している。
$post = sanitize($_POST);

$max = $post['max'];

// 数量変更フォーム ------------------------------------------------------------------------------------------------------------

for($i=0; $i<$max; $i++) 
{
    // 半角数字では無ければ、0が入るため、==0 がtrueになりif文の中身が実行される
    if(preg_match("/\A[0-9]+\z/",$post['kazu'.$i]) == 0)
    {
        print '数量に誤りがあります。<br>';
        print '<a href="shop_cartlook.php">カートに戻る</a>';
        exit();
    }
    
    // 数を0個にしたり、10を超える数字を入れたりした場合にエラーを出すようにする。↓の場合、0か11以上を入力するとエラー文が表示される。
    if($post['kazu'.$i] < 1 || 10 < $post['kazu'.$i]) 
    {
        print '数量は1個以上、10個までです。';
        print '<a href="shop_cartlook.php">カートに戻る</a>';
        exit();
    }
    $kazu[]=$post['kazu'.$i];
}

// 削除フォーム ------------------------------------------------------------------------------------------------------------

$cart = $_SESSION['cart']; // 商品コードが入っている。
print_r($_SESSION['cart']);

// 配列を0番目から順に削除していくと、後の番号が繰り上がって意図していないものが消されるため、配列の一番後ろから消していくようにする。
// そのため、$iに$max(配列に入っている数)を代入、forを回す度に$i--をして、0になるまで(0 <= $i)繰り返すというfor文を書いている。
for($i=$max; 0<=$i; $i--) {
    if(isset($_POST['sakujo'.$i])==true)
    {
        array_splice($cart,$i,1); // $cart配列の、$i番目を1つ削除する
        array_splice($kazu,$i,1);
    }
}

// 削除処理が終わったら、また$_SESSIONに入れて、shop_cartlook.phpにデータを持っていく
$_SESSION['cart'] = $cart;
$_SESSION['kazu'] = $kazu;

header('Location:shop_cartlook.php');
exit();

?>