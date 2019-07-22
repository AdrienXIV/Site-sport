<?php
session_start();
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
                    <p>Menu</p>
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
                    if (isset($_SESSION['id']) and !empty($_SESSION['id'])) {
                        $link_address = "profil.php?id=" . $_SESSION['id'];

                        echo "<a class='nav-link' href='" . $link_address . "'>Profil</a>";
                    } else echo "<a class='nav-link' href=" . "profil.php" . ">Profil</a>"; ?>

                </li><?php
                        $db = new PDO('mysql:host=localhost;dbname=sport; charset=utf8', 'root', '');

                        if (isset($_SESSION['id'])) { // si la personne est connectée ou non

                            $requser = $db->prepare("SELECT * FROM identifiants WHERE id = ?");
                            $requser->execute(array($_SESSION['id']));
                            $user = $requser->fetch();

                            if (isset($user['id']) and $user['id'] == $_SESSION['id']) {
                                ?>
                        <li class="nav-item">
                            <?php $link_address = "profil.php?id=" . $_SESSION['id'];
                            echo "<a class='nav-link' href='" . $link_address . "'> Editer profil</a>"; ?>

                        </li><?php
                            }
                        }
                        ?>

                <li class="nav-item">
                    <a class="nav-link" href="deconnexion.php">
                        Se déconnecter</a>
                </li>
            </ul>
        </div>
    </nav>

    <!--   FORMULAIRE DE CHOIX   -->
    <div class="center-div-choix" id="formulaire-choix">
        <form action="inscription.php" method="POST">
            <input type="submit" value="S'inscrire" class="btn btn-outline-dark btn-size" id="btn-inscription">
        </form>
        <form action="connexion.php" method="POST">
            <input type="submit" value="Se connecter" class="btn btn-outline-secondary btn-size margin-top" id="btn-connexion">
        </form>
    </div>





</body>

</html>