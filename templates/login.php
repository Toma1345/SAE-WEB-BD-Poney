<?php
session_start();

require_once "../bd/DataBase.php";

if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    $logout_message = "Vous avez été déconnecté avec succès.";
}

try {
    $pdo = Database::getConnection();
} catch (PDOException $e) {
    die("Erreur de connexion à la base de données : " . $e->getMessage());
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = trim($_POST['username']);
    $password = trim($_POST['password']);

    if (strpos($username, '.') !== false) {
        [$prenom, $nom] = explode('.', $username, 2);

        $prenom = strtolower($prenom);
        $nom = strtolower($nom);

        $stmt = $pdo->prepare('SELECT * FROM ADHERENTS WHERE LOWER(prenomA) = :prenom AND LOWER(nomA) = :nom');
        $stmt->bindParam(':prenom', $prenom);
        $stmt->bindParam(':nom', $nom);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user) {
            if ($password === $user['mdp']) {
                $_SESSION['logged_in'] = true;
                $_SESSION['user_name'] = ucfirst($user['prenomA']) . ' ' . ucfirst($user['nomA']);
                $_SESSION['prenom'] = $user['prenomA'];
                $_SESSION['nom'] = $user['nomA'];
                $_SESSION['numtel'] = $user['numeroTel'];
                $_SESSION['email'] = $user['mail'];
                $_SESSION['cotisation'] = $user['cotisation'];
                header('Location: profil.php');
                exit;
            } else {
                $error = 'Mot de passe incorrect.';
            }
        } else {
            $error = 'Identifiants incorrects.';
        }
    } else {
        $error = "Le format du nom d'utilisateur est invalide. Utilisez 'prenom.nom'.";
    }
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Grand Galop</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f8f8f8;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #ffffff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            width: 300px;
            text-align: center;
            position: relative;
        }
        .login-container h1 {
            color: #2d572c;
            margin-bottom: 1.5rem;
        }
        .login-container form {
            display: flex;
            flex-direction: column;
        }
        .login-container input {
            margin-bottom: 1rem;
            padding: 0.5rem;
            border: 1px solid #ddd;
            border-radius: 5px;
        }
        .login-container button {
            background-color: #2d572c;
            color: #ffffff;
            padding: 0.5rem;
            border: none;
            border-radius: 5px;
            font-weight: bold;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        .login-container button:hover {
            background-color: #1b391b;
        }
        .message {
            margin-bottom: 1rem;
            color: #2d572c;
            font-weight: bold;
        }
        .error {
            color: #e63946;
            font-weight: bold;
        }
        .back-link {
            position: left;
            top: 10px;
            right: 10px;
            text-decoration: none;
            color: #aaa;
            font-size: 0.9rem;
            font-weight: bold;
            display: flex;
            align-items: center;
            transition: color 0.3s ease;
        }
        .back-link:hover {
            color: #555;
        }
        .back-link svg {
            margin-right: 5px;
        }
    </style>
</head>
<body>
    
    <div class="login-container">
        <a class="back-link" href="home.php">
            ← Retour
        </a>
        <h1>Connexion</h1>
        <?php if (isset($logout_message)) : ?>
            <p class="message"><?php echo $logout_message; ?></p>
        <?php endif; ?>
        <?php if (isset($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Nom d'utilisateur (prenom.nom)" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
