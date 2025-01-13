<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['logged_in'] !== true) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon profil - Grand Galop</title>
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
            max-width: 1200px;
            margin: 2rem auto;
            padding: 0 2rem;
        }

        .profile-header {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 2rem;
        }

        .profile-header h2 {
            font-size: 2rem;
            margin: 0;
        }

        .profile-header .user-info {
            display: flex;
            align-items: center;
            font-size: 1rem;
            color: #2d572c;
        }

        .profile-header .user-info svg {
            margin-right: 0.5rem;
        }

        .card {
            background-color: #ffffff;
            padding: 1.5rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            margin-bottom: 1.5rem;
        }

        .card img {
            width: 160px;
            border-radius: 10px;
        }

        .card h3 {
            font-size: 1.5rem;
            color: #2d572c;
            margin-bottom: 1rem;
        }

        .card .info-list p {
            margin: 0.5rem 0;
        }

        .info-list span {
            font-weight: bold;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 2rem;
        }

        .action-button {
            display: inline-block;
            background-color: #2d572c;
            color: #ffffff;
            padding: 0.5rem 1rem;
            border-radius: 5px;
            text-decoration: none;
            font-weight: bold;
            margin-top: 1rem;
        }

        .action-button:hover {
            background-color: #1b391b;
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
            <a href="logout.php" class="logout-btn">Se déconnecter</a>

        </nav>
    </header>

    <div class="container">
        <div class="profile-header">
            <h2>Mon profil</h2>
            <div class="user-info">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" fill="currentColor" viewBox="0 0 16 16">
                    <path d="M3 14s-1 0-1-1 1-4 6-4 6 3 6 4-1 1-1 1H3Zm9-1c-.001-.246-.153-.782-.727-1.344C10.71 11.09 9.499 10.5 8 10.5c-1.5 0-2.711.59-3.273 1.156-.574.562-.726 1.098-.727 1.344h8ZM8 9a3 3 0 1 1 0-6 3 3 0 0 1 0 6Zm0-1a2 2 0 1 0 0-4 2 2 0 0 0 0 4Z"/>
                </svg>
                <?php echo htmlspecialchars($_SESSION['user_name']); ?> <!-- Affiche le nom de l'utilisateur --> 
            </div>
            
        </div>

        <div class="grid">
            <!-- Cours Card -->
            <div class="card">
                <img src="images/calendrier.png" alt="calendrier">
                <h3>Consulter mes cours</h3>
                <p>Vous avez <strong>2 cours</strong> de réservés</p>
                <p><strong>225 €</strong></p>
                <a href="#" class="action-button">Voir les cours</a>
            </div>

            <!-- Poneys Card -->
            <div class="card">
                <img src="images/myponeys.png" alt="Photo de poneys">
                <h3>Consulter mes anciens poneys</h3>
                <p>0 poney pour le moment</p>
            </div>
        </div>
        <div class="grid"></div>
            <!-- Infos personnelles -->
            <div class="card">
                <h3>Vos informations</h3>
                <div class="info-list">
                    <p><span>Nom :</span> Avril</p>
                    <p><span>Prénom :</span> Camille</p>
                    <p><span>Adresse :</span> 5 allée des chevaux, Paris 75000</p>
                    <p><span><a href="cotisation.html">Cotisation</a> à jour :</span> Oui</p>
                    <p><span>Votre poney favori :</span> Looping</p>
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