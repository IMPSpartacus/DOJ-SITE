<?php
session_start();
require 'config.php';

if (isset($_SESSION['agent_id'])) {
    header('Location: dashboard.php');
    exit;
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];

    $stmt = $pdo->prepare('SELECT * FROM agents WHERE email = ?');
    $stmt->execute([$email]);
    $agent = $stmt->fetch();

    if ($agent && password_verify($password, $agent['mot_de_passe'])) {
        $_SESSION['agent_id'] = $agent['id'];
        $_SESSION['agent_nom'] = $agent['nom'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Email ou mot de passe incorrect.";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>Connexion Magistrat - CRAD</title>
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" />
  <style>
    * { box-sizing: border-box; }

    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #001c38, #003366);
      min-height: 100vh;
      display: flex;
      align-items: center;
      justify-content: center;
    }

    .alert-banner {
      background-color: #c62828;
      color: #fff;
      padding: 12px 20px;
      font-weight: bold;
      text-align: center;
      width: 100%;
      position: fixed;
      top: 0;
      left: 0;
      z-index: 10;
      box-shadow: 0 4px 12px rgba(0,0,0,0.3);
      font-size: 15px;
    }

    .alert-banner a {
      color: #ffd54f;
      text-decoration: underline;
    }

    .login-container {
      background: rgba(255, 255, 255, 0.1);
      backdrop-filter: blur(12px);
      border-radius: 16px;
      padding: 2.5rem 3rem;
      box-shadow: 0 12px 32px rgba(0,0,0,0.3);
      width: 100%;
      max-width: 420px;
      z-index: 1;
      animation: fadeIn 1s ease;
      transition: transform 0.3s ease;
    }

    .login-container:hover {
      transform: scale(1.015);
    }

    .login-container h2 {
      color: #fff;
      margin-bottom: 1.5rem;
      font-weight: 600;
      font-size: 1.8rem;
      text-align: center;
    }

    .login-container .logo {
      font-size: 3rem;
      color: #ffd54f;
      text-align: center;
      margin-bottom: 15px;
    }

    form {
      display: flex;
      flex-direction: column;
    }

    label {
      font-weight: 600;
      color: #fff;
      margin-bottom: 0.4rem;
      margin-top: 1.1rem;
    }

    input[type="email"],
    input[type="password"] {
      padding: 12px;
      border: 1.8px solid rgba(255,255,255,0.3);
      border-radius: 8px;
      font-size: 1rem;
      background: rgba(255,255,255,0.05);
      color: #fff;
    }

    input:focus {
      border-color: #ffd54f;
      outline: none;
      background: rgba(255,255,255,0.1);
    }

    .input-group {
      position: relative;
    }

    .toggle-password {
      position: absolute;
      top: 50%;
      right: 14px;
      transform: translateY(-50%);
      color: #ccc;
      cursor: pointer;
    }

    .toggle-password:hover {
      color: #ffd54f;
    }

    button {
      margin-top: 2rem;
      padding: 12px;
      background-color: #ffd54f;
      color: #00274d;
      font-weight: 700;
      font-size: 1.1rem;
      border: none;
      border-radius: 8px;
      cursor: pointer;
    }

    button:hover {
      background-color: #ffca28;
      transform: scale(1.03);
    }

    .error, .success {
      margin-top: 1rem;
      padding: 12px;
      border-radius: 8px;
      font-weight: 600;
      animation: fadeIn 0.6s ease;
      text-align: center;
    }

    .error {
      background-color: #ef5350;
      color: #fff;
    }

    .success {
      background-color: #66bb6a;
      color: #fff;
    }

    .back-home {
      display: block;
      margin-top: 1.8rem;
      text-align: center;
      color: #ffd54f;
      font-weight: 600;
      text-decoration: none;
    }

    .back-home:hover {
      color: #fff176;
      text-decoration: underline;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(20px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-height: 600px) {
      body {
        align-items: flex-start;
        padding-top: 60px;
      }
    }

    @media (max-width: 480px) {
      .login-container {
        padding: 2rem 1.5rem;
      }
    }
  </style>
</head>
<body>

<div class="alert-banner">
  ⚠️ Accès réservé aux magistrats autorisés. Si vous n’êtes pas concerné, <a href="dojcrad.html">retournez à l'accueil</a>.
</div>

<div class="login-container">
  <div class="logo"><i class="fa-solid fa-scale-balanced"></i></div>
  <h2>Connexion DOJ</h2>

  <?php if ($error): ?>
    <div class="error"><?= htmlspecialchars($error) ?></div>
  <?php endif; ?>

  <?php if (isset($_GET['register']) && $_GET['register'] === 'success'): ?>
    <div class="success">Inscription réussie, vous pouvez vous connecter.</div>
  <?php endif; ?>

  <form method="post" action="">
    <label for="email">Email DOJ</label>
    <input type="email" id="email" name="email" required placeholder="exemple@doj.gov" />

    <label for="password">Mot de passe</label>
    <div class="input-group">
      <input type="password" id="password" name="password" required placeholder="Votre mot de passe" />
      <i class="fa fa-eye toggle-password" id="togglePassword"></i>
    </div>

    <button type="submit">Se connecter</button>
  </form>

  <a href="dojcrad.html" class="back-home">🏠 Retour à l’accueil</a>
</div>

<script>
  const toggle = document.getElementById('togglePassword');
  const input = document.getElementById('password');

  toggle.addEventListener('click', () => {
    const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
    input.setAttribute('type', type);
    toggle.classList.toggle('fa-eye');
    toggle.classList.toggle('fa-eye-slash');
  });
</script>

</body>
</html>
