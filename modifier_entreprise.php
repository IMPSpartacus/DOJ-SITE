<?php
session_start();
require 'config.php';

if (!isset($_SESSION['agent_id'])) {
    header('Location: login.php');
    exit;
}

// Récupérer l'ID de l'entreprise
$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

// Si formulaire soumis
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raison_sociale = trim($_POST['raison_sociale'] ?? '');
    $secteur = trim($_POST['secteur'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');

    if ($raison_sociale && $secteur && $adresse) {
        $stmt = $pdo->prepare("UPDATE entreprises SET raison_sociale = ?, secteur = ?, adresse = ? WHERE id = ?");
        $stmt->execute([$raison_sociale, $secteur, $adresse, $id]);
        header('Location: registre.php?updated=1');
        exit;
    } else {
        $message = "Veuillez remplir tous les champs.";
    }
}

// Récupérer les infos actuelles
$stmt = $pdo->prepare("SELECT * FROM entreprises WHERE id = ?");
$stmt->execute([$id]);
$entreprise = $stmt->fetch();

if (!$entreprise) {
    echo "Entreprise introuvable.";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Modifier Entreprise</title>
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      background: #f0f4f8;
      padding: 40px;
      color: #00274d;
    }
    .container {
      max-width: 600px;
      margin: auto;
      background: white;
      padding: 30px;
      border-radius: 12px;
      box-shadow: 0 6px 18px rgba(0,0,0,0.1);
    }
    h1 {
      text-align: center;
      margin-bottom: 30px;
      color: #003366;
    }
    label {
      font-weight: bold;
      margin-top: 15px;
      display: block;
    }
    input[type="text"] {
      width: 100%;
      padding: 12px;
      margin-top: 6px;
      border: 1px solid #ccc;
      border-radius: 8px;
      margin-bottom: 20px;
    }
    .btn {
      display: block;
      width: 100%;
      padding: 14px;
      font-size: 16px;
      background: #003366;
      color: white;
      border: none;
      border-radius: 8px;
      cursor: pointer;
      font-weight: bold;
      transition: background 0.3s;
    }
    .btn:hover {
      background: #001f3f;
    }
    .back {
      display: block;
      text-align: center;
      margin-top: 20px;
      text-decoration: none;
      color: #003366;
      font-weight: bold;
    }
    .back:hover {
      text-decoration: underline;
    }
    .message {
      background: #ffdede;
      color: #a40000;
      padding: 12px;
      border-radius: 6px;
      text-align: center;
      margin-bottom: 20px;
    }
  </style>
</head>
<body>
  <div class="container">
    <h1>Modifier une Entreprise</h1>

    <?php if (!empty($message)) : ?>
      <div class="message"><?= htmlspecialchars($message) ?></div>
    <?php endif; ?>

    <form method="POST">
      <label for="raison_sociale">Raison Sociale</label>
      <input type="text" name="raison_sociale" id="raison_sociale" value="<?= htmlspecialchars($entreprise['raison_sociale']) ?>" required>

      <label for="secteur">Secteur</label>
      <input type="text" name="secteur" id="secteur" value="<?= htmlspecialchars($entreprise['secteur']) ?>" required>

      <label for="adresse">Adresse</label>
      <input type="text" name="adresse" id="adresse" value="<?= htmlspecialchars($entreprise['adresse']) ?>" required>

      <button type="submit" class="btn">✅ Mettre à jour</button>
    </form>

    <a href="registre.php" class="back">← Retour au registre</a>
  </div>
</body>
</html>
