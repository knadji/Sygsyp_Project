<?php 
    // Etablire la connexion avec la base de donnée
    require_once "ConnexionBdd.php";
        
    /**
     * Fonction qui permet de ce rediriger vers une autre page.
     * $lien : Le lien vers la page destinataire.
     * */
    function Redirection ($Lien)
       {
            header("Location: $Lien");
            exit;
       }
     /**
      * Fonction qui permet de sécuriser la base de donnée des injections sql.
      * $Valeur : la variable à sécuriser .
      * */
     function escape_errors ($Valeur){
        
        $magic_quotes = get_magic_quotes_gpc();// Retourne la configuration actuelle de la fonction "magic_quotes_gpc".
        $version_récente = function_exists("mysql_real_escape_string");// Tester si la function "mysql_real_escape_string" existe ou pas.
        if($version_récente){
            if($magic_quotes){
                $Valeur = stripcslashes($Valeur);// supprimé tous les antislashs.
                $Valeur = mysql_real_escape_string($Valeur);// enlever les caractéres spéciaux.
            }else {
                $Valeur = addslashes($Valeur);
            }
        }else{
            if(!$magic_quotes){
                $Valeur = addslashes($Valeur);
            }
        }
        $Valeur = trim($Valeur);
        return $Valeur;
     }  
     
     /**
      * Fonction qui code URL plus une compression. 
      * $lien : le lien à codée.
      * */
     function codage_url ($lien){
        //on compresse la chaîne obtenue avec gz
        $lien = gzcompress($lien, 9);
        //encode la chaîne au format URL
        $lien=urlencode($lien);
        return $lien;
     }
     
     /**
      * Fonction qui décode URL 
      * $lien : l'adresse qui a été utilisée pour appeler la page
      *        récupère avec la variable serveur "$_SERVER['REQUEST_URI']"
      * */
     function decodage_url($lien){
        //extrait le seul et unique paramètre (il se trouve après le ?)
        $lien=substr($lien, strpos($lien,'?')+1);
        //décode la chaîne
        $lien=urldecode($lien);
        //décompresse les données
        $lien = gzuncompress($lien);
        return $lien;
     }
     
     /**
      * Fonction qui permet d'affiche le message d'erreur associer au numéro d'erreur 
      * MOP : Mot de passe oublié.
      * $num_erreur : la variable à sécuriser .
      * */
     function affiche_erreur ($num_erreur){
        
        if(!empty($num_erreur)){
            if($num_erreur == 1){
                $num_erreur = "Vous n'êtes pas encore inscrit";
            }else if($num_erreur == 2){
                 $num_erreur = "Votre adresse mail n'est pas valide .";
            }else if($num_erreur == 3){
				$num_erreur = "Votre Mail est incorrecte !";
			}else if($num_erreur == 4){
				$num_erreur =  "Tous les champs doivent être complétés.";
			}else if($num_erreur == 5){
				$num_erreur = "Le compte à bien été Modifier.";
			}else if($num_erreur == 6){
				$num_erreur = "Adresse mail déja utilisée";
			}else if($num_erreur == 7){
				$num_erreur = "Votre Mail est incorrecte !";
			}else if($num_erreur == 8){
				$num_erreur ="Votre Pseudo est trop grand !";
			}else if($num_erreur == 9){
				$num_erreur = "Votre Nom est trop grand !";
			}else if($num_erreur == 10){
				$num_erreur = "Inscription terminée, consultez votre boîte email :)";
			}else if($num_erreur == 11){
				$num_erreur ="Votre Mot de passe est incorrecte !";
			}else if($num_erreur == 12){
				$num_erreur ="Votre mot de passe doivent contenir au minimum 6 caractéres.";
			}else if($num_erreur == 13){
				$num_erreur = "Il faut choisir un agent." ;
			}else if($num_erreur == 14){
				$num_erreur =  "Votre nom ou prénom n'est pas valide.";
			}else if($num_erreur == 15){
				$num_erreur = "Votre numéro de téléphone n'est pas valide.";
			}else if($num_erreur == 16){
				$num_erreur = "votre date de naissance n'est pas valide.";
			}else if($num_erreur == 17){
				$num_erreur = "votre code postal n'est pas valide.";
			}else if($num_erreur == 18){
				$num_erreur = "Le captcha est incorrecte !";
			}else if($num_erreur == 19){
				$num_erreur = "Vous devez choisir obligatoirement un service."; 
			}else if($num_erreur == 20){
				$num_erreur = "opération effectuée avec succés. Un email est envoyer à ton chef.";
			}else if($num_erreur == 21){
			     $num_erreur = "Merci de choisir des services different.";
			}else if ($num_erreur == 22){
			     $num_erreur = "Vous devez choisir les services par ordre merci.";
			}else if ($num_erreur == 23){
			     $num_erreur = "Information bien modifier.";
			}else if ($num_erreur == 24){
			     $num_erreur ="Voulez consultez votre boîte email :)";
			}else if($num_erreur == 100){
				$num_erreur = "Votre mot de passe à bien été Modifier.";
			}
        }
        return $num_erreur;
     }
     
     /**
     * Fonction qui permet de vérifier si l'object qu'on à upload est une image.
     * $avatar_tmp : L'objet qu'on veut upload.
     * $avatar : Le nom de l'image
     * $idUser : l'identifiant de l'utilisateur
     * $url : le chemin vers dossier image .
     * */
    function upload_avatar ($avatar_tmp, $avatar, $idUser, $url)
       {
            // initialisation de la variable d'erreur.
            $erreur = "";
            if(!empty($avatar_tmp)){
                // séparation du nom et l'extension de l'image.
                $image = explode('.',$avatar);
                // récupérer l'extension de l'image.
                $image_ext = end($image);
                // faire un test sur l'extension de l'image
                if(in_array(strtolower($image_ext),array('png','gif','jpeg','jpg')) == false){
                        $erreur = "Veuillez saisir une image"; 
                }
            }
            if(empty($erreur)){
                // test sur l'existence de l'image
                if(file_exists($avatar_tmp)){
                    $image_size = getimagesize($avatar_tmp);
                    // tester si l'objet est une image
                    if($image_size['mime'] == 'image/jpeg'){
                        $image_src = imagecreatefromjpeg($avatar_tmp);
                    }else if($image_size['mime'] == 'image/png'){
                        $image_src = imagecreatefrompng($avatar_tmp);
                    }else if($image_size['mime'] == 'image/gif'){
                        $image_src = imagecreatefromgif($avatar_tmp);
                    }else if($image_size['mime'] == 'image/jpg'){
                        $image_src = imagecreatefromjpg($avatar_tmp);
                    }else{
                        $erreur = "Votre image n'est pas valider !!";
                        $image_src= false;
                    }
                    if($image_src != false){
                        // définition de la taille obligatoire de l'image 
                        $width_image_std = 200;
                        if($image_size[0] <= $width_image_std){
                            $image_finale = $image_src;
                        }else {
                            // nouvelle largeur de l'image
                            $new_width[0] = $width_image_std;
                            // calculer de la nouvelle hauteur de l'image
                            $new_height[1] = 200;
                            // Crée une nouvelle image
                            $image_finale = imagecreatetruecolor($new_width[0], $new_height[1]);
                            // faire une copie de l'image source dans une nouvelle image avec des dimensions réduite
                            imagecopyresampled($image_finale, $image_src, 0,0,0,0,$new_width[0],$new_height[1],$image_size[0],$image_size[1]);
                        }
                        // sauvegarder l'image dans le dossier avatar_user avec comme nom "id_user.jpg"
                        imagejpeg($image_finale, $url.'/'.$idUser.'.jpg');
                    }
                }
            }
            echo $erreur;
       }
       
     /**
      * Fonction qui retourne le nom du service à partir d'un ensemble de valeurs.
      * $ensemble de valeur : Les valeurs correspondat à un service.
      * */
      function niveau_service ($ens_valeur)
        {
            if($ens_valeur[5]!=1){
                $niveau = 3;
            }else if($ens_valeur[4]!=1){
                $niveau = 2;
            }else if($ens_valeur[3]!=1){
                $niveau = 1;
            }else $niveau = 0;
            return $niveau;
        }
                
      function niveau_service_formulaire($ens_valeur){
        
            if($ens_valeur[4]!=1){
                $niveau = 4;
            }else if($ens_valeur[3]!=1){
                $niveau = 3;
            }else if($ens_valeur[2]!=1){
                $niveau = 2;
            }else $niveau = 1;
            return $niveau;
      }
                
      /**
      * Fonction qui retourne le nom du service à partir de sont niveau.
      * $niveau_service : une valeur qui correspondat au niveau de service (niveau 1 , sous_niveau 2, ...) .
      * */
      function Recup_nom_service ($niveau_service, $user_info_services, $BDD)
        {
            /* identifiant du service (+2 -> le décalage par rapport
               au deux 1er champ de la table crolles_agent_affectation_droit*/
            $id_service = $user_info_services[$niveau_service+2];
            // Reqûete pour récupérer le nom du service a partir des tables crolles_agent_sous_service(1 | 2 | 3). 
            $query_nom_service = "SELECT nom_service 
                                  FROM crolles_services
                                  WHERE id_service = ? ";
            $prep_query_mon_service = $BDD->prepare($query_nom_service);
            $prep_query_mon_service->bindValue(1, $id_service);
            $prep_query_mon_service->execute();
            $res = $prep_query_mon_service->fetch();
            $tout_service = $res['nom_service'];
            $prep_query_mon_service->closeCursor();
            return $tout_service;                  
       }
       /**
       * Fonction de conversion du binaire en hexadécimale.
       * $binsid : La chaine à convertire.
       * */
       
       // Returns the textual SID
        function bin_to_str_sid($binsid) 
        {
            $hex_sid = bin2hex($binsid);
            $rev = hexdec(substr($hex_sid, 0, 2));
            $subcount = hexdec(substr($hex_sid, 2, 2));
            $auth = hexdec(substr($hex_sid, 4, 12));
            $result    = "$rev-$auth";
        
            for ($x=0;$x < $subcount; $x++) {
                $subauth[$x] = hexdec(little_endian(substr($hex_sid, 16 + ($x * 8), 8)));
                $result .= "-" . $subauth[$x];
            }
        
            // Cheat by tacking on the S-
            return 'S-' . $result;
        }
        
        // Converts a little-endian hex-number to one, that 'hexdec' can convert
         function little_endian($hex) 
         {
            $result = "";
            for ($x = strlen($hex) - 2; $x >= 0; $x = $x - 2) 
            {
                $result .= substr($hex, $x, 2);
            }
            return $result;
         }

      /**
      * Fonction qui cherche l'ensembles des identifiant de mes chef de service.
      * $valeur_service : les valeurs des diffirents niveaux de chaque service (renvoie par la fonction Valeur_service).
      * $requete_prep : une requete préparer.
      * */
      function rechercher_mon_chef ($valeur_service, $requete_prep, $j)
        {
            $tab_res_agent_chef = array();
            if($valeur_service[3] == 1){
                if($valeur_service[2] == 1){
                    if($valeur_service[1] == 1){  
                        echo "Boucle N1";
                        while($data = $requete_prep->fetch()){
                            if($data['sous_service_n1']==1){
                                $tab_res_agent_chef[$j] = $data['agent_web_idAgent'];
                                echo "<br />".$tab_res_agent_chef[$j];
                                $j++;
                            }
                        }
                    }else{
                        while($data = $requete_prep->fetch()){
                            echo "Boucle N2";
                            if($data['sous_service_n1']==1 || ($data['sous_service_n1']== $valeur_service[1] && $data['sous_service_n2'] == 1)){
                                $tab_res_agent_chef[$j] = $data['agent_web_idAgent'];
                                echo "<br />".$tab_res_agent_chef[$j];
                                $j++;
                            }
                        }
                    }
                }else{
                    while($data = $requete_prep->fetch()){
                        echo "Boucle N3";
                        if($data['sous_service_n2'] == 1 || ($data['sous_service_n2']==$valeur_service[2] && $data['sous_service_n3'] == 1)){
                            $tab_res_agent_chef[$j] = $data['agent_web_idAgent'];
                            echo "<br />".$tab_res_agent_chef[$j];
                            $j++;
                        }
                    }
                }
            }else{
                while($data = $requete_prep->fetch()){
                    echo "Boucle N4";
                    if($data['sous_service_n1'] == $valeur_service[1]){
                        if($data['sous_service_n2'] == $valeur_service[2]){
                            if($data['sous_service_n3'] == $valeur_service[3] || $data['sous_service_n3'] == 1){
                                $tab_res_agent_chef[$j] = $data['agent_web_idAgent'];
                                $j++;
                            }
                        }else if($data['sous_service_n2'] == 1){
                            $tab_res_agent_chef[$j] = $data['agent_web_idAgent'];
                            $j++;
                        }
                    }else if($data['sous_service_n1'] == 1){ // 1 = NULL
                        $tab_res_agent_chef[$j] = $data['agent_web_idAgent'];
                        $j++;
                    }
                }
            }
         return $tab_res_agent_chef;
        }      
       
       /**
       * Envoi de email à tout mon hiérarchie.
       * $adresse_mail : adresses email du destinataire.
       * $nom_prenom_agent : Le nom et prénom de l'agent source du mail.
       * $sujet_mail :.
       * */
       function envoie_mail($adresse_mail, $sujet_mail, $message) 
        {
            // A qui on envoie le mail.
            $to = $adresse_mail;
            // le sujet du email
            $sujet = $sujet_mail;
            //en-tete de l'email
            $en_tete = "From:admin@Crolles.fr\r\n";
            // Headers.
            $headers = 'Mime-Version: 1.0'."\r\n";
            $headers .= 'Content-type: text/html; charset=iso-8859-1'."\r\n";
            $headers .= "\r\n";
            $body = $message;
            $body .="
                      <table>
                            <tr>
                                <td>
                                    <img src='http://www.ville-crolles.fr/images/home/logo-ville-de-crolles2.gif'/>
                                </td>
                                <td>
                                    <p>
                                        Administrateur<br />
                                        Ville de Crolles<br />
                                        Service Communication<br />
                                        BP 11 - 38921 Crolles Cedex<br />
                                        Tel.: 04 76 08 04 54 - fax:04 76 08 88 61<br />
                                        <a href='www.ville-crolles.fr'>www.ville-crolles.fr</a>
                                    </p>
                                </td>
                            </tr>
                        </table>
            ";
            mail($to, $sujet, $body, $headers);
       }
       
       
        // -------------------------------------------
        // crypte une chaine (via une clé de cryptage)
        // -------------------------------------------
        function crypter_val($maCleDeCryptage="", $maChaineACrypter){
            if($maCleDeCryptage==""){
                $maCleDeCryptage=$GLOBALS['PHPSESSID'];
            }
            $maCleDeCryptage = md5($maCleDeCryptage);
            $letter = -1;
            $newstr = '';
            $strlen = strlen($maChaineACrypter);
            for($i = 0; $i < $strlen; $i++ ){
                $letter++;
                if ( $letter > 31 ){
                    $letter = 0;
                }
                $neword = ord($maChaineACrypter{$i}) + ord($maCleDeCryptage{$letter});
                if ( $neword > 255 ){
                    $neword -= 256;
                }
                $newstr .= chr($neword);
            }
            return base64_encode($newstr);
        }
        
        // --------------------------------------------------
        // décrypte une chaine (avec la même clé de cryptage)
        // --------------------------------------------------
        function decrypter_val($maCleDeCryptage="", $maChaineCrypter){
            if($maCleDeCryptage==""){
                $maCleDeCryptage=$GLOBALS['PHPSESSID'];
            }
            $maCleDeCryptage = md5($maCleDeCryptage);
            $letter = -1;
            $newstr = '';
            $maChaineCrypter = base64_decode($maChaineCrypter);
            $strlen = strlen($maChaineCrypter);
            for ( $i = 0; $i < $strlen; $i++ ){
                $letter++;
                if ( $letter > 31 ){
                    $letter = 0;
                }
                $neword = ord($maChaineCrypter{$i}) - ord($maCleDeCryptage{$letter});
                if ( $neword < 1 ){
                    $neword += 256;
                }
                $newstr .= chr($neword);
            }
            return $newstr;
        }
      
      /**
      * Fonction controle de saisie clavier du NOM de l'utilisateur.
      * $nom : La chaine de caractère qu'on veut vérifier.
      * */
      function regex_nom_prenom ($nom)
        {
            return preg_match('#^[a-zA-ZÀÂÄÇÉÈÊËÎÏÔÖÙÛÜŸàâäçéèêëîïôöùûüÿÆŒæœ]{1,124}$#', $nom);
        }
        
      /**
      * Fonction controle de saisie clavier de la date.
      * $date : La date de naissance qu'on veut vérifier.
      * */
      function regex_date($date)
        {
            return preg_match('#^\d{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2]\d|3[0-1])$#', $date);
        }
        
      /**
      * Fonction controle de saisie clavier de l'adresse email de l'utilisateur.
      * $email : L'adresse email à vérifier.
      * */
      function regex_email($email)
        {
            //return preg_match('#^[^\W][a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\@[a-zA-Z0-9_]+(\.[a-zA-Z0-9_]+)*\.[a-zA-Z]{2,4}$#' , $email);
            return preg_match('#^[a-zA-Z0-9._-]+@[a-zA-Z0-9._-]{2,}\.[a-zA-Z]{2,4}$#',$email);
        }
        
      /**
      * Fonction contrôle de saisie clavier de l'adresse postal de l'utilisateur.
      * $code_postal : L'adresse postal à vérifier.
      * */
      function regex_code_postal($code_postal)
        {
            return preg_match('#^\d{5,5}$#', $code_postal);
        }
        
      /**
      * Fonction contrôle de saisie clavier du numéro de téléphone de l'utilisateur.
      * $num_tel : Le numéro de téléphone à vérifier.
      * */
      function regex_num_tel($num_tel)
        {
            return preg_match('#^(\d\d){5}$#', $num_tel);
        }
        
?>