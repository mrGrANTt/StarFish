<?php 
function connect(
    $host='localhost',
    $user='user',
    $pass='admin',
    $dbname='bd'
){
    global $link;
    $link = mysqli_connect($host, $user, $pass);

    if (!$link) {
        die('Ошибка подключения: ' . mysqli_connect_error());
    }
    if (!mysqli_select_db($link, $dbname)) {
        die('Ошибка открытия базы данных: ' . mysqli_error($link));
    }
}

function register($user, $email, $pas) {
    $user = trim(htmlspecialchars($user));
    $email = trim(htmlspecialchars($email));
    $pas = md5(trim(htmlspecialchars($pas)));

    $ins = "INSERT into users(name, email, pass) values (\"".$user."\",\"".$email."\",\"".$pas."\");";
    $sel = "SELECT id FROM users WHERE name=\"".$user."\";";
    
    connect();
    global $link;

    mysqli_query($link, $ins);
    $err = mysqli_error($link);
    if($err != "") {
        echo $err."<br/>";
        ?>
            <p style="color: red;">Name or email alredy used!</p>
        <?php
        return false;
    }

    $res = mysqli_query($link, $sel);
    $err = mysqli_error($link);
    if($err != "") {
        echo $err."<br/>";
        ?>
            <p style="color: red;">Some think wrong... Call Administrator!</p>
        <?php
        return false;
    }

    $arr = mysqli_fetch_array($res, MYSQLI_NUM);
    
    if($arr) {
        $_SESSION['user'] = $user;
        $_SESSION['id'] = $arr[0];
        generate_def_info();
        return true;
    }
    
    return false;
}

function login($user, $pas) { 
    $user = trim(htmlspecialchars($user));
    $pas = md5(trim(htmlspecialchars($pas)));

    $ins = 'SELECT name, id FROM users WHERE pass="'.$pas.'" AND (name="'.$user.'" OR email="'.$user.'")';

    connect();
    global $link;
    $res = mysqli_query($link, $ins);
    $err = mysqli_error($link);
    if($err != "" || !$res) {
        return false;
    }
    $arr = mysqli_fetch_array($res, MYSQLI_NUM);
    
    if($arr) {
        $_SESSION['user'] = $arr[0];
        $_SESSION['id'] = $arr[1];
        return true;
    }
    return false;
}

function logout() {
    session_destroy();
}

function generate_def_info() {

    $user_id = $_SESSION['id'];
    $date = date("d.m.Y");

    $ins = [
        "INSERT INTO `pages` (`user_id`, `description`, `logo`, `upper_image`, `backgroung_image`, `contacts`) 
            VALUES ('".$user_id."', 'Accaunt created ".$date."!', 'image/logs/".$user_id.".png', 'image/uppers/".$user_id.".png', 'image/backgroungs/".$user_id.".png', 'No contact information')",
        "INSERT INTO `posts` (`user_id`, `title`, `text`, `post_date`) VALUES ('".$user_id."', 'Example 1', 'It\'s defualt example text!', '".$date."');",
        "INSERT INTO `posts` (`user_id`, `title`, `text`, `post_date`) VALUES ('".$user_id."', 'Example 2', 'It\'s second defualt example text!', '".$date."');",
        "INSERT INTO `posts` (`user_id`, `title`, `text`, `post_date`) VALUES ('".$user_id."', 'Example 3', 'It\'s defualt example text too!', '".$date."');"
    ];

    copy("image/logs/default.png","image/logs/".$user_id.".png");
    copy("image/uppers/default.png","image/uppers/".$user_id.".png");
    copy("image/backgroungs/default.png","image/backgroungs/".$user_id.".png");

    connect();
    global $link;
    foreach($ins as $que) {
        mysqli_query($link, $que);
        $err = mysqli_error($link);

        if($err != "") {
            echo $err;
            return false;
        }
    }


    return true;
}

function get_multy_line($strings, $prefix, $sufix) {
    foreach(explode("\n", $strings) as $v) {
        echo $prefix.$v.$sufix;
    }
}
?>