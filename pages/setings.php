<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <title>Setings</title>
    </head>
    <body>
        <h1>Setings</h1>
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
                
                            <div>
                                <span>Text</span> 
                                <div>
                                    <span>Description</span> 
                                    <?php
                                        echo "<textarea type=\"text\" placeholder=\"description\" name=\"description\"  maxlength=\"255\">".$arr[0]."</textarea> ";
                                    ?>
                                </div>
                                <div>
                                    <span>Contacts</span> 
                                    <?php
                                        echo "<textarea type=\"text\" placeholder=\"contacts\" name=\"contacts\"  maxlength=\"255\">".$arr[1]."</textarea> ";
                                    ?>
                                </div>
                            </div>
                
                             
                            <div>
                                <span>Files</span> 
                                <div>
                                    <span>Logo(200:200): </span>
                                    <input type="file" name="logo"/> 
                                </div>
                                <div>
                                    <span>Upper image(900:300): </span>
                                    <input type="file" name="upper_image"/> 
                                </div>
                                <div>
                                    <span>Backgroung image(2500:Any): </span>
                                    <input type="file" name="backgroung_image" /> 
                                </div>
                            </div>
                    <?php
                }
            }
            ?>
                <div>
                    <span id="posts-title">Posts</span> 
                    <input type="hidden" id="removabel" name="removabel" />
                    <div>
                        <button id="adder" type="button">Add</button>
                        <button id="remover" type="button">Remove</button>
                    </div>
                    <div id="list">
                    <?php

                    $sel = 'SELECT title, text, id from posts where user_id='.$_SESSION['id'].';';
                    //               0     1    2 

                    $res = mysqli_query($link, $sel);
                    if($res) {
                        $counter = 0;
                        while($arr = mysqli_fetch_array($res, MYSQLI_NUM)) {
                            $counter++;

                            echo "<div>
                                <input type=\"text\" name=\"title".$counter."\" placeholder=\"title\" value=\"".$arr[0]."\" maxlength=\"30\" />
                                <textarea type=\"text\" name=\"text".$counter."\" placeholder=\"text\" maxlength=\"255\">".$arr[1]."</textarea>
                                <input type=\"hidden\" name=\"post".$counter."_id\" value=\"".$arr[2]."\" />
                            </div>";
                        }
                    ?>
                    </div>
                </div>
                    <div>
                        <input type="submit" name="cansel" value="Cansel"/>
                        <input type="submit" name="save" value="Save"/>
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

                        echo "<script> window.location=document.URL; </script>";
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
                }
                if(isset($_POST['cansel'])) {
                    echo "<p>Chenge canseled!</p>";
                }
            } else {
                header("Location: ?");
            }
        ?>
    </body>
</html>