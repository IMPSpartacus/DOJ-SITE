<?php
session_start();
if (!isset($_SESSION['agent_id'])) {
    header('Location: login.php');
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Contact Interne - CRAD</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      padding: 40px;
      background: #f0f4f8;
      color: #00274d;
    }
    .container {
      max-width: 800px;
      margin: auto;
      background: white;
      padding: 40px;
      border-radius: 12px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      color: #003366;
      margin-bottom: 30px;
    }
    p.info {
      font-size: 1.1rem;
      background: #e3f2fd;
      padding: 20px;
      border-left: 5px solid #1976d2;
      margin-bottom: 30px;
      border-radius: 8px;
    }
    form label {
      font-weight: bold;
      display: block;
      margin-top: 20px;
    }
    input[type="text"], input[type="email"], textarea {
      width: 100%;
      padding: 12px;
      border: 1px solid #ccc;
      border-radius: 6px;
      margin-top: 8px;
    }
    textarea {
      height: 140px;
      resize: vertical;
    }
    button {
      margin-top: 30px;
      padding: 14px 24px;
      background: #003366;
      color: white;
      border: none;
      border-radius: 6px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }
    button:hover {
      background: #001f3f;
    }
    .back-link {
      display: inline-block;
      margin-top: 20px;
      color: #003366;
      text-decoration: none;
      font-weight: bold;
    }
    .back-link:hover {
      text-decoration: underline;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Contact Interne - CRAD</h1>

    <p class="info">
      Pour toute requête technique ou rapport administratif, veuillez utiliser le formulaire ci-dessous. Vos messages seront transmis directement au responsable technique ou au superviseur concerné.
    </p>

    <form method="POST" action="#">
      <label for="nom">Nom Prénom</label>
      <input type="text" id="nom" name="nom" required>

      <label for="email">Email DOJ</label>
      <input type="email" id="email" name="email" required>

      <label for="message">Message / Rapport</label>
      <textarea id="message" name="message" required></textarea>

      <button type="submit">Envoyer la demande</button>
    </form>

    <a href="dashboard.php" class="back-link">&larr; Retour au dashboard</a>
  </div>
</body>
</html>
