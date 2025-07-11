<?php
session_start();
require 'config.php';

if (isset($_SESSION['agent_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = trim($_POST['nom']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $password_confirm = $_POST['password_confirm'];

    if (!$nom || !$email || !$password || !$password_confirm) {
        $error = "Veuillez remplir tous les champs.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error = "Adresse email invalide.";
    } elseif ($password !== $password_confirm) {
        $error = "Les mots de passe ne correspondent pas.";
    } else {
        $stmt = $pdo->prepare('SELECT id FROM agents WHERE email = ?');
        $stmt->execute([$email]);
        if ($stmt->fetch()) {
            $error = "Cet email est déjà utilisé.";
        } else {
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            $stmt = $pdo->prepare('INSERT INTO agents (nom, email, mot_de_passe) VALUES (?, ?, ?)');
            if ($stmt->execute([$nom, $email, $hashed_password])) {
                $success = "Inscription réussie. <a href='login.php'>Cliquez ici pour vous connecter</a>.";
            } else {
                $error = "Une erreur est survenue, veuillez réessayer.";
            }
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Inscription Magistrat - CRAD</title>
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #001c38, #004080);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
      color: white;
    }

    .register-container {
      background: rgba(255,255,255,0.1);
      backdrop-filter: blur(10px);
      padding: 2.5rem 2.8rem;
      border-radius: 16px;
      max-width: 430px;
      width: 100%;
      box-shadow: 0 12px 32px rgba(0,0,0,0.3);
      animation: fadeIn 1s ease;
    }

    h2 {
      text-align: center;
      margin-bottom: 1.5rem;
      color: #fff;
      font-size: 1.8rem;
    }

    form {
      display: flex;
      flex-direction: column;
      gap: 1.2rem;
    }

    label {
      color: #fff;
      font-weight: 600;
      margin-bottom: 0.4rem;
    }

    input[type="text"],
    input[type="email"],
    input[type="password"] {
      padding: 12px;
      border-radius: 8px;
      border: 1.6px solid rgba(255,255,255,0.3);
      background: rgba(255,255,255,0.05);
      color: white;
      font-size: 1rem;
      transition: all 0.3s ease;
    }

    input:focus {
      border-color: #ffd54f;
      background: rgba(255,255,255,0.08);
      outline: none;
    }

    button {
      padding: 12px;
      background-color: #ffd54f;
      color: #00274d;
      font-weight: 700;
      font-size: 1.1rem;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      transition: all 0.3s ease;
    }

    button:hover {
      background-color: #ffca28;
      transform: scale(1.03);
    }

    .error, .success {
      padding: 12px;
      border-radius: 8px;
      font-weight: 600;
      text-align: center;
      margin-bottom: 1rem;
      animation: fadeIn 0.6s ease;
    }

    .error {
      background-color: #ef5350;
      color: #fff;
    }

    .success {
      background-color: #66bb6a;
      color: #fff;
    }

    .success a {
      color: #fff;
      font-weight: bold;
      text-decoration: underline;
    }

    .redirect {
      text-align: center;
      margin-top: 1.5rem;
      font-size: 0.95rem;
    }

    .redirect a {
      color: #ffd54f;
      font-weight: bold;
      text-decoration: none;
    }

    .redirect a:hover {
      text-decoration: underline;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 480px) {
      .register-container {
        padding: 2rem 1.5rem;
      }
    }
  </style>
</head>
<body>

<div class="register-container">
  <h2><i class="fa-solid fa-user-plus"></i> Inscription Magistrat</h2>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if ($success): ?>
    <div class="success"><?= $success ?></div>
  <?php endif; ?>

  <form method="post" action="">
    <div>
      <label for="nom">Nom Prénom</label>
      <input type="text" id="nom" name="nom" required value="<?= isset($_POST['nom']) ? htmlspecialchars($_POST['nom']) : '' ?>">
    </div>

    <div>
      <label for="email">Email DOJ</label>
      <input type="email" id="email" name="email" required value="<?= isset($_POST['email']) ? htmlspecialchars($_POST['email']) : '' ?>">
    </div>

    <div>
      <label for="password">Mot de passe</label>
      <input type="password" id="password" name="password" required>
    </div>

    <div>
      <label for="password_confirm">Confirmer le mot de passe</label>
      <input type="password" id="password_confirm" name="password_confirm" required>
    </div>

    <button type="submit">S'inscrire</button>
  </form>

  <p class="redirect">Déjà inscrit ? <a href="login.php">Connectez-vous ici</a>.</p>
</div>

</body>
</html>
