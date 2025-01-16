<?php
// Connexion à la base de données
try {
    $pdo = new PDO('mysql:host=localhost;dbname=centre_equestre', 'username', 'password');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion : ' . $e->getMessage());
}

// Fonction pour récupérer les cours avec les places restantes
function getCourses($pdo) {
    $stmt = $pdo->prepare('
        SELECT c.idC, c.nomC, c.dateC, c.heureC AS heure_debut, c.duree, c.nbPersMax,
               c.nbPersMax - COUNT(r.idC) AS placesRestantes
        FROM COURS c
        LEFT JOIN RESERVATIONS r ON c.idC = r.idC
        GROUP BY c.idC
        HAVING placesRestantes > 0
    ');
    $stmt->execute();
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Fonction pour récupérer les poneys disponibles
function getAvailablePonies($pdo, $date, $startTime, $endTime) {
    $stmt = $pdo->prepare('
        SELECT idPoney, nomPoney 
        FROM PONEYS
        WHERE idPoney NOT IN (
            SELECT r.idPoney
            FROM RESERVATIONS r
            JOIN COURS c ON r.idC = c.idC
            WHERE c.dateC = :dateC 
              AND (
                  (c.heure BETWEEN :heure_debut AND :heure_fin)
                  OR (c.heure BETWEEN DATETIME(:heure_debut, "-2 hours") AND :heure_debut)
                  OR (c.heure BETWEEN :heure_fin AND DATETIME(:heure_fin, "+2 hours"))
              )
        )
    ');
    $stmt->execute([
        ':dateC' => $date,
        ':heure_debut' => $startTime,
        ':heure_fin' => $endTime
    ]);
    return $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Récupérer les cours disponibles
$courses = getCourses($pdo);

// Si le formulaire est soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $pdo->beginTransaction();
    try {
        foreach ($_POST['participants'] as $idC => $nbParticipants) {
            if ($nbParticipants > 0) {
                for ($i = 0; $i < $nbParticipants; $i++) {
                    $poids = $_POST['poids'][$idC][$i];
                    $idPoney = $_POST['poney'][$idC][$i];

                    // Vérification des données
                    if (empty($poids) || empty($idPoney)) {
                        throw new Exception('Tous les champs doivent être remplis.');
                    }

                    // Vérifie que le poney est encore disponible
                    $ponies = getAvailablePonies($pdo, $_POST['dateC'][$idC], $_POST['heure_debut'][$idC], $_POST['heure_fin'][$idC]);
                    $poneyDispo = array_filter($ponies, fn($p) => $p['idPoney'] == $idPoney);
                    if (empty($poneyDispo)) {
                        throw new Exception("Le poney sélectionné n'est plus disponible.");
                    }

                    // Enregistre la réservation
                    $stmt = $pdo->prepare('INSERT INTO RESERVATIONS (idC, poids, idPoney) VALUES (:idC, :poids, :idPoney)');
                    $stmt->execute([
                        ':idC' => $idC,
                        ':poids' => $poids,
                        ':idPoney' => $idPoney,
                    ]);
                }
            }
        }
        $pdo->commit();
        echo "<p style='color: green;'>Réservation réussie.</p>";
    } catch (Exception $e) {
        $pdo->rollBack();
        echo "<p style='color: red;'>Erreur : " . $e->getMessage() . "</p>";
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
        }

        h1 {
            text-align: center;
            color: #444;
        }

        fieldset {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 20px;
        }

        legend {
            font-weight: bold;
        }

        label {
            display: block;
            margin-top: 10px;
        }

        input, select, button {
            padding: 5px;
            margin-top: 5px;
        }

        button {
            background-color: #007BFF;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <h1>Réservations</h1>
    <form method="post">
        <?php foreach ($courses as $course) { ?>
            <fieldset>
                <legend><?php echo htmlspecialchars($course['nom']); ?></legend>
                <p>Date : <?php echo htmlspecialchars($course['dateC']); ?></p>
                <p>Heure : <?php echo htmlspecialchars($course['heure_debut']); ?></p>
                <p>Durée : <?php echo htmlspecialchars($course['duree']); ?> heures</p>
                <p>Places restantes : <?php echo htmlspecialchars($course['placesRestantes']); ?></p>

                <label for="participants_<?php echo $course['idC']; ?>">Nombre de participants :</label>
                <input type="number" name="participants[<?php echo $course['idC']; ?>]" min="0" max="<?php echo $course['placesRestantes']; ?>" value="0" required>

                <?php if ($course['placesRestantes'] > 0) { ?>
                    <?php for ($i = 0; $i < $course['placesRestantes']; $i++) { ?>
                        <label>Poids participant :</label>
                        <input type="number" name="poids[<?php echo $course['idC']; ?>][]" required>

                        <label>Poney :</label>
                        <select name="poney[<?php echo $course['idC']; ?>][]">
                            <option value="">-- Choisir un poney --</option>
                            <?php 
                            $ponies = getAvailablePonies($pdo, $course['dateC'], $course['heure_debut'], $course['heure_debut'] + $course['duree']);
                            foreach ($ponies as $poney) { ?>
                                <option value="<?php echo $poney['idPoney']; ?>">
                                    <?php echo htmlspecialchars($poney['nomPoney']); ?>
                                </option>
                            <?php } ?>
                        </select>
                    <?php } ?>
                <?php } ?>
            </fieldset>
        <?php } ?>
        <button type="submit">Réserver</button>
    </form>
</body>
</html>
