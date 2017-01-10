<?php
//Connexion avec la base de donnée
    try
    {
        $BDD = new PDO('mysql:host=127.0.0.1;dbname=mydb','root','');
        $BDD->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    }
    catch (Eception $e)
    {
        die('Erreur : '.$e.getMessage());
    }
?>