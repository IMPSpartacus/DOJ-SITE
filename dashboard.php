<?php
session_start();
require 'config.php';

if (!isset($_SESSION['agent_id'])) {
    header('Location: login.php');
    exit;
}

$stmt = $pdo->prepare('SELECT * FROM agents WHERE id = ?');
$stmt->execute([$_SESSION['agent_id']]);
$agent = $stmt->fetch();

if (!$agent) {
    session_destroy();
    header('Location: login.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Tableau de Bord - CRAD Magistrat</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <script src="https://unpkg.com/lucide@latest"></script>
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #e3ecf5, #f9fbfc);
      color: #1e2b40;
      display: flex;
      min-height: 100vh;
    }

    .sidebar {
      position: fixed;
      top: 0;
      left: 0;
      width: 240px;
      height: 100vh;
      background: #00274d;
      color: #fff;
      padding-top: 30px;
      display: flex;
      flex-direction: column;
      gap: 20px;
      z-index: 1000;
    }

    .sidebar h2 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 22px;
      font-weight: 600;
    }

    .sidebar a {
      padding: 14px 30px;
      text-decoration: none;
      color: white;
      font-weight: 500;
      display: flex;
      align-items: center;
      gap: 12px;
      transition: background 0.3s, border-left 0.3s;
      border-left: 4px solid transparent;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background: #004080;
      border-left: 4px solid #ffd54f;
    }

    .main {
      margin-left: 240px;
      padding: 40px 50px;
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    .header {
      display: flex;
      justify-content: space-between;
      align-items: center;
      margin-bottom: 40px;
    }

    .header h1 {
      font-size: 28px;
      color: #00274d;
      font-weight: 700;
    }

    .user-info {
      font-weight: 600;
      font-size: 16px;
      color: #004080;
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .cards {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(260px, 1fr));
      gap: 24px;
    }

    .card {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(4px);
      border-radius: 14px;
      padding: 24px;
      box-shadow: 0 12px 24px rgba(0, 0, 0, 0.07);
      transition: transform 0.3s ease, box-shadow 0.3s ease;
    }

    .card:hover {
      transform: translateY(-6px);
      box-shadow: 0 14px 30px rgba(0, 0, 0, 0.1);
    }

    .card h3 {
      font-size: 18px;
      color: #003366;
      margin-bottom: 12px;
    }

    .card p {
      font-size: 15px;
      line-height: 1.6;
      margin: 0.4rem 0;
    }

    .card a {
      color: #004c99;
      text-decoration: underline;
      font-weight: 600;
    }

    .actions {
      margin-top: 50px;
    }

    .actions h3 {
      font-size: 20px;
      margin-bottom: 12px;
      color: #00274d;
    }

    .actions ul {
      list-style: none;
      padding: 0;
    }

    .actions li {
      background: #fff;
      padding: 14px 20px;
      margin-bottom: 10px;
      border-radius: 10px;
      box-shadow: 0 6px 16px rgba(0, 0, 0, 0.05);
      font-size: 14px;
    }

    .logout {
      position: fixed;
      bottom: 25px;
      left: 50%;
      transform: translateX(-50%);
      background: #d32f2f;
      padding: 12px 22px;
      border-radius: 8px;
      color: white;
      font-weight: 600;
      text-decoration: none;
      box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
      transition: background 0.3s ease;
    }

    .logout:hover {
      background: #a3201f;
    }

    @media (max-width: 768px) {
      .sidebar {
        position: static;
        width: 100%;
        height: auto;
        flex-direction: row;
        flex-wrap: wrap;
        justify-content: center;
        padding: 10px;
        gap: 10px;
      }

      .main {
        margin-left: 0;
        padding: 20px;
      }

      .logout {
        position: relative;
        bottom: auto;
        left: auto;
        transform: none;
        margin-top: 30px;
      }
    }
  </style>
</head>
<body>

  <div class="sidebar">
    <h2>⚖️ CRAD DOJ</h2>
    <a href="dashboard.php" class="active"><i data-lucide="layout-dashboard"></i> Accueil</a>
    <a href="registre.php"><i data-lucide="building-2"></i> Registre</a>
    <a href="fiscalite.php"><i data-lucide="dollar-sign"></i> Fiscalité</a>
    <a href="contact.php"><i data-lucide="mail"></i> Contact</a>
  </div>

  <div class="main">
    <div class="header">
      <h1>Tableau de Bord</h1>
      <div class="user-info">
        <i data-lucide="user-check"></i> Magistrat : <?= htmlspecialchars($agent['nom']) ?>
      </div>
    </div>

    <div class="cards">
      <div class="card">
        <h3>Informations personnelles</h3>
        <p><strong>Email :</strong> <?= htmlspecialchars($agent['email']) ?></p>
        <p><strong>ID Magistrat :</strong> <?= htmlspecialchars($agent['id']) ?></p>
      </div>

      <div class="card">
        <h3>Effectif des Magistrats</h3>
        <ul style="padding-left: 20px; margin: 0; color: #003366;">
        <li><strong>Eziio Mason Reed</strong> : Pas de grade trop bo</li> 
        <li><strong>Ethan David West</strong> : Federal Judge</li>
          <li><strong>Calvin Tournesol</strong> : Deputy Attorney General</li>
        </ul>
      </div>

      <div class="card">
        <h3>Navigation rapide</h3>
        <p><a href="registre.php">📁 Consulter le registre</a></p>
        <p><a href="fiscalite.php">💰 Gestion fiscale</a></p>
        <p><a href="contact.php">✉️ Contacter une division</a></p>
      </div>

      <div class="card">
        <h3>Statistiques</h3>
        <p>Entreprises suivies : <strong>2</strong></p>
        <p>Audits récents : <strong>1</strong></p>
      </div>
    </div>

    <div class="actions">
      <h3>Dernières actions</h3>
      <ul>
        <li>✅ 09/07 - Création du site internet OP </li>
        <li>📄 08/07 - Developpement du site</li>
      </ul>
    </div>

    <a href="logout.php" class="logout">🚪 Se déconnecter</a>
  </div>

  <script>
    lucide.createIcons();
  </script>
</body>
</html>
