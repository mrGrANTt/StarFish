<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Setings</title>
        <link rel="stylesheet" type="text/css" href="pages/setings.css"/>
    </head>
    <body>
    <?php echoHome(); ?>
        <div class="setings-menu">
            <div class="main-title">
                <h1>Setings</h1>
            </div>
            <?php 
                if(isset($_SESSION['id'])) {

                global $link;
                connect();

                $sel = 'SELECT description, contacts from pages where user_id='.$_SESSION['id'].';';
                //                0           1

                $res = mysqli_query($link, $sel);
                if($res) {
                    $arr = mysqli_fetch_array($res, MYSQLI_NUM);
                    if($arr) {
                        ?>
                            <form method="post" enctype="multipart/form-data">
                    
                                <div class="form-section text">
                                    <span class="title">Text</span> 
                                    <div class="input-group">
                                        <div class="input-group-title">
                                            <span>Description</span> 
                                        </div>
                                        <div class="input-group-text">
                                            <?php
                                                echo "<textarea type=\"text\" placeholder=\"description\" name=\"description\"  maxlength=\"255\">".$arr[0]."</textarea> ";
                                            ?>
                                        </div>
                                    </div>
                                    <div class="input-group">
                                        <div class="input-group-title">
                                            <span>Contacts</span> 
                                        </div>
                                        <div class="input-group-text">
                                            <?php
                                                echo "<textarea type=\"text\" placeholder=\"contacts\" name=\"contacts\"  maxlength=\"255\">".$arr[1]."</textarea> ";
                                            ?>
                                        </div>
                                        <div class="input-group-text-help">
                                            <p>new line - new contact</p>
                                        </div>
                                    </div>
                                </div>
                    
                                
                                <div class="form-section files">
                                    <span class="title">Files</span> 
                                    <div class="file-upload">
                                        <div class="file-title">
                                            <span>Logo (200:200) </span>
                                        </div>
                                        <div class="file-uploder">
                                            <input type="file" name="logo"/> 
                                        </div>
                                    </div>
                                    <div class="file-upload">
                                        <div class="file-title">
                                            <span>Upper image (900:300) </span>
                                        </div>
                                        <div class="file-uploder">
                                            <input type="file" name="upper_image"/> 
                                        </div>
                                    </div>
                                    <div class="file-upload">
                                        <div class="file-title">
                                            <span>Backgroung image (2500:Any) </span>
                                        </div>
                                        <div class="file-uploder">
                                            <input type="file" name="backgroung_image"/> 
                                        </div>
                                    </div>
                                </div>
                        <?php
                    }
                }
                ?>
                    <div class="form-section posts">
                        <span id="posts-title" class="title">Posts</span> 
                        <input type="hidden" id="removabel" name="removabel" />
                        <div class="button-group">
                            <button id="adder" type="button">Add</button>
                            <button id="remover" type="button">Remove</button>
                        </div>
                        <div id="list" class="post-list">
                        <?php

                        $sel = 'SELECT title, text, id from posts where user_id='.$_SESSION['id'].';';
                        //               0     1    2 

                        $res = mysqli_query($link, $sel);
                        if($res) {
                            $counter = 0;
                            while($arr = mysqli_fetch_array($res, MYSQLI_NUM)) {
                                $counter++;

                                echo "<div class=\"post-item\">
                                    <input type=\"text\" name=\"title".$counter."\" placeholder=\"title\" value=\"".$arr[0]."\" maxlength=\"30\" />
                                    <textarea type=\"text\" name=\"text".$counter."\" placeholder=\"text\" maxlength=\"255\">".$arr[1]."</textarea>
                                    <input type=\"hidden\" name=\"post".$counter."_id\" value=\"".$arr[2]."\" />
                                </div>";
                            }
                        ?>
                    </div>
                    </div>
                        <div class="form-actions">
                            <input class="save" type="submit" name="save" value="Save"/>
                            <input class="cans" type="submit" name="cansel" value="Cansel"/>
                        </div>
                    </form>
                    <script src="pages\setings.js"></script>
                
                <?php 
                    }
                    if(isset($_POST['save'])) {

                        if(isset($_FILES["logo"])) {
                            move_uploaded_file($_FILES["logo"]["tmp_name"], "image/logs/".$_SESSION['id'].".png");
                        }
                        if(isset($_FILES["upper_image"])) {
                            move_uploaded_file($_FILES["upper_image"]["tmp_name"], "image/uppers/".$_SESSION['id'].".png");
                        }
                        if(isset($_FILES["backgroung_image"])) {
                            move_uploaded_file($_FILES["backgroung_image"]["tmp_name"], "image/backgroungs/".$_SESSION['id'].".png");
                        }

                        $rep = 'UPDATE pages SET description = "'.$_POST['description'].'", contacts = "'.$_POST['contacts'].'" WHERE user_id='.$_SESSION['id'].';';

                        mysqli_query($link, $rep);
                        $err = mysqli_error($link); 
                        if($err != "") {
                            echo $err."<br/>";
                        } else {

                            $counter = 1;
                            while(isset($_POST['title'.$counter])) {
                                if(isset($_POST['post'.$counter.'_id'])) {
                                    $rep = 'UPDATE posts SET title = "'.$_POST['title'.$counter].'", text = "'.$_POST['text'.$counter].'", post_date = "'.(date("d.m.Y")).'"
                                        WHERE id=\''.$_POST['post'.$counter.'_id'].'\' AND (title != "'.$_POST['title'.$counter].'" OR text != "'.$_POST['text'.$counter].'");';
                                    
                                } else {
                                    $rep = 'INSERT into posts(user_id, title, text, post_date) 
                                        VALUES ('.$_SESSION['id'].',"'.$_POST['title'.$counter].'","'.$_POST['text'.$counter].'","'.(date("d.m.Y")).'");';
                                }
                                mysqli_query($link, $rep);
                                $err = mysqli_error($link);
                                if($err != "") {
                                    echo $err."<br/>";
                                }
                                $counter++;
                            }

                        }

                        if($_POST['removabel'] != "") {
                            $indexs = explode(" ", $_POST['removabel']);
                            
                            foreach($indexs as $ind) {
                                if($ind != "") {
                                    $ins = 'DELETE FROM posts WHERE id = '.$ind;
                                    
                                    mysqli_query($link, $ins);
                                    $err = mysqli_error($link);
                                    if($err != "") {
                                        echo $err."<br/>";
                                    }
                                }
                            }
                        }

                        $_SESSION['saved'] = 1;
                        echo "<script> window.location=document.URL; </script>";
                    }
                    if(isset($_POST['cansel'])) {
                        $_SESSION['saved'] = 2;
                        echo "<script> window.location=document.URL; </script>";
                    }


                    if (!isset($_POST['save']) && isset($_SESSION['saved']) && $_SESSION['saved'] == 1) {
                        echo "<p style=\"color: green;\">Chenge saved!</p>";
                        $_SESSION['saved'] = 0;
                    } 
                    if (!isset($_POST['cansel']) && isset($_SESSION['saved']) && $_SESSION['saved'] == 2) {
                        echo "<p style=\"color: red;\">Chenge canseled!</p>";
                        $_SESSION['saved'] = 0;
                    } 
                } else {
                    header("Location: ?");
                }
            ?>
        </div>
    </body>
</html>
