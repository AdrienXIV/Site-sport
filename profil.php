<?php
session_start();
$db = new PDO('mysql:host=localhost;dbname=sport; charset=utf8', 'root', '');

if (isset($_GET['id']) and $_GET['id'] > 0) {
    $getid = intval($_GET['id'] > 0);
    $requser = $db->prepare('SELECT * FROM identifiants WHERE id=?');
    $requser->execute(array($_GET['id']));
    $user_info = $requser->fetch();
    $donnee_sport = $db->prepare('SELECT * FROM donnee_sport WHERE nom=?');
    $donnee_sport->execute(array($user_info['nom']));
    $donnee = $donnee_sport->fetchAll();
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
                        <p>Profil de <?php echo $user_info['prenom']; ?></p>
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
                                                                        if (isset($_SESSION['id']) and $user_info['id'] == $_SESSION['id']) {
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

        <?php
        if (!empty($_SESSION['message'])) {
            echo "<p style='padding:10px;'>" . $_SESSION['message'] . "</p>";
            $_SESSION['message'] = ""; // remise à 0
        }
        ?>

        <script src="https://canvasjs.com/assets/script/canvasjs.min.js"></script>

        <div class="graph">
            <div id="chartContainerCardiaque"></div>
            <table class="rejouer">
                <td>
                    <input class="btn btn-outline-dark btn-size" type="button" value="Afficher le graphique en entier" id="btn_afficher_cardiaque">
                </td>
                <td>
                    <input class="btn btn-outline-dark btn-size" type="button" value="Rejouer l'annimation" id="btn_rejouer_cardiaque">
                </td>
            </table>
        </div>

        <div class="separation"></div>

        <div class="graph">
            <div id="chartContainerCalorie"></div>
            <table class="rejouer">
                <td>
                    <input class="btn btn-outline-dark btn-size" type="button" value="Afficher le graphique en entier" id="btn_afficher_calorie">
                </td>
                <td>
                    <input class="btn btn-outline-dark btn-size" type="button" value="Rejouer l'annimation" id="btn_rejouer_calorie">
                </td>
            </table>
        </div>

        <script type="text/javascript">
        window.onload = function() {
    CanvasJS.addCultureInfo("fr", {
        decimalSeparator: ",", // Observe ToolTip Number Format
        digitGroupSeparator: ".", // Observe axisY labels                   
        days: ["Dimanche", "Lundi", "Mardi", "Mercredi", "Jeudi", "Vendredi", "Samedi"],
        months: ["Janvier", "Février", "Mars", "Avril", "Mai", "Juin", "Juillet", "Août", "Septembre", "Octobre", "Novembre", "Décembre"]

    });
    var btn_rejouer_cardiaque = document.getElementById('btn_rejouer_cardiaque');
    var btn_affiche_cardiaque = document.getElementById('btn_afficher_cardiaque')
    var donnee_recu = <?php echo json_encode($donnee); ?>;

    var interval = setInterval(function() {
        donnee_recu = <?php echo json_encode($donnee); ?>;
    }, 500);

    var dataCardiaque = [],
        dataCalorie = [];
    var dataPoints = [];
    var optionsCardiaque = {},
        optionsCalorie = {};

    var updateInterval = 2500;
    var compteurCalorie = 0,
        compteurCardiaque = 0;
    var intervalCardiaque = setInterval(function() {}, updateInterval),
        intervalCalorie = setInterval(function() {}, updateInterval);

    var chartCardiaque = new CanvasJS.Chart("chartContainerCardiaque", {});
    var chartCalorie = new CanvasJS.Chart("chartContainerCalorie", {});

        cardiaque();
    updateChartCardiaque();
    calorie();
    updateChartCalorie();

    btn_afficher_cardiaque.onclick = function() {
        chartCardiaque.destroy();
        clearInterval(intervalCardiaque);
        dataCardiaque.splice(0, dataCardiaque.length);
        chartCardiaque = new CanvasJS.Chart("chartContainerCardiaque", optionsCardiaque);
        donnee_recu.forEach(function(element) {
            dataCardiaque.push({
                x: new Date(element.date),
                y: [parseInt(element.rythmecardiaque), parseInt(element.rythmecardiaquemax)],
                markerBorderColor: "blue",
                markerColor: "white"
            });
        });
        chartCardiaque.render();
    };

    btn_rejouer_cardiaque.onclick = function() {
        clearInterval(intervalCardiaque);
        compteurCardiaque = 0;
        dataCardiaque.splice(0, dataCardiaque.length);

        chartCardiaque = new CanvasJS.Chart("chartContainerCardiaque", optionsCardiaque);

        updateChartCardiaque();
        intervalCardiaque = setInterval(function() {
            updateChartCardiaque()
        }, updateInterval);

    };

    btn_afficher_calorie.onclick = function() {
        clearInterval(intervalCalorie);
        dataCalorie.splice(0, dataCalorie.length);
        chartCalorie = new CanvasJS.Chart("chartContainerCalorie", optionsCalorie);
        donnee_recu.forEach(function(element) {
            dataCalorie.push({
                x: new Date(element.date),
                y: parseInt(element.calorie),
                markerBorderColor: "blue",
                markerColor: "white"
            });
        });
        chartCalorie.render();
    };

    btn_rejouer_calorie.onclick = function() {
        clearInterval(intervalCalorie);
        compteurCalorie = 0;
        dataCalorie.splice(0, dataCalorie.length);

        chartCalorie = new CanvasJS.Chart("chartContainerCalorie", optionsCalorie);

        updateChartCalorie();
        intervalCalorie = setInterval(function() {
            updateChartCalorie()
        }, updateInterval);

    };

    function cardiaque() {
        optionsCardiaque = {
            zoomEnabled: true,
            animationEnabled: true,
            animationDuration: 2000,
            culture: "fr",

            legend: {
                fontFamily: "tahoma",
                fontSize: 15,
                markerMargin: 10
            },
            title: {
                text: "Rythme cardiaque en fonction des jours de la semaine",
                fontSize: 30
            },
            axisX: {
                valueFormatString: "DDDD DD MMMM YYYY",
                interval: 1,
                intervalType: "day",
                labelAngle: -60
            },
            axisY: {
                includeZero: true,
                maximum: 250,
                title: "Battements par minute",
                titleFontSize: 15
            },

            data: [{
                type: "rangeSplineArea",
                indexLabel: "{y[#index]}",
                xValueFormatString: "DDDD DD MMMM YYYY",
                markerBorderThickness: 1,
                toolTipContent: "<strong style='color:navy'>{x}</strong> <br><br> Rythme cardiaque moyen : <strong style='color:red'>{y[0]}</strong> <br> Rythme cardiaque max : <strong style='color:red'>{y[1]}</strong>",
                dataPoints: dataCardiaque
            }]
        };
        chartCardiaque = new CanvasJS.Chart("chartContainerCardiaque", optionsCardiaque);
    }

    intervalCardiaque = setInterval(function() {
        updateChartCardiaque()
    }, updateInterval);

    function updateChartCardiaque() {

        if (compteurCardiaque != donnee_recu.length) {
            if (compteurCardiaque < 5) {
                for (let ii = 0; ii < 5; ii++) {
                    dataCardiaque.push({
                        x: new Date(donnee_recu[ii].date),
                        y: [parseInt(donnee_recu[ii].rythmecardiaque), parseInt(donnee_recu[ii].rythmecardiaquemax)],
                        markerColor: "white",
                        markerBorderColor: "blue"
                    });
                    compteurCardiaque++;

                }
            } else {
                //_data.splice(0, 1);
                dataCardiaque.push({
                    x: new Date(donnee_recu[compteurCardiaque].date),
                    y: [parseInt(donnee_recu[compteurCardiaque].rythmecardiaque), parseInt(donnee_recu[compteurCardiaque].rythmecardiaquemax)],
                    markerColor: "white",
                    markerBorderColor: "blue"
                });
                compteurCardiaque++;
            }

        } else {
            compteurCardiaque = 0;
            clearInterval(intervalCardiaque)
        }
        chartCardiaque.render();
    }

    //////////////////////////////////////////////////////////////////////////////////////

    function calorie() {
        optionsCalorie = {
            zoomEnabled: true,
            animationEnabled: true,
            animationDuration: 2000,
            culture: "fr",

            legend: {
                fontFamily: "tahoma",
                fontSize: 15,
                markerMargin: 10
            },
            title: {
                text: "Calories dépensées en fonction des jours de la semaine",
                fontSize: 30
            },
            axisX: {
                valueFormatString: "DDDD DD MMMM YYYY",
                interval: 1,
                intervalType: "day",
                labelAngle: -60
            },
            axisY: {
                includeZero: true,
                maximum: 800,
                title: "Calories",
                titleFontSize: 15
            },

            data: [{
                type: "line",
                indexLabel: "{y}",
                xValueFormatString: "DDDD DD MMMM YYYY",
                markerBorderThickness: 1,
                lineThickness: 5,
                markerSize: 10,
                toolTipContent: "<strong style='color:navy'>{x}</strong> <br><br> Calories dépensées : <strong style='color:red'>{y}</strong>",
                dataPoints: dataCalorie
            }]
        };
        chartCalorie = new CanvasJS.Chart("chartContainerCalorie", optionsCalorie);

    }

    intervalCalorie = setInterval(function() {
        updateChartCalorie()
    }, updateInterval);

    function updateChartCalorie() {
        if (compteurCalorie != donnee_recu.length) {
            if (compteurCalorie < 5) {
                for (let ii = 0; ii < 5; ii++) {
                    dataCalorie.push({
                        x: new Date(donnee_recu[ii].date),
                        y: parseInt(donnee_recu[ii].calorie),
                        markerColor: "white",
                        markerBorderColor: "blue"
                    });
                    compteurCalorie++;
                }
            } else {
                //_data.splice(0, 1);
                dataCalorie.push({
                    x: new Date(donnee_recu[compteurCalorie].date),
                    y: parseInt(donnee_recu[compteurCalorie].calorie),
                    markerColor: "white",
                    markerBorderColor: "blue"
                });
                compteurCalorie++;
            }

        } else {
            compteurCalorie = 0;
            clearInterval(intervalCalorie)
        }
        chartCalorie.render();
    }
}
        
        </script>

    </body>

    </html>

<?php
} else  header('Location:connexion.php');
?>


<!-- Cookie : setcookie('nom', 'valeur', time() + temps en secondes, null, null, false, true); -->