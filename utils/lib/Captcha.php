<?php 
    // creation du code 
    session_start();
    include_once "config.php";
    $captcha_rand = sha1(rand());
    $captcha_rand = substr($captcha_rand,0,4);
    $_SESSION['$captcha_rand'] = $captcha_rand;
    // creation de l'image
    // Tente d'ouvrire l'image
    $image_captcha = imagecreatefrompng(Path.'images\cap.png');
    // Couleur du texte dans l'image = Noir
    $text_color = imagecolorallocate($image_captcha,0,0,0);
    imagettftext($image_captcha,41,0,9,70,$text_color,Path.'images\COMICATE.ttf',$captcha_rand);
    //imagestring($image_captcha,10,50,5,$captcha_rand,$text_color);
    // affichage de l'image
    header('Content-type:image/png');
    imagepng($image_captcha);
?>
