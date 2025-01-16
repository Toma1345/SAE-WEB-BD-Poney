<?php

require_once "../bd/DataBase.php";

try {
    // Connexion à la base de données
    $pdo = Database::getConnection();

    // Requête pour récupérer les cours
    $query = "
        SELECT COURS.idC, COURS.tarif, COURS.dateC, COURS.heureC, MONITEUR.nomM, MONITEUR.prenomM
        FROM COURS
        JOIN MONITEUR JOIN RESERVER JOIN ADHERENT ON COURS.idM = MONITEUR.idM AND ADHERENT.nomA = :nom
    ";
    $stmt = $pdo->prepare($query);
    $stmt->bindParam(':nom',$_SESSION['nom']);
    $stmt->execute();

    // Récupération des résultats
    $courses = $stmt->fetchAll(PDO::FETCH_ASSOC);

    // Structure des jours et heures
    $days = ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi', 'Dimanche'];
    $hours = ['9h', '10h', '11h', '13h', '14h', '15h', '16h', '17h'];

    // Organisation des cours par jour et heure
    $planning = [];
    foreach ($courses as $course) {
        $dayIndex = date('N', strtotime($course['dateCours'])) - 1; // 0 pour lundi
        $hour = $course['heureC'] . 'h';
        $planning[$dayIndex][$hour] = $course;
    }
} catch (PDOException $e) {
    die("Erreur lors de la connexion à la base de données : " . $e->getMessage());
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning - Mes cours</title>
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

        .button-primary {
            background-color: #558c45;
            color: #fff;
            padding: 8px 16px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
        }

        .container {
            padding: 40px;
            text-align: center;
        }

        h2 {
            font-size: 36px;
            margin-bottom: 20px;
        }

        .planning {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 10px;
            background-color: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .planning div {
            border: 1px solid #d4d4d4;
            border-radius: 8px;
            padding: 10px;
            font-size: 14px;
            text-align: center;
        }

        .planning .header {
            font-weight: bold;
            background-color: #f9f9f9;
        }

        .planning .time {
            background-color: #f9f9f9;
        }

        .planning .course {
            background-color: #eef6e9;
            border: 1px solid #558c45;
            color: #558c45;
            font-size: 14px;
        }

        .planning .course strong {
            display: block;
            margin-bottom: 5px;
        }

        .planning .course:hover {
            background-color: #d9ecd0;
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
        <h2>Mes cours</h2>
        <div class="planning">
            <!-- Première ligne : entêtes des jours -->
            <div class="header time"></div>
            <?php foreach ($days as $day): ?>
                <div class="header"><?= $day ?></div>
            <?php endforeach; ?>

            <!-- Lignes horaires -->
            <?php foreach ($hours as $hour): ?>
                <div class="time"><?= $hour ?></div>
                <?php for ($i = 0; $i < count($days); $i++): ?>
                    <div>
                        <?php if (!empty($planning[$i][$hour])): ?>
                            <div class="course">
                                <strong><?= $planning[$i][$hour]['nomM'] . ' ' . $planning[$i][$hour]['prenomM'] ?></strong>
                                <?= $planning[$i][$hour]['tarif'] ?> €
                            </div>
                        <?php endif; ?>
                    </div>
                <?php endfor; ?>
            <?php endforeach; ?>
        </div>
    </div>
    <footer>
        <p>Site internet créé par Claire Deneau, Thomas Brossier et Benjamin Doré</p>
        <p>Dans le cadre de la SAÉ "Poney"</p>
    </footer>
</body>
</html>

