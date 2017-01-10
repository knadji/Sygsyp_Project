<?php 
   include_once "Config.php"; 
   // Pour la �tablire la connexion � la BDD
   require_once Path_index."includes/connexion_BDD.php";
   // Des fonctions Util
   include_once Path_index."includes/fonction_util.php";
   //Validation du formulaire d'inscription d'agent
    if(isset($_POST['form_motPasseOblie']))
    { 
        //R�cup�ration des valeurs du formulaire
        $mail = escape_errors($_POST['mail']);
        $confirmationDeMail = escape_errors($_POST['confirmationDeMail']);
        $erreur = 0;
        //test sur les champs du formulaire
        if(!empty($_POST['mail']) AND !empty($_POST['confirmationDeMail']))
        {
            // test sur la confiramation du mail
            if($mail == $confirmationDeMail){ 
                // v�rifier la validit�e du mail
                if(filter_var($mail, FILTER_VALIDATE_EMAIL)){
                    // V�rifier l'unicit� du mail dans la base de donn�e
                    $requeteUniciteMail =  $BDD->prepare("SELECT idUser_crolles FROM crolles_user_connect_std WHERE email_user = ? ");
                    $requeteUniciteMail->execute(array($mail));
                    $mailExist = $requeteUniciteMail->rowCount();
                    if($mailExist == 1){
                        $requeteUniciteMail = $requeteUniciteMail->fetch();
                        $idUser_passe_oublie = $requeteUniciteMail['idUser_crolles'];
                        // G�n�rer un code.
                        $code_passe_oublie = uniqid(rand(123451, 987659));
                        // mettre ajoure la base de donn�
                        $BDD->exec("UPDATE crolles_user_connect_std SET code_passe_oublie='$code_passe_oublie' WHERE idUser_crolles='$idUser_passe_oublie'");
                        // l'envoie du Email pour g�n�rer un nouveau mot de passe � l'utilisateurs.
                        // le sujet du email
                        $sujet = "G�n�ration d'un nouveaux mot de passe";
                        // R�cup�rer le nom du serveur. 
                        $SERVER_NAME = $_SERVER['SERVER_NAME'];
                        // R�cuperer le http | https
                        $REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME'];
                        //----------------------------------- Codage de l'url ----------------------------------------------------
                        $str = '$id_user="'.$idUser_passe_oublie.'";';
                        // Fonction qui code l'url � envoie aux autres page.
                        $lien_crypt� = codage_url($str);
                        //--------------------------------------------------------------------------------------------------------
                        // Message du email.
                        $message = "Bonjour<br />
                                 Cliquez ici  cr�e votre nouveaux mot de passe
                                <a href ='$REQUEST_SCHEME://$SERVER_NAME/".RACINE_PATH_CONTROLEUR."/mdp_oublie/vue/form_nouveau_motDePasse.php?$lien_crypt�&code_passe_oublie=$code_passe_oublie'>Nouveaux mot de passe</a><br /><br />";
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