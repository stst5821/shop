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


require_once('../common/common.php');

    $post = sanitize($_POST);
    $onamae = $post['onamae'];
    $email = $post['email'];
    $postal1 = $post['postal1'];
    $postal2 = $post['postal2'];
    $address = $post['address'];
    $tel = $post['tel'];

    $preg_email = "/\A([a-zA-Z0-9])+([a-zA-Z0-9\._-])*@([a-zA-Z0-9_-])+([a-zA-Z0-9\._-]+)+\z/";
    $preg_postal = "/\A[0-9]+\z/";
    $preg_tel = "/\A[0-9]{2,4}-[0-9]{2,4}-[0-9]{3,4}\z/";

    // 入力した情報に1つでもエラーがあったら、入力画面に戻すようにする。
    $okflg = true; // 初期化。ここではtrueに設定。

    // ここのif文から、1つでもエラーがあれば、$okflgがfalseになる。
    if($onamae == "")
    {
        print '名前を入力してください。<br>';
        $okflg = false;
    }
    else
    {
        print 'お名前<br>';
        print $onamae;
        print '<br><br>';
    }

    if(preg_match($preg_email,$email) == 0)
    {
        print 'メールアドレスを正確に入力してください。<br>';
        $okflg = false;
    }
    else
    {
        print 'メールアドレス<br>';
        print $email;
        print '<br><br>';
    }

    if(preg_match($preg_postal,$postal1) == 0)
    {
        print '郵便番号は半角数字で入力してください。<br>';
        $okflg = false;
    }
    else
    {
        print '郵便番号<br>';
        print $postal1;
        print '-';
        print $postal2;
        print '<br><br>';
    }

    if(preg_match($preg_postal,$postal2) == 0)
    {
        print '郵便番号は半角数字で入力してください。<br>';
        $okflg = false;
    }

    if($address == "")
    {
        print '住所が入力されていません。<br>';
        $okflg = false;
    }
    else
    {
        print '住所<br>';
        print $address;
        print '<br><br>';
    }

    if(preg_match($preg_tel,$tel) == 0)
    {
        print '電話番号を正確に入力してください。<br>';
        $okflg = false;
    }
    else
    {
        print '電話番号<br>';
        print $tel;
        print '<br><br>';
    }

    // ここまでのif文でエラーがなければ、$okflgはtrueのままなので中身が実行される。
    if($okflg == true)
    {
        // $_POSTに入っているデータを、shop_form_doneに持っていく。このデータはサニタイズ前のデータなので、done.phpでサニタイズをしなければならない。
        print '<form method="post" action="shop_form_done.php">';
        print '<input type="hidden" name="onamae" value="'.$onamae.'">';
        print '<input type="hidden" name="email" value="'.$email.'">';
        print '<input type="hidden" name="postal1" value="'.$postal1.'">';
        print '<input type="hidden" name="postal2" value="'.$postal2.'">';
        print '<input type="hidden" name="address" value="'.$address.'">';
        print '<input type="hidden" name="tel" value="'.$tel.'">';
        print '<input type="button" onclick="history.back()" value="戻る">';
        print '<input type="submit" value="OK"><br>';
    }
    else // 1つでもエラーがあれば、$okflgはfalseになっているので、elseの中身が実行される。
    {
        print '<form>';
        print '<input type="button" onclick="history.back()" value="戻る">';
        print '</form>';
    }

?>

    </form>

</body>

</html>