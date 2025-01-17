<?php
// Connexion à la base de données
require_once "../bd/DataBase.php";
try {
    $pdo = Database::getConnection();
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Fonction pour récupérer les cours disponibles à une date donnée
function getCoursesByDate($pdo, $date) {
    $stmt = $pdo->prepare('
        SELECT c.idC, c.nomC, c.dateC, c.heureC AS heure_debut, c.duree, c.prix, c.nbPersonnesMax,
               c.nbPersonnesMax - COUNT(r.idC) AS placesRestantes
        FROM COURS c
        LEFT JOIN RESERVER r ON c.idC = r.idC
        WHERE c.dateC = :date
        GROUP BY c.idC
        HAVING placesRestantes > 0
    ');
    $stmt->execute([':date' => $date]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupération de la date sélectionnée ou date par défaut (aujourd'hui)
$dateSelectionnee = $_POST['date'] ?? date('Y-m-d');
$courses = getCoursesByDate($pdo, $dateSelectionnee);

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['reservation'])) {
    $pdo->beginTransaction();
    try {
        foreach ($_POST['participants'] as $idC => $nbParticipants) {
            for ($i = 0; $i < $nbParticipants; $i++) {
                $poids = $_POST['poids'][$idC][$i];
                $idPoney = $_POST['poney'][$idC][$i];

                if (empty($poids) || empty($idPoney)) {
                    throw new Exception('Tous les champs doivent être remplis.');
                }

                $stmt = $pdo->prepare('INSERT INTO RESERVER (idC, poids, idPoney) VALUES (:idC, :poids, :idPoney)');
                $stmt->execute([
                    ':idC' => $idC,
                    ':poids' => $poids,
                    ':idPoney' => $idPoney,
                ]);
            }
        }
        $pdo->commit();
        $successMessage = "Réservation réussie.";
    } catch (Exception $e) {
        $pdo->rollBack();
        $errorMessage = "Erreur : " . $e->getMessage();
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservations de Poneys</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            background-color: #f9f9f9;
        }

        .header {
            text-align: center;
            margin-bottom: 20px;
        }

        .container {
            display: flex;
            gap: 20px;
        }

        .courses {
            flex: 3;
        }

        .cart {
            flex: 1;
            background: #fff;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 8px;
        }

        .course {
            background: #fff;
            padding: 15px;
            border: 1px solid #ddd;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .course h3 {
            margin: 0 0 10px;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input, select {
            padding: 8px;
            margin-top: 5px;
            width: 100%;
            max-width: 300px;
            box-sizing: border-box;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            padding: 10px 15px;
            border-radius: 4px;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Réservation de cours</h1>
        <form method="post">
            <label for="date">Date :</label>
            <input type="date" id="date" name="date" value="<?php echo htmlspecialchars($dateSelectionnee); ?>" onchange="this.form.submit()">
        </form>
    </div>

    <div class="container">
        <div class="courses">
            <?php foreach ($courses as $course): ?>
                <div class="course">
                    <h3><?php echo htmlspecialchars($course['nom']); ?> (<?php echo htmlspecialchars($course['duree']); ?>h)</h3>
                    <p>Heure : <?php echo htmlspecialchars($course['heure_debut']); ?></p>
                    <p>Prix : <?php echo htmlspecialchars($course['prix']); ?> €</p>
                    <p>Places restantes : <?php echo htmlspecialchars($course['placesRestantes']); ?></p>

                    <form method="post">
                        <label for="participants_<?php echo $course['idC']; ?>">Nombre de participants :</label>
                        <input type="number" id="participants_<?php echo $course['idC']; ?>" name="participants[<?php echo $course['idC']; ?>]" min="0" max="<?php echo $course['placesRestantes']; ?>" value="0">

                        <?php for ($i = 0; $i < $course['placesRestantes']; $i++): ?>
                            <div class="participant-fields">
                                <label>Poids :</label>
                                <input type="number" name="poids[<?php echo $course['idC']; ?>][]" placeholder="kg">

                                <label>Poney :</label>
                                <select name="poney[<?php echo $course['idC']; ?>][]">
                                    <option value="">-- Choisir un poney --</option>
                                    <?php 
                                    $ponies = $pdo->query('SELECT idPoney, nomPoney FROM PONEYS')->fetchAll(PDO::FETCH_ASSOC);
                                    foreach ($ponies as $poney): ?>
                                        <option value="<?php echo $poney['idPoney']; ?>">
                                            <?php echo htmlspecialchars($poney['nomPoney']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        <?php endfor; ?>
                    </form>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="cart">
            <h2>Réserver</h2>
            <!-- Résumé des réservations ici -->
            <button type="submit">Réserver</button>
        </div>
    </div>
</body>
</html>
