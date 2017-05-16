<?php

ini_set('display_errors', 1); 
error_reporting(E_ALL);
session_start();


if(empty($_SESSION))
    $_SESSION['page'] = 1;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $_SESSION['page'] ++;
}

$ch = curl_init("http://jbzd.pl/strona/" . $_SESSION['page']);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_BINARYTRANSFER, true);
$content = curl_exec($ch);




?>

<!DOCTYPE html>
<html>
    <head>
        <style>
            #container{
                width: 100%;
                height: 100%;
            }
        </style>
        <script src="https://code.jquery.com/jquery-1.12.4.min.js" integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>
        
    </head>
    <body>
        <embed id="iframe-jb">
            <?php echo $content; ?>
            
        </embed>
        
        <div id="container">
            
        </div>

        <script>
            
            var url = 'http://jbzd.pl/strona/1';
            
            $(window).load(function(){
                console.log('Start~');
                var max_scroll = document.body.offsetHeight - document.documentElement.clientHeight;
                var scroll = 0;
                setTimeout(function(){
                    var scroll_interval = setInterval(function(){
                        document.body.scrollTop = scroll;
                        scroll += 1;

                        if(scroll >= max_scroll){
                            clearInterval(scroll_interval);
                            nextPage();
                        }

                    }, 10);
                }, 2000);
                
                function nextPage(){
                    console.log('GO tO VEXT PAGE');
                    $.ajax({
                        method: 'POST',
                        url: '/jb.php',
                        success: function(){
                            window.location.reload();
                        }
                    });
                    
                }
                
            });

        </script>
    </body>
</html>









<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */

echo 'sprd';