<?php
session_start();

if (isset($_GET['logout']) && $_GET['logout'] == 'success') {
    $logout_message = "Vous avez été déconnecté avec succès.";
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Vérification des identifiants (exemple basique)
    if ($username === 'camille' && $password === '1234') {
        $_SESSION['logged_in'] = true;
        $_SESSION['user_name'] = 'Camille Avril';
        header('Location: profil.php');
        exit;
    } else {
        $error = 'Identifiants incorrects.';
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
    </style>
</head>
<body>
    <div class="login-container">
        <h1>Connexion</h1>
        <?php if (isset($logout_message)) : ?>
            <p class="message"><?php echo $logout_message; ?></p>
        <?php endif; ?>
        <?php if (isset($error)) : ?>
            <p class="error"><?php echo $error; ?></p>
        <?php endif; ?>
        <form action="" method="POST">
            <input type="text" name="username" placeholder="Nom d'utilisateur" required>
            <input type="password" name="password" placeholder="Mot de passe" required>
            <button type="submit">Se connecter</button>
        </form>
    </div>
</body>
</html>
