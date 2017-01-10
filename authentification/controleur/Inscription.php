<?php
session_start();
include_once "Config.php";
// Pour la établire la connexion à la BDD
require_once Path_index."utils/fonction/ConnexionBdd.php";
// Des fonctions Util
include_once Path_index."utils/fonction/FonctionUtil.php";
//Validation du formulaire d'inscription d'agent
if(isset($_POST['formInscription']))
{
	//Récupération des valeurs du formulaire
	$pseudo = addslashes($_POST['pseudo']);
	$mail = escape_errors($_POST['mail']);
	$confirmationDeMail = escape_errors($_POST['confirmationDeMail']);
	$motDePasse = hash("sha256",$_POST['motDePasse']); // '{SHA}' . base64_encode(sha1($_POST['motDePasse'], TRUE));
	$confirmationDePasse = hash("sha256",$_POST['confirmationDePasse']); // '{SHA}' . base64_encode(sha1($_POST['confirmationDePasse'], TRUE));
	// génération du random pour Apache
	$bytes = openssl_random_pseudo_bytes(2);
	$rand = bin2hex($bytes);
	//
	$captcha_user = escape_errors($_POST['captcha']);
	$pseudoLong = strlen($pseudo);
	$erreur = 0;
	$générer_lien = false;
	//test sur les champs du formulaire
	if(!empty($pseudo) AND !empty($mail) AND
			!empty($confirmationDeMail) AND !empty($motDePasse)AND
			!empty($confirmationDePasse) AND !empty($captcha_user))
	{
		if($pseudoLong <= 255){ // testsur la longeur du prénom
			if($mail == $confirmationDeMail){ // test sur la confiramation du mail
				if(filter_var($mail, FILTER_VALIDATE_EMAIL)){// vérifier la validitée du mail
					// Vérifier l'unicité du mail dans la base de donnée'
					$requeteUniciteMail =  $BDD->prepare("SELECT pseudo_user FROM crolles_user_connect_std WHERE  email_user = ? ");
					$requeteUniciteMail->execute(array($mail));
					$mailExist = $requeteUniciteMail->rowCount();
					if($mailExist == 0){
						//test sur la confirmation du mot de passe
						if($motDePasse == $confirmationDePasse AND strlen($motDePasse)>=6){
							if(strtolower($captcha_user) == strtolower($_SESSION['$captcha_rand'])){
								// une variable qui permet de dire est ce que je génére un lien ou pas.
								$générer_lien = true;
								// Générer un code d'activation utilisre pour la validation de l'inscription.
								$code = uniqid(rand(123451, 987659));
								// récuperer la date du systéme.
								date_default_timezone_set("Europe/Paris");
								$date = date('y-m-d H:i:s');
								// insertion d'un habitant dans la base de donnée.
								$insertAgent = $BDD->prepare("INSERT INTO crolles_user_connect_std(
                                                                                                       id_rand,
                                                                                                       date_crea_user,
                                                                                                       last_connect_user,
                                                                                                       pseudo_user,
                                                                                                       email_user,
                                                                                                       password_user,
                                                                                                       code_mail_inscription)
                                                                                                       VALUES (?,?,?,?,?,?,?)");
								$insertAgent->execute(array($rand, $date, $date, $pseudo, $mail, $motDePasse, $code));
								// l'envoir du Email d'activation à l'utilisateurs.
								// Retourne l'identifiant généré par la dernière requête
								$dernier_id=$BDD->lastInsertId();
								// le sujet du email
								$sujet = "Test activation du compte";
								// récuperer le nom du serveur
								$HTTP_HOST = $_SERVER['HTTP_HOST'];
								// Récuperer le http | https
								$REQUEST_SCHEME = $_SERVER['REQUEST_SCHEME'];
								//----------------------------------- Codage de l'url ----------------------------------------------------
								$str = '$id_user="'.$dernier_id.'";';
								// Fonction qui code l'url à envoie aux autres page.
								$lien_crypté = codage_url($str);
								//--------------------------------------------------------------------------------------------------------
								$message = "Bonjour<br />
								Cliquez ici pour activer votre compte
								<a href ='$REQUEST_SCHEME://$HTTP_HOST/".RACINE_PATH_CONTROLEUR."/module_mail/validation_par_mail.php?$lien_crypté&code=$code'>Activer</a><br /><br />";
								envoie_mail($mail, $sujet, $message);
								//
								$erreur = 10;
							}else $erreur = 18;
						}else if ($motDePasse != $confirmationDePasse){
							$erreur = 11;
						}else{
							$erreur = 12;
						}
					}else{
						$erreur = 6;
					}
				}else{
					$erreur = 2;
				}
			}else{
				$erreur = 7;
			}
		}else{
			$erreur = 8;
		}
	}else{
		$erreur = 4;
	}
	echo affiche_erreur($erreur);
}
?>