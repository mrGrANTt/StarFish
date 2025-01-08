<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <?php
            echo "<title>".(isset($_GET['reg'])? "Registration" : "Login")."</title>"
        ?>
        <link rel="stylesheet" type="text/css" href="pages/registration.css"/>
    </head>
    <body>
        <div class="center-menu">
            <div class="center-contener">
                <?php
                    if(!isset($_SESSION['user'])) {
                        if(isset($_GET['reg'])) {
                            ?>
                                <div class="upper">
                                    <a href="?" class="a-log unselect">Login</a>
                                    <a href="?reg" class="a-reg">Registration</a>
                                </div>
                                <div class="reg-form">
                                    
                                    <form method="POST">
                                        <?php
                                            echo "<div><input type=\"text\" name=\"user\" placeholder=\"user\" value=\"".(isset($_POST['user'])? $_POST['user'] : "")."\" class=\"inputer\"/> </div>";
                                            echo "<div><input type=\"email\" name=\"email\" placeholder=\"email\" value=\"".(isset($_POST['email'])? $_POST['email'] : "")."\" class=\"inputer\"/></div>";
                                        ?>
                                        <div><input type="password" name="pas1" placeholder="password" class="inputer"/></div>
                                        <div><input type="password" name="pas2" placeholder="confirm password" class="inputer"/></div>
                                        
                                        <?php
                                            if(isset($_POST['reg'])) {
                                                if($_POST['pas1'] == $_POST['pas2']) {
                                                if (register($_POST['user'],$_POST['email'],$_POST['pas1'])) {
                                                    ?>
                                                        <script>
                                                            window.location=document.URL;
                                                        </script>
                                                    <?php
                                                }
                                                } else {
                                                    ?>
                                                        <p style="color: red;">Password must match!</p>
                                                    <?php
                                                }
                                            }
                                        ?>

                                        <div class="reg-decorator1 decorator"></div>
                                        <div class="sub-conteiner"><button type="submit" name="reg" class="submit-btn">Registration -></button></div>
                                        <div class="reg-decorator2 decorator"></div>
                                    </form>
                                </div>
                            <?php
                        } else {
                            ?>
                                <div class="upper">
                                    <a href="?" class="a-log">Login</a>
                                    <a href="?reg" class="a-reg unselect">Registration</a>
                                </div>
                                <div class="reg-form">
                                    <form method="POST">
                                        <?php
                                            echo "<div><input type=\"text\" name=\"user\" placeholder=\"user or email\" value=\"".(isset($_POST['user'])? $_POST['user'] : "")."\" class=\"inputer\"/> </div>";
                                        ?>
                                        <div><input type="password" name="pas" placeholder="password" class="inputer"/></div>

                                        <?php
                                            if(isset($_POST['log'])) {
                                                if (login($_POST['user'],$_POST['pas'])) {
                                                    ?>
                                                        <script>
                                                            window.location=document.URL;
                                                        </script>
                                                    <?php
                                                } else {
                                                    ?>
                                                        <p style="color: red;">Invalid user or password!</p>
                                                    <?php   
                                                }
                                            }
                                        ?>

                                        <div class="log-decorator1 decorator"></div>
                                        <div class="sub-conteiner"><button type="submit" name="log" class="submit-btn">Login -></button></div>
                                        <div class="log-decorator2 decorator"></div>
                                    </form>
                                </div>
                            <?php
                        }
                    } else {
                        ?>
                            <form method="POST">
                                <div class="account-menu">
                                    <?php 
                                        echo "<p class=\"account-menu-text\" style=\"color: green;\">Now you are ".$_SESSION['user']."!</p>";
                                        
                                        global $link;
                                        connect();
                                        $sel = 'SELECT id FROM pages WHERE user_id="'.$_SESSION['id'].'";';
                                        $res = mysqli_query($link, $sel);
                                        $err = mysqli_error($link); 
                                        if($err != "") {
                                            echo $err."<br/>";
                                        } else {
                                            if($res) {
                                                echo "<p class=\"your-pages-title\">Your pages: </p>";
                                                while($arr = mysqli_fetch_array($res, MYSQLI_NUM)) {
                                                    echo "<p class=\"your-pages-text\"><a href=\"?id=".$arr[0]."\">Page with id ".$arr[0]."</a></p>";
                                                }
                                            } else {
                                                echo "<p class=\"account-menu-text\" style=\"color: red;\">Cant find your page!</p>";
                                            }
                                        }

                                    ?>
                                    <p>You can configure your page in <a href="?setings">Setings page</a></p>
                                    <button class="submit-btn" type="submit" name="logout">Logout</button>
                                </div>
                                
                            </form>
                        <?php
                        if(isset($_POST['logout'])) {
                            logout();
                            ?>
                                <script>
                                    window.location=document.URL;
                                </script>
                            <?php
                        }
                    }
                ?>
            </div>
        </div>
    </body>
</html>
