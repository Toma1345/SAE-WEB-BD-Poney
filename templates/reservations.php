<?php
if (!isset($_SESSION)) {
    session_start();
}

if (!isset($_SESSION['course1_people'])) {
    $_SESSION['course1_people'] = 1;
}
if (!isset($_SESSION['course2_people'])) {
    $_SESSION['course2_people'] = 0;
}
if (!isset($_SESSION['course3_people'])) {
    $_SESSION['course3_people'] = 0;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['course1_increase']) && $_SESSION['course1_people'] < 10) {
        $_SESSION['course1_people']++;
    }
    if (isset($_POST['course1_decrease']) && $_SESSION['course1_people'] > 0) {
        $_SESSION['course1_people']--;
    }

    if (isset($_POST['course2_increase']) && $_SESSION['course2_people'] < 10) {
        $_SESSION['course2_people']++;
    }
    if (isset($_POST['course2_decrease']) && $_SESSION['course2_people'] > 0) {
        $_SESSION['course2_people']--;
    }

    if (isset($_POST['course3_increase']) && $_SESSION['course3_people'] < 10) {
        $_SESSION['course3_people']++;
    }
    if (isset($_POST['course3_decrease']) && $_SESSION['course3_people'] > 0) {
        $_SESSION['course3_people']--;
    }
}

$course1_price = 75;
$course2_price = 150;
$course3_price = 170;
$taxe = 0.50;
$total = ($_SESSION['course1_people'] * $course1_price) + ($_SESSION['course2_people'] * $course2_price) + ($_SESSION['course3_people'] * $course3_price) + $taxe;
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation de cours</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
        }

        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            background-color: #ffffff;
            border-bottom: 1px solid #ddd;
        }

        header h1 a {
            text-decoration:none;
            font-size: 1.5rem;
            color: #2d572c;
        }

        header nav a {
            margin-right: 1rem;
            text-decoration: none;
            color: #000;
        }

        header nav a:last-child {
            color: #ffffff;
            background-color: #2d572c;
            padding: 0.5rem 1rem;
            border-radius: 5px;
        }

        .btn {
            background-color: #4CAF50;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            text-decoration: none;
            font-size: 16px;
        }

        .btn:hover {
            background-color: #45a049;
        }

        .container {
            padding: 20px;
        }

        h2 {
            font-size: 28px;
            margin-bottom: 10px;
        }

        .subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 20px;
        }

        .courses {
            display: flex;
            gap: 20px;
        }

        .course {
            background-color: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            overflow: hidden;
            text-align: left;
            width: 820px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: row;
            margin: 2%;
        }

        .course img {
            width: 160px;
            height: 160px;
            object-fit: cover;
        }

        .course-details {
            padding: 15px;
            flex-grow: 1;
        }

        .course-details h3 {
            margin: 0 0 10px;
            font-size: 18px;
            color: #333;
        }

        .course-details p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .price {
            font-size: 20px;
            color: #4CAF50;
            margin-top: 10px;
        }

        .reserve-controls {
            display: flex;
            align-items: center;
            margin-top: 10px;
        }

        .reserve-controls span {
            margin: 0 10px;
        }

        .reserve-controls button {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 5px 10px;
            border-radius: 5px;
            font-size: 14px;
            cursor: pointer;
        }

        .reserve-controls button:hover {
            background-color: #45a049;
        }

        .sidebar {
            background-color: #ffffff;
            border: 1px solid #e5e5e5;
            border-radius: 10px;
            padding: 15px;
            max-width: 300px;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.1);
        }

        .sidebar h3 {
            margin-top: 0;
            font-size: 20px;
            color: #333;
        }

        .sidebar p {
            margin: 5px 0;
            font-size: 14px;
            color: #666;
        }

        .sidebar .total {
            font-size: 18px;
            color: #333;
            margin: 15px 0;
        }

        .sidebar .btn {
            width: 100%;
            text-align: center;
        }

        .layout {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }

        .flex-item {
            flex: 1;
            min-width: 300px;
        }

        footer {
            margin-top: auto;
            text-align: center;
            padding: 10px 0;
            background-color: #f0f0f0;
            color: #333;
            font-size: 14px;
            border-top: 1px solid #ccc;
        }
    </style>
