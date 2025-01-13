<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Grand Galop - Découvrez nos poneys</title>
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
            text-decoration:none;
        }

        .hero {
            text-align: center;
            padding: 60px 20px;
            background-color: #fff;
        }

        .hero h2 {
            font-size: 36px;
            font-weight: 400;
            line-height: 1.5;
        }

        .hero em {
            font-style: italic;
            font-weight: bold;
        }

        .hero .button-primary {
            margin-top: 20px;
        }

        .gallery {
            display: flex;
            justify-content: center;
            gap: 20px;
            margin: 40px 20px;
        }

        .gallery img {
            max-width: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .gallery figcaption {
            margin-top: 10px;
            font-size: 14px;
            text-align: center;
            color: #666;
        }

        .content {
            padding: 40px 20px;
            background-color: #fff;
            text-align: center;
        }

        .content h3 {
            font-size: 20px;
            margin-bottom: 20px;
        }

        .content p {
            font-size: 16px;
            line-height: 1.6;
            color: #555;
        }

        .content strong {
            font-weight: bold;
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

    <section class="hero">
        <h2>
            Vous êtes <em>licenciés</em> à la FFE<br>
            Venez découvrir nos <em>poneys</em> !
        </h2>
        <a href="reservations.php" class="button-primary">Réserver un cours</a>
    </section>

    <section class="gallery">
        <figure>
            <img src="images/bubble.png" alt="Bubble">
            <figcaption>Bubble &mdash; Il adore prendre la pose pour les photos !</figcaption>
        </figure>
        <figure>
            <img src="images/california.jpeg" alt="California">
            <figcaption>California &mdash; Le plus jeune poney de notre centre équestre !</figcaption>
        </figure>
    </section>

    <section class="content">
        <h3>REJOIGNEZ-NOUS !</h3>
        <p>
            Envie de vivre une expérience unique avec nos poneys ? Que vous soyez débutant ou confirmé,
            nous proposons des cours individuels ou collectifs adaptés à tous les âges. Réservez facilement
            vos séances avec nos moniteurs qualifiés, tout en respectant le bien-être des poneys. Venez galoper
            avec nous et découvrez le plaisir de l'équitation en pleine nature !
        </p>
        <p>
            <strong>Réservez dès maintenant et rejoignez l’aventure Grand Galop !</strong>
        </p>
    </section>
    <footer>
        <p>Site internet créé par Claire Deneau, Thomas Brossier et Benjamin Doré</p>
        <p>Dans le cadre de la SAÉ "Poney"</p>
    </footer>
</body>
</html>