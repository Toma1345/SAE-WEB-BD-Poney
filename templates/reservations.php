<?php
session_start();
require_once "../bd/DataBase.php";

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}

// Connexion à la base de données
try {
    $pdo = Database::getConnection();
} catch (PDOException $e) {
    die("Erreur de connexion : " . $e->getMessage());
}

// Récupérer la date et l'heure depuis l'URL
$date = isset($_GET['date']) ? $_GET['date'] : '2025-01-15';
$hour = isset($_GET['hour']) ? $_GET['hour'] : '9';

// Vérification pour retirer un éventuel "h" dans l'heure
$hour = preg_replace('/[^0-9]/', '', $hour);

// Gestion de la soumission du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $idC = $_POST['idC'];
    $idP = $_POST['idP'];
    $idA = $_SESSION['id'];

    // Insertion de la réservation dans la base de données
    $stmt = $pdo->prepare("INSERT INTO RESERVER (idC, idP, idA, paye) VALUES (:idC, :idP, :idA, :paye)");
    $stmt->execute([
        ':idC' => $idC,
        ':idP' => $idP,
        ':idA' => $idA,
        ':paye' => 1, // On suppose que le paiement est effectué
    ]);

    echo "<p>Réservation effectuée avec succès !</p>";
}

// Récupération des cours disponibles
$stmt = $pdo->prepare("SELECT * FROM COURS WHERE dateC = :date AND heureC = :hour");
$stmt->execute([':date' => $date, ':hour' => $hour]);
$cours = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupération des poneys
$poneys = $pdo->query("SELECT * FROM PONEY")->fetchAll(PDO::FETCH_ASSOC);
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
            background-color: #f8f8f8;
            margin: 0;
            padding: 0;
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
            text-decoration: none;
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

        .container {
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        h1 {
            font-size: 2rem;
            color: #2d572c;
            margin-bottom: 1rem;
        }

        h2 {
            font-size: 1.5rem;
            margin-bottom: 1rem;
        }

        p {
            font-size: 1rem;
            color: #333;
        }

        .cours {
            background-color: #ffffff;
            padding: 20px;
            margin-bottom: 1.5rem;
            border-radius: 8px;
            border: 1px solid #ccc;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .cours h2 {
            font-size: 1.3rem;
            color: #2d572c;
            margin-bottom: 0.5rem;
        }

        .cours p {
            margin-bottom: 0.5rem;
            color: #333;
        }

        form {
            display: flex;
            align-items: center;
            gap: 15px;
            flex-wrap: wrap;
        }

        form label {
            font-size: 1rem;
            color: #333;
        }

        select, button {
            padding: 0.5rem;
            font-size: 1rem;
            border-radius: 5px;
            border: 1px solid #ccc;
        }

        select {
            flex-grow: 1;
        }

        button {
            background-color: #2d572c;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #1b391b;
        }

        button:active {
            background-color: #164116;
        }

        .cours img {
            max-width: 100px;
            margin-right: 20px;
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
            <a href="logout.php" class="logout-btn">Se déconnecter</a>
        </nav>
    </header>

    <div class="container">
        <h1>Réservation de cours</h1>
        <p>Pour le <?= htmlspecialchars($date) ?> à <?= htmlspecialchars($hour) ?>h</p>

        <?php if (!empty($cours)) : ?>
            <?php foreach ($cours as $c) : ?>
                <div class="cours">
                    <h2><?= htmlspecialchars($c['nomC']) ?> (<?= htmlspecialchars($c['duree']) ?>h) - <?= htmlspecialchars($c['heureC']) ?>h</h2>
                    <p>Tarif : <?= htmlspecialchars($c['tarif']) ?> €</p>
                    <p>Places restantes : <?= htmlspecialchars($c['nbPersMax']) ?></p>
                    <form method="POST">
                        <input type="hidden" name="idC" value="<?= htmlspecialchars($c['idC']) ?>">

                        <label for="idP">Poney :</label>
                        <select name="idP" required>
                            <?php foreach ($poneys as $p) : ?>
                                <option value="<?= htmlspecialchars($p['idP']) ?>">
                                    <?= htmlspecialchars($p['nomP']) ?> (max <?= htmlspecialchars($p['poidsMax']) ?> kg)
                                </option>
                            <?php endforeach; ?>
                        </select>

                        <button type="submit">Réserver</button>
                    </form>
                </div>
            <?php endforeach; ?>
        <?php else : ?>
            <p>Aucun cours disponible pour cette date et heure.</p>
        <?php endif; ?>
    </div>

    <footer>
        <p>Site internet créé par Claire Deneau, Thomas Brossier et Benjamin Doré</p>
        <p>Dans le cadre de la SAÉ "Poney"</p>
    </footer>
</body>
</html>
