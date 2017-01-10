<?php 
   include_once "Config.php"; 
   // Pour la établire la connexion à la BDD
   require_once Path_index."includes/connexion_BDD.php";
   // Des fonctions Util
   include_once Path_index."includes/fonction_util.php";
   //Validation du formulaire d'inscription d'agent
    if(isset($_POST['form_motPasseOblie']))
    { 
        //Récupération des valeurs du formulaire
        $mail = escape_errors($_POST['mail']);
        $confirmationDeMail = escape_errors($_POST['confirmationDeMail']);
        $erreur = 0;
        //test sur les champs du formulaire
        if(!empty($_POST['mail']) AND !empty($_POST['confirmationDeMail']))
        {
            // test sur la confiramation du mail
            if($mail == $confirmationDeMail){ 
                // vérifier la validitée du mail
                if(filter_var($mail, FILTER_VALIDATE_EMAIL)){
                    // Vérifier l'unicité du mail dans la base de donnée
                    $requeteUniciteMail =  $BDD->prepare("SELECT idUser_crolles FROM crolles_user_connect_std WHERE email_user = ? ");
                    $requeteUniciteMail->execute(array($mail));
                    $mailExist = $requeteUniciteMail->rowCount();
                    if($mailExist == 1){
                        $requeteUniciteMail = $requeteUniciteMail->fetch();
                        $idUser_passe_oublie = $requeteUniciteMail['idUser_crolles'];
                        // Générer un code.
                        $code_passe_oublie = uniqid(rand(123451, 987659));
                        // mettre ajoure la base de donné
                        $BDD->exec("UPDATE crolles_user_connect_std SET code_passe_oublie='$code_passe_oublie' WHERE idUser_crolles='$idUser_passe_oublie'");
                        // l'envoie du Email pour générer un nouveau mot de passe à l'utilisateurs.
                        // le sujet du email
                        $sujet = "Génération d'un nouveaux mot de passe";
                        // Récupérer le nom du serveur. 
                        $SERVER_NAME = $_SERVER['SERVER_NAME'];
                        // Récuperer le http | https
                        $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME'];
                        //----------------------------------- Codage de l'url ----------------------------------------------------
                        $str = '$id_user="'.$idUser_passe_oublie.'";';
                        // Fonction qui code l'url à envoie aux autres page.
                        $lien_crypté = codage_url($str);
                        //--------------------------------------------------------------------------------------------------------
                        // Message du email.
                        $message = "Bonjour<br />
                                 Cliquez ici  crée votre nouveaux mot de passe
                                <a href ='$REQUEST_SCHEME://$SERVER_NAME/".RACINE_PATH_CONTROLEUR."/mdp_oublie/vue/form_nouveau_motDePasse.php?$lien_crypté&code_passe_oublie=$code_passe_oublie'>Nouveaux mot de passe</a><br /><br />";
                        //envoyer le mail
                        envoie_mail($mail, $sujet, $message);
                        $erreur = 24;
                    }else{
                        $erreur = 1; 
                    }
                }else{
                    $erreur = 2;
                }
            }else{
                        $erreur = 3;    
                    }
        }else{
            $erreur = 4;
        }
            Redirection(Path_vue."vue/form_motPasseOublie.php?erreur=".$erreur."");
    }
?>