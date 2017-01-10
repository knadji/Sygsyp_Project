<?php 
    include_once "Config.php";
    include_once Path_index."includes/header.php";
    include_once Path_index."includes/fonction_util.php";
    require_once Path_index."includes/connexion_BDD.php";
    
    //------------------------------------------------------Validation par mail utilisateur habitant-----------------------------------------------------

    //récupère l'adresse qui a été utilisée pour appeler la page
    @$str = decodage_url($_SERVER['REQUEST_URI']);
    //évalue le contenu, ça va donc évaluer $id_user="val1"
    eval($str);
    if(!empty($id_user)){
     if(isset($id_user) AND isset($_GET['code'])){
        $code = $_GET['code'];
        $requete = $BDD->query("SELECT * FROM crolles_user_connect_std WHERE idUser_crolles='$id_user' AND code_mail_inscription='$code'");
        $Nb_user_Exist = $requete->rowCount();
        if($Nb_user_Exist == 1){
            // activer utilisateurs.
            $BDD->exec("UPDATE crolles_user_connect_std SET compte_active='1' WHERE idUser_crolles='$id_user'");
            Redirection(Path_index."auth_identification/auth_bd/vue/form_connexion_user_db.php");
        }else{
            echo "Code d'activation invalide.";
        } 
      }
    }else echo "Informations introuvables";
?>