</head>
<body>
<header>
        <h1><a href="home.php">Grand Galop</a></h1>
        <nav>
            <a href="planning.php">Réserver un cours</a>
            <a href="tarifs.php">Consulter les tarifs</a>
            <a href="planningcours.php">Mes cours</a>
            <a href="profil.php" class="btn">Mon profil</a>
        </nav>
    </header>

    <div class="container">
        <h2>Réservation de cours</h2>
        <?php

            $date = isset($_GET['date']) ? $_GET['date'] : '';
            if (isset($_GET['hour'])) {
                $hour_raw = $_GET['hour']; // Exemple : "9h"
                // Retirer le "h" et ajouter les minutes
                $hour = str_replace('h', '', $hour_raw); 
                $hour = str_pad($hour, 2, '0', STR_PAD_LEFT) . ':00'; // Transforme "9" en "09:00"
            } else {
                $hour = ''; // Valeur par défaut
            }
            

        ?>

        <input type="date" name="choixdate" id="date_reserv" value="<?php echo htmlspecialchars($date); ?>">
        <input type="time" name="choixhour" id="hour_reserv" value="<?php echo htmlspecialchars($hour); ?>">


        <p class="subtitle">3 possibilités</p>

        <div class="layout">
            <div class="flex-item">
                <form method="POST">
                    <div class="courses">
                        <div class="course">
                            <img src="images/bubble.png" alt="Découvrir l'équitation">
                            <div class="course-details">
                                <h3>Découvrir l'équitation (1h)</h3>
                                <p>Reste 3 places</p>
                                <p class="price">75 €</p>
                                <div class="reserve-controls">
                                    <button type="submit" name="course1_decrease">-</button>
                                    <span><?php echo $_SESSION['course1_people']; ?> personnes</span>
                                    <button type="submit" name="course1_increase">+</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="courses">
                        <div class="course">
                            <img src="images/balade_foret.png" alt="Balade en forêt">
                            <div class="course-details">
                                <h3>Balade en forêt (1h)</h3>
                                <p>Reste 10 places</p>
                                <p class="price">150 €</p>
                                <div class="reserve-controls">
                                    <button type="submit" name="course2_decrease">-</button>
                                    <span><?php echo $_SESSION['course2_people']; ?> personnes</span>
                                    <button type="submit" name="course2_increase">+</button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="courses">
                        <div class="course">
                            <img src="images/grande_balade.png" alt="Grande balade en forêt">
                            <div class="course-details">
                                <h3>Grande balade en forêt (2h)</h3>
                                <p>Reste 2 places</p>
                                <p class="price">170 €</p>
                                <div class="reserve-controls">
                                    <button type="submit" name="course3_decrease">-</button>
                                    <span><?php echo $_SESSION['course3_people']; ?> personnes</span>
                                    <button type="submit" name="course3_increase">+</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

            <div class="flex-item">
                <div class="sidebar">
                    <h3>Réservation</h3>

                    <h4>Détails des cours réservés</h4>
                    <?php if ($_SESSION['course1_people'] > 0) { ?>
                        <p>1 - Découvrir l'équitation: <?php echo $_SESSION['course1_people']; ?> personnes</p>
                    <?php } ?>
                    <?php if ($_SESSION['course2_people'] > 0) { ?>
                        <p>2 - Balade en forêt: <?php echo $_SESSION['course2_people']; ?> personnes</p>
                    <?php } ?>
                    <?php if ($_SESSION['course3_people'] > 0) { ?>
                        <p>3 - Grande balade en forêt: <?php echo $_SESSION['course3_people']; ?> personnes</p>
                    <?php } ?>

                    <p>Taxe : 0,50 €</p>
                    <p class="total">Total: <?php echo number_format($total, 2, ',', ' ') ?> €</p>
                    <a href="#" class="btn">Réserver les cours</a>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p>Site internet créé par Claire Deneau, Thomas Brossier et Benjamin Doré</p>
        <p>Dans le cadre de la SAÉ "Poney"</p>
    </footer>
</body>
</html>

