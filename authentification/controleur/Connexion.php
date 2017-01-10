<?php
session_start();
include_once "config.php";
require_once Path_index."includes/connexion_BDD.php";
include_once Path_index."includes/fonction_util.php";
if(isset($_POST['formConnexion'])){
	$erreur = "";
	$mailConnect = escape_errors($_POST['Mail']);
	$motDePasse = hash("sha256",$_POST['MotDePasse']); //'{SHA}' . base64_encode(sha1($_POST['MotDePasse'], TRUE));

	if(!empty($mailConnect) AND !empty($motDePasse)){
		//-----------------------------------------Requête pour tester si l'utilisateur est un Habitant-------------------------------------------------------
		$requeteAuthentification_Habitant = $BDD->prepare("SELECT * FROM crolles_user_connect_std
                                                                   WHERE email_user=?
                                                                   AND password_user =?");
		// Requête pour récuperer les information EIDAS
		$requeteAuthentification_Habitant->execute(array($mailConnect, $motDePasse));
		$Habitant_Exist = $requeteAuthentification_Habitant->rowCount();
		$utilisateurInfo = $requeteAuthentification_Habitant->fetch();
		// Récuperer le statu de l'activation de ce compte (0 OU 1).
		$active = $utilisateurInfo['compte_active'];

		if($Habitant_Exist == 1 AND $active == 1){
			// C'est un utilisateur Habitant.
			$erreur = "";
			$id_rand_apache_log = $utilisateurInfo['id_rand'];
			$_SESSION['idUser_crolles'] = $utilisateurInfo['idUser_crolles'];
			$_SESSION['username'] = htmlspecialchars(stripcslashes($utilisateurInfo['pseudo_user']));
			$_SESSION['email_user'] = $utilisateurInfo['email_user'];
			$_SESSION['compte_active'] = $utilisateurInfo['compte_active'];
			// récuperer la date du systéme.
			date_default_timezone_set("Europe/Paris");
			$date = date('y-m-d H:i:s');
			// mettre ajour l'heure de connexion de l'utilisateur.
			$query_updat = "UPDATE crolles_user_connect_std
                                    SET last_connect_user =?
                                    WHERE idUser_crolles =?";
			$prep = $BDD->prepare($query_updat);
			$prep->bindValue(1, $date);
			$prep->bindValue(2, $_SESSION['idUser_crolles']);
			$prep->execute();
			$prep->closeCursor();
			//---------------------Fin MAJ------------------------
			Redirection(Path_index."index.php"."?".$id_rand_apache_log.$_SESSION['idUser_crolles']);

		}else if($Habitant_Exist != 1){
			$erreur = "Mauvais mail ou mot de passe";
		}else{
			$erreur = "Votre compte n'est pas actif, consultez votre email.";
		}
		//}
	}else{
		$erreur = "Tous les champs doivent être complétés";
	}
	echo $erreur;
	?>
        &nbsp;<a href="<?php echo Path_index;?>index.php">Se reconnecter</a>
<?php         
    }
?>
