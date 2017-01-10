<?php
    define('Path_index', '../');
    // Récupérer le nom du dossier contenant le site
    $RACINE = explode('/', $_SERVER['REQUEST_URI']);
    define('RACINE_PATH_CONTROLEUR',$RACINE[1]);
?>