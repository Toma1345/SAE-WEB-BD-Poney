<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning des horaires - Grand Galop</title>
    <style>
        /* Styles globaux */
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f9f9f9;
        }
        header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 20px;
            background-color: white;
            border-bottom: 1px solid #ddd;
        }
        header h1 {
            font-size: 24px;
            color: #2e7d32;
            margin: 0;
        }
        header nav a {
            margin-left: 15px;
            text-decoration: none;
            color: #333;
            font-size: 14px;
        }
        header .btn {
            background-color: #2e7d32;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        main {
            padding: 20px;
        }
        h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
        }

        /* Planning */
        .planning {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
            overflow-x: auto;
            border: 1px solid #ddd;
            border-radius: 8px;
            background-color: white;
            padding: 10px;
        }
        .planning .row {
            display: flex;
            border-bottom: 1px solid #ddd;
        }
        .planning .header-row {
            font-weight: bold;
            background-color: #f3f3f3;
        }
        .planning .row div,
        .planning .header-row div {
            flex: 1;
            text-align: center;
            padding: 10px;
            border-right: 1px solid #ddd;
        }
        .planning .row div:last-child,
        .planning .header-row div:last-child {
            border-right: none;
        }
        .planning .time {
            font-weight: bold;
            background-color: #f3f3f3;
        }

        /* Boutons */
        .bubble {
            display: inline-block;
            padding: 10px 15px;
            border-radius: 20px;
            font-size: 14px;
            cursor: pointer;
        }
        .bubble.available {
            background-color: #e8f5e9;
            color: #2e7d32;
            border: 1px solid #2e7d32;
        }
        .bubble.unavailable {
            background-color: #fbe9e7;
            color: #d32f2f;
            border: 1px solid #d32f2f;
        }
        .bubble.available:hover {
            background-color: #c8e6c9;
        }
        .bubble.unavailable:hover {
            background-color: #ffcdd2;
        }
    </style>
</head>
<body>
    <header>
        <h1><a href="home.html">Grand Galop</a></h1>
        <nav>
            <a href="reservations.html">Réserver un cours</a>
            <a href="planning.php">Consulter les horaires</a>
            <a href="#">Consulter les tarifs</a>
            <a href="profil.html">Mon profil</a>
            <a href="mes-cours.html" class="btn">Mes cours</a>
        </nav>
    </header>

    <main>
        <h2>Les horaires</h2>
        <div class="planning">
            <!-- Ligne des jours -->
            <div class="row header-row">
                <div class="time"></div>
                <div>Lundi<br>21/10</div>
                <div>Mardi<br>22/10</div>
                <div>Mercredi<br>23/10</div>
                <div>Jeudi<br>24/10</div>
                <div>Vendredi<br>25/10</div>
                <div>Samedi<br>26/10</div>
                <div>Dimanche<br>27/10</div>
            </div>

            <?php
            // Données du planning
            $planning = [
                "9h" => ["Indisponible", "Réserver", "Indisponible", "Indisponible", "Indisponible", "Réserver", "Réserver"],
                "10h" => ["Indisponible", "Réserver", "Indisponible", "Indisponible", "Indisponible", "Réserver", "Réserver"],
                "11h" => ["Indisponible", "Indisponible", "Réserver", "Indisponible", "Indisponible", "Réserver", "Réserver"],
                "12h" => ["Réserver", "Réserver", "Réserver", "Indisponible", "Indisponible", "Réserver", "Réserver"],
                "13h" => ["Réserver", "Réserver", "Indisponible", "Indisponible", "Réserver", "Réserver", "Réserver"],
                "14h" => ["Réserver", "Indisponible", "Indisponible", "Indisponible", "Indisponible", "Réserver", "Réserver"],
                "15h" => ["Indisponible", "Réserver", "Indisponible", "Indisponible", "Réserver", "Indisponible", "Indisponible"],
                "16h" => ["Indisponible", "Indisponible", "Indisponible", "Réserver", "Réserver", "Indisponible", "Indisponible"],
                "17h" => ["Indisponible", "Réserver", "Réserver", "Indisponible", "Indisponible", "Indisponible", "Indisponible"],
            ];

            // Génération du planning
            foreach ($planning as $hour => $slots) {
                echo '<div class="row">';
                echo "<div class='time'>$hour</div>";
                foreach ($slots as $slot) {
                    $class = $slot === "Réserver" ? "available" : "unavailable";
                    echo "<div><div class='bubble $class'>$slot</div></div>";
                }
                echo '</div>';
            }
            ?>
        </div>
    </main>
</body>
</html>
