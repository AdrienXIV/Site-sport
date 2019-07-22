<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=sport; charset=utf8', 'root', '');

if (isset($_SESSION['id'])) { // si la personne est connectée ou non

    $requser = $db->prepare("SELECT * FROM identifiants WHERE id = ?");
    $requser->execute(array($_SESSION['id']));
    $user = $requser->fetch();

    if (isset($_SESSION['id'])) {

        if (isset($_POST['button'])) {

            $nom = $user['nom'];
            $prenom = $user['prenom'];

            if (!empty($_POST['date']) and !empty($_POST['rythmecardiaque']) and !empty($_POST['rythmecardiaquemax']) and !empty($_POST['calorie']) and !empty($_POST['temps'])) {

                $date = htmlspecialchars($_POST['date']);
                $calorie = htmlspecialchars($_POST['calorie']);
                $rythmecardiaque = htmlspecialchars($_POST['rythmecardiaque']);
                $rythmecardiaquemax = htmlspecialchars($_POST['rythmecardiaquemax']);
                $temps = htmlspecialchars($_POST['temps']);

                $reqdonnee = $db->prepare("INSERT INTO donnee_sport (nom, prenom, `date`, rythmecardiaque, rythmecardiaquemax, calorie, temps) VALUES (?, ?, ?, ?, ?, ?, ?);");
                $reqdonnee->execute(array($nom, $prenom, $date, $rythmecardiaque, $rythmecardiaquemax, $calorie, $temps));
                $donneesport = $reqdonnee->fetch();

                $_SESSION['message'] = "Insertion réussi !";
                header('Location: profil.php?id=' . $_SESSION['id']);
            }
        }
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
                        <p>Edition du profil de <?php echo $_SESSION['prenom']; ?></p>
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

                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="editionprofil.php"><?php
                                                                        if (isset($_SESSION['id']) and $user['id'] == $_SESSION['id']) {
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

        <div class="center-div-edition edition-profil">
            <h2>Edition de mon profil</h2>
            <form action="" method="post">
                <table>
                    <tr>
                        <td><label for="date">Date</label>
                            <input required id="date" type="date" name="date"></td>
                        <td>
                            <label for="calorie">Calories dépensées</label>
                            <input required id="calorie" type="number" min="1" max="10000" name="calorie" placeholder="100">
                        </td>

                    </tr>
                    <tr>
                        <td><label for="rythmecardiaque">Rythme cardiaque moyen</label>
                            <input required id="rythmecardiaque" type="number" min="1" max="250" name="rythmecardiaque" placeholder="60"></td>
                        <td>
                            <label for="rythmecardiaquemax">Rythme cardiaque max</label>
                            <input required id="rythmecardiaquemax" type="number" min="1" max="250" name="rythmecardiaquemax" placeholder="60">
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <label for="temps">Durée</label>
                            <input required id="temps" type="time" name="temps" value="01:00:00" step="1">
                        </td>
                        <td>
                            <input type="submit" value="Valider" name="button" class="btn btn-outline-success btn-size margin-top">
                        </td>
                    </tr>
                </table>
            </form>
        </div>
        <script>
            var date = new Date();

            var day = date.getDate();
            var month = date.getMonth() + 1;
            var year = date.getFullYear();

            if (month < 10) month = "0" + month;
            if (day < 10) day = "0" + day;

            var today = year + "-" + month + "-" + day;
            document.getElementById("date").value = today;
        </script>
    </body>

    </html>
<?php
} else {
    header('Location:connexion.php');
}
?>