<?php
session_start();

try {

    $db = new PDO('mysql:host=localhost;dbname=sport; charset=utf8', 'root', '');

    if (isset($_POST['button'])) {
        if (!empty($_POST['email']) and !empty($_POST['password'])) {

            $email = htmlspecialchars($_POST['email']); // htmlspecialchars = evite l'injection d'html dans la variable
            $password = sha1($_POST['password']); // encode le mdp

            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {

                $requser = $db->prepare("SELECT * FROM identifiants WHERE email = ? AND `password` = ? ");
                $requser->execute(array($email, $password));

                $user_existe = $requser->rowCount();

                //vérification si l'utilisateur existe dans la bdd
                if ($user_existe == 1) {

                    $user_info = $requser->fetch();
                    $_SESSION['id'] = $user_info['id']; // stocke l'id de la bdd correspondant à l'utilisateur dans une variable session
                    $_SESSION['prenom'] = $user_info['prenom']; // stocke le prenom
                    $_SESSION['email'] = $user_info['email']; // stocke le mail

                    header("Location: profil.php?id=" . $_SESSION['id']); // ajoute l'id dans l'url comme une requete get et redirige vers cette page

                } else {
                    $erreur = "Courriel ou mot de passe invalides !";
                }
            } else {
                $erreur = "Email invalide !";
            }
        }
    } else {
        $erreur = "Tous les champs doivent être complétés !";
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
                    <p>Connexion</p>
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
                        if(isset($user['id'])){
                                                                        if (isset($_SESSION['id']) and $user['id'] == $_SESSION['id']) {
                                                                            ?>
                                Editer profil</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="deconnexion.php"> <?php
                                                                    }}
                                                                    ?>
                            Se déconnecter</a>
                    </li>
                </ul>
            </div>
        </nav>

    <!--   FORMULAIRE DE CHOIX   -->
    <div class="center-div-connexion" id="formulaire-connexion">
        <form action="" method="POST">

            <label for="email">Courriel</label>
            <input required type="text" name="email" id="email" class="input-connexion" placeholder="azerty@gmail.com" value="<?php if (isset($_SESSION['email'])) {
                                                                                                                                    echo $_SESSION['email'];
                                                                                                                                } ?>">
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" class="input-connexion">
            
            <?php if (isset($_POST['button'])) {
                echo "<div class='div-erreur'>$erreur</div>";
            }
            ?>
            
            <div class="margin-top"></div>
            <input required type="submit" value="Valider" name="button" class="btn btn-outline-success btn-size">
        </form>
    </div>
</body>

</html>