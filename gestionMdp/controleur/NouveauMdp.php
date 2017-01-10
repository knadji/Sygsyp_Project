<?php 
   include_once "Config.php";
    // Pour la établire la connexion à la BDD
   require_once Path_index."includes/connexion_BDD.php";
   // Des fonctions Util
   include_once Path_index."includes/fonction_util.php";
   //
   //récupère l'adresse qui a été utilisée pour appeler la page
   @$str = decodage_url($_SERVER['REQUEST_URI']);
   //évalue le contenu, ça va donc évaluer $id_user_passeOublie="val1"
   eval($str);
   //Validation du formulaire d'inscription d'agent
    if(isset($_POST['form_creation_new_pass']))
    {
        //Récupération des valeurs du formulaire
        $new_passe = $_POST['new_passe'];
        $confirmation_duNewPasse =$_POST['confirmation_duNewPasse'];
        $erreur = 0;
        //test sur les champs du formulaire
        if(!empty($new_passe) AND !empty($confirmation_duNewPasse) AND !empty($id_user_passeOublie))
        {
            if(strlen($new_passe) > 6){
                $new_passe_crypter = hash("sha256",$new_passe);// '{SHA}' . base64_encode(sha1($new_passe, TRUE)); //
                $confirmation_duNewPasse_crypter = hash("sha256",$confirmation_duNewPasse); //'{SHA}' . base64_encode(sha1($confirmation_duNewPasse, TRUE)); //
                if($new_passe_crypter == $confirmation_duNewPasse_crypter){
                    
                    $BDD->exec("UPDATE crolles_user_connect_std SET password_user='$new_passe_crypter' WHERE idUser_crolles='$id_user_passeOublie'");
                    // le message num 1
                    $erreur = 100;
                }else{
                    $erreur = 11;
                }
            }else {
                $erreur =  12;
            }
        }else{
            $erreur = 4;
        }
        Redirection(Path_index."auth_identification/auth_bd/vue/form_connexion_user_db.php?erreur=".$erreur."");
    }
?>