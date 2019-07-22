<?php
session_start();
try {
    $db = new PDO('mysql:host=localhost;dbname=sport; charset=utf8', 'root', '');

    if (isset($_POST['button'])) {
        if (!empty($_POST['nom']) and !empty($_POST['prenom']) and !empty($_POST['email']) and !empty($_POST['password'])) {

            $nom = htmlspecialchars($_POST['nom']);
            $prenom = htmlspecialchars($_POST['prenom']);
            $email = htmlspecialchars($_POST['email']);
            $password = sha1($_POST['password']); // encodage

            $nomlenght = strlen($nom);
            $prenomlenght = strlen($prenom);

            if ($nomlenght <= 255) {
                if ($prenomlenght <= 255) {

                    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                        $reqmail = $db->prepare('SELECT * FROM identifiants WHERE email=?');
                        $reqmail->execute(array($email));
                        $mail_existe = $reqmail->rowCount();

                        if ($mail_existe == 0) {
                            $query = $db->prepare("INSERT INTO identifiants (nom, prenom, email, `password`) VALUES (?,?,?,?)");
                            $query->execute(array($nom, $prenom, $email, $password));
                            header("Location: connexion.php");
                        } else {
                            $erreur = "Adresse électronique déjà utilisée !";
                        }
                    } else {
                        $erreur = "Email invalide !";
                    }
                } else {
                    $erreur = "Votre prénom ne doit pas dépasser 255 caractères !";
                }
            } else {
                $erreur = "Votre nom ne doit pas dépasser 255 caractères !";
            }

        } else {
            $erreur = "Tous les champs doivent être complétés !";
        }
    }
} catch (PDOException $e) {
    print "Erreur !: " . $e->getMessage() . "<br/>";
    die();
}
?>


<html>

<head>
    <title>Sport</title>
    <link rel="icon" href="public/images/sport.png" />
    <link rel="stylesheet" href="node_modules/bootstrap/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="public/style.css">
    <meta name="viewport" content="width=device-width, initial-scale=0.66" />
    <meta charset="UTF-8">
</head>

<body>

    <!--   EN TETE   -->
    <div class="container-fluid titre-color">
        <div class="container">
            <div class="row">
                <article class="col-sm-12 col-md col-xl col-lg text-center">
                    <h1>Performances sportives</h1>
                    <p>Inscription</p>
                </article>
            </div>
        </div>
    </div>

    <nav class="navbar navbar-expand-lg navbar-light bg-light">
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav navbar-profil">
                    <li class="nav-item">
                        <a class="nav-link" href="index.php">Accueil</a>
                    </li>
                    <li class="nav-item">
                        <?php 
                        if(isset($_SESSION['id']) and !empty($_SESSION['id'])){
                            $link_address = "profil.php?id=".$_SESSION['id'];

                            echo "<a class='nav-link' href='".$link_address."'>Profil</a>";
                        }
                        else echo "<a class='nav-link' href="."profil.php".">Profil</a>"; ?>
                        
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="editionprofil.php"><?php
                                                                        if (isset($_SESSION['id']) and isset($user['id']) and $user['id'] == $_SESSION['id']) {
                                                                            ?>
                                Editer profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="deconnexion.php"> <?php
                                                                    }
                                                                    ?>
                            Se déconnecter</a>
                    </li>
                </ul>
            </div>
        </nav>

    <!--   FORMULAIRE DE CHOIX   -->
    <div class="center-div-inscription" id="formulaire-inscription">
        <form action="" method="POST">
            <table>
                <td>
                    <label for="nom">Nom</label>
                    <input required type="text" name="nom" id="nom" class="input-inscription" placeholder="Nom" value="<?php if (isset($nom)) {
                                                                                                                            echo $nom;
                                                                                                                        } ?>">
                </td>
                <td>
                    <label for="prenom">Prénom</label>
                    <input required type="text" name="prenom" id="prenom" class="input-inscription" placeholder="Prénom" value="<?php if (isset($prenom)) {
                                                                                                                                    echo $prenom;
                                                                                                                                } ?>">
                </td>
            </table>

            <label for="email">Courriel</label>
            <input required type="email" name="email" id="email" class="input-inscription" placeholder="azerty@gmail.com" value="<?php if (isset($email)) {
                                                                                                                                        echo $email;
                                                                                                                                    } ?>">
            <label for="password">Mot de passe</label>
            <input required type="password" name="password" id="password" class="input-inscription">

            <?php if (isset($_POST['button'])) {
                echo "<div class='div-erreur'>$erreur</div>";
            }
            ?>

            <div class="margin-top"></div>
            <input type="submit" value="Valider" name="button" class="btn btn-outline-success btn-size">
        </form>
    </div>
</body>

</html>