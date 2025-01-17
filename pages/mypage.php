<!DOCTYPE html>
<html>
    <?php 
        connect();
        global $link;
    
        if($_GET['id'] == 'boo') {
            header("Location: https://parad1st.github.io/Screamer/");//litle joke
        } else {
            $sel = 'select name, description, logo, upper_image, backgroung_image, contacts, description_color, contacts_color from pages INNER JOIN users on users.id=pages.user_id where pages.id='.$_GET['id'];
//                          0          1        2        3              4             5           6                    7
            $res_set = mysqli_query($link, $sel);
            if($res_set == false) {
                include_once("err-page.html");
            } else {

                $res = mysqli_fetch_array($res_set, MYSQLI_NUM);
                if(is_null($res)) {
                    include_once("err-page.html");
                } else {
                    ?>
                        <head>
                            <?php 
                                echo "<title>".$res[0]."'s page</title>";
                            ?>
                            <link rel="stylesheet" type="text/css" href="pages/mypage.css"/>
                        </head>
                            <?php 
                                echo "<body style=\"background-image: url('".$res[4]."');\">";
                            ?>
                            <div class="my-page">
                                <div class="upper">
                                    <?php 
                                        echo "<img src=\"".$res[3]."\" class=\"upper-img\" draggable=\"false\"/><br/>";
                                        echo "<img src=\"".$res[2]."\" class=\"logo-img\" align=\"left\" draggable=\"false\"/>";
                                        echo "<h1 class=\"title\" style=\"color: ".$res[6].";\">Hello, I'm - ".$res[0]."</h1>";
                                        get_multy_line($res[1], "<p class=\"description\" style=\"color: ".$res[6].";\">", "</p>");
                                    ?>
                                </div>
                                <br/>
                                <div class="body">
                                    <?php echoHome(); ?>
                                    <ul class="posts">
                                        <?php 
                                            $sel2 = 'SELECT title, text, post_date, color from posts WHERE user_id='.$_SESSION['id'];
                                            //               0       1       2       3
                                            $posts = mysqli_query($link, $sel2);

                                            while($post=mysqli_fetch_array($posts, MYSQLI_NUM)) {
                                                if($post[0] != "" || $post[1] != "") {
                                                    echo '<li>
                                                        <div class="post">
                                                            <h2 class="post-title" style="color: '.$post[3].';">'.$post[0].'</h2>
                                                            <p class="post-text" style="color: '.$post[3].';">'.$post[1].'</p>
                                                            <h4 class="post-date" style="color: '.$post[3].';">'.$post[2].'</h4>
                                                        </div>
                                                    </li><br/>';
                                                }
                                                else {
                                                    echo "<br/><br/><br/>";
                                                }
                                            }
                                        ?>
                                    </ul>
                                </div>
                                <br/>
                                <div class="contacts">
                                    <?php 
                                        echo "<h3 class=\"contacts-title\" style=\"color: ".$res[7].";\">Контакты:</h3>";
                                        get_multy_line($res[5], "<span class=\"contact\" style=\"color: ".$res[7].";\">", "</span>");
                                    ?>
                                </div>
                            </div>
                        </body>
                    <?php
                }
            }
        }
    
    ?>
</html>
<script>
  window.addEventListener('scroll', function () {
    const docHeight = document.body.scrollHeight - window.innerHeight;
    const scrollPercent = window.scrollY / docHeight;

    document.body.style.backgroundPositionY = (scrollPercent * 100) + '%';
  });
</script>