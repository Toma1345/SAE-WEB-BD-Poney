<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Planning des horaires - Grand Galop</title>
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

        main {
            padding: 20px;
        }
        h2 {
            text-align: center;
            font-size: 28px;
            margin-bottom: 20px;
        }
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

    <main>
        <h2>Les horaires</h2>
        <div class="planning">
            <div class="row header-row">
                <div class="time"></div>
                <div>Lundi</div>
                <div>Mardi</div>
                <div>Mercredi</div>
                <div>Jeudi</div>
                <div>Vendredi</div>
                <div>Samedi</div>
                <div>Dimanche</div>
            </div>

            <?php
            $planning = [
                "9h" => ["Réserver", "Réserver", "Réserver", "Réserver", "Réserver", "Réserver", "Réserver"],
                "10h" => ["Réserver", "Réserver", "Indisponible", "Réserver", "Réserver", "Réserver", "Réserver"],
                "11h" => ["Réserver", "Réserver", "Réserver", "Réserver", "Réserver", "Réserver", "Réserver"],
                "13h" => ["Réserver", "Réserver", "Réserver", "Réserver", "Réserver", "Réserver", "Réserver"],
                "14h" => ["Réserver", "Réserver", "Réserver", "Indisponible", "Réserver", "Réserver", "Réserver"],
                "15h" => ["Réserver", "Réserver", "Réserver", "Réserver", "Réserver", "Réserver", "Réserver"],
                "16h" => ["Réserver", "Réserver", "Réserver", "Réserver", "Réserver", "Réserver", "Réserver"],
                "17h" => ["Réserver", "Réserver", "Réserver", "Réserver", "Réserver", "Réserver", "Réserver"],
            ];

            $baseDate = new DateTime();

            foreach ($planning as $hour => $slots) {
                echo '<div class="row">';
                echo "<div class='time'>$hour</div>";
                foreach ($slots as $index => $slot) {
                    $class = $slot === "Réserver" ? "available" : "unavailable";

                    $columnDate = clone $baseDate;
                    $columnDate->modify("+$index day");
                    $formattedDate = $columnDate->format('Y-m-d');

                    echo "<div>";
                    if ($slot === "Réserver") {
                        echo "<a href='reservations.php?hour=$hour&date=$formattedDate' class='bubble $class'>Réserver</a>";
                    } else {
                        echo "<div class='bubble $class'>$slot</div>";
                    }
                    echo "</div>";
                }
                echo '</div>';
            }
            ?>


        </div>
    </main>
    <footer>
        <p>Site internet créé par Claire Deneau, Thomas Brossier et Benjamin Doré</p>
        <p>Dans le cadre de la SAÉ "Poney"</p>
    </footer>
</body>
</html>

