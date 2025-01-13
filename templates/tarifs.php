<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nos Tarifs - Grand Galop</title>
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

        .container {
            width: 90%;
            max-width: 1200px;
            margin: 0 auto;
        }

        .tarifs-container {
            padding: 2rem 0;
            text-align: center;
        }

        .tarifs-container h1 {
            font-size: 2.5rem;
            margin-bottom: 0.5rem;
        }

        .subtitle {
            font-size: 1rem;
            color: #666;
            margin-bottom: 2rem;
        }

        .tarifs-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); 
            gap: 1.5rem;
            padding: 1rem;
        }

        .tarif-card {
            background-color: #fff;
            padding: 1.5rem;
            border: 1px solid #eaeaea;
            border-radius: 10px;
            text-align: left;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            margin: 0 10px;
        }

        .tarif-card h3 {
            font-size: 1.25rem;
            color: #2d623d;
            margin-bottom: 0.5rem;
        }

        .price {
            font-size: 1.5rem;
            font-weight: bold;
            color: #2d623d;
            margin-bottom: 1rem;
        }

        .tarif-card p {
            font-size: 0.9rem;
            color: #666;
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
            <a href="reservations.php">Réserver un cours</a>
            <a href="planning.php">Consulter les horaires</a>
            <a href="tarifs.php">Consulter les tarifs</a>
            <a href="planningcours.php">Mes cours</a>
            <a href="profil.php" class="btn">Mon profil</a>
        </nav>
    </header>

    <main class="tarifs-container">
        <h1>Nos tarifs</h1>
        <p class="subtitle">Valable du 01 septembre 2024 au 31 août 2025</p>
        <div class="tarifs-grid">
            <div class="tarif-card">
                <h3>Licence FFE</h3>
                <p class="price">50 €</p>
                <p>Licence valable pour la saison 2024/2025</p>
            </div>
            <div class="tarif-card">
                <h3>Découverte de l’équitation</h3>
                <p class="price">75 € / heure</p>
                <p>Profiter d'une balade d'une heure à la découverte de l'équitation</p>
            </div>
            <div class="tarif-card">
                <h3>Balade en forêt</h3>
                <p class="price">150 €</p>
                <p>Profiter d'une balade en pleine forêt loin du bruit des voitures</p>
            </div>
            <div class="tarif-card">
                <h3>Grande balade en forêt</h3>
                <p class="price">170 €</p>
                <p>Grande balade pour se reconnecter avec la nature</p>
            </div>
        </div>
    </main>
    <footer>
        <p>Site internet créé par Claire Deneau, Thomas Brossier et Benjamin Doré</p>
        <p>Dans le cadre de la SAÉ "Poney"</p>
    </footer>
</body>
</html>