<?php
    session_start();
    include_once "config.php";
    
    //echo "username = ".$_SESSION['username'];
    if(empty($_SESSION['username'])){
?>
    <div align ="right">
        <big>Connexion :</big><br /><br />
        <a href="auth_identification/auth_sspi/login.php">Agent</a> | <a href="auth_identification/auth_bd/vue/form_connexion_user_db.php">Habitant</a>
    </div>
<?php 
    }
    //session_destroy();    
    
    if(isset($_SESSION['idAgent']) || !empty($_SESSION['idAgent'])){ // Agent 
?>
    <div align = "right">
        <h3>Bonjour Agent <?php echo @$_SESSION['cn'];?></h3>
        <a href="index.php">Accueil</a>
<?php
       if($_SESSION['deconnection']){
 ?>
        <a href="auth_identification/deconnection.php" >| Se déconnecter</a>
       
<?php 
        }
?>
    </div><br />
    <h2>Mes Droit : </h2>
<?php
    include_once Path."profil_agent_affectation/form_Droit_Agent.php";
    
    }else if(isset($_SESSION['idUser_crolles']) || !empty($_SESSION['idUser_crolles'])){// Habitant
?>
    <div align = "right">
        <h3>Bonjour Habitant <?php echo $_SESSION['username'] ?> ...</h3>
         <a href="index.php">Accueil</a>&nbsp| <a href="auth_identification/deconnection.php">Se déconnecter</a>
    </div>
<?php 
    include_once Path."auth_identification/auth_bd/vue/form_Droit_Habitant.php";        
    }else if (isset($_SESSION['idAdmin']) || !empty($_SESSION['idAdmin'])){// Administrateur
?>
    <div align = "right">
        <h3>Bonjour Administrateur <?php echo $_SESSION['pseudo_admin'] ?> ...</h3>
        <a href="auth_identification/deconnection.php">Se déconnecter</a>
    </div>
    <table width="80%" height="100%" >
        <tr>
            <td width="15%" bgcolor="silver">
                <b><h3>Utilisateur : </h3></b>
                <a href="auth_identification/auth_bd/inscription/maj_Utilisateur.php?cat_utilisateur=<?php echo $x="admin" ?>">Administrateur</a><br /><br />
                <a href="auth_identification/auth_bd/inscription/maj_Utilisateur.php?cat_utilisateur=<?php echo $x="agent" ?>">Agent</a><br /><br />
                <a href="auth_identification/auth_bd/inscription/maj_Utilisateur.php?cat_utilisateur=<?php echo $x="habitant" ?>">Habitant</a><br /><br />
            </td>
            <td></td>
        </tr>
    </table>
<?php
    }
    include_once("includes/footer.php");
 ?>
