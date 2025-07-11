<?php
session_start();
if (!isset($_SESSION['agent_id'])) {
    header('Location: login.php');
    exit;
}
require 'config.php';

$message = '';

// Ajout d'une entreprise
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $raison_sociale = trim($_POST['raison_sociale'] ?? '');
    $secteur = trim($_POST['secteur'] ?? '');
    $adresse = trim($_POST['adresse'] ?? '');

    if ($raison_sociale && $secteur && $adresse) {
        $stmt = $pdo->prepare("INSERT INTO entreprises (raison_sociale, secteur, adresse) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$raison_sociale, $secteur, $adresse]);
            $message = "✅ Entreprise ajoutée avec succès.";
        } catch (PDOException $e) {
            $message = "❌ Erreur : " . $e->getMessage();
        }
    } else {
        $message = "⚠️ Veuillez remplir tous les champs.";
    }
}

// Suppression
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $stmt = $pdo->prepare("DELETE FROM entreprises WHERE id = ?");
    $stmt->execute([$id]);
    $message = "🗑️ Entreprise supprimée.";
}

// Pagination
$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;

// Total entreprises
$total = $pdo->query("SELECT COUNT(*) FROM entreprises")->fetchColumn();
$totalPages = ceil($total / $limit);

// Données entreprises
$stmt = $pdo->prepare("SELECT id, raison_sociale, secteur, adresse FROM entreprises ORDER BY raison_sociale ASC LIMIT ? OFFSET ?");
$stmt->bindValue(1, $limit, PDO::PARAM_INT);
$stmt->bindValue(2, $offset, PDO::PARAM_INT);
$stmt->execute();
$entreprises = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Registre des Entreprises - CRAD</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #e3edf8, #f6f9fc);
      color: #00274d;
      padding: 40px 20px;
    }

    h1 {
      text-align: center;
      margin-bottom: 30px;
      font-size: 2rem;
    }

    .message {
      max-width: 700px;
      margin: 0 auto 25px;
      padding: 14px 20px;
      border-radius: 10px;
      font-weight: 600;
      text-align: center;
      background: #fff8e1;
      border: 1px solid #ffe082;
      color: #7b5e00;
    }

    form {
      max-width: 700px;
      margin: 0 auto 40px;
      padding: 30px;
      background: rgba(255,255,255,0.9);
      backdrop-filter: blur(6px);
      border-radius: 14px;
      box-shadow: 0 10px 30px rgba(0,0,0,0.07);
    }

    form h2 {
      margin-top: 0;
      color: #00274d;
      margin-bottom: 20px;
      font-size: 1.5rem;
      text-align: center;
    }

    label {
      font-weight: 600;
      margin-bottom: 6px;
      display: block;
    }

    input[type="text"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 18px;
      border: 1px solid #ccc;
      border-radius: 8px;
      font-size: 1rem;
      transition: border-color 0.3s ease;
    }

    input:focus {
      outline: none;
      border-color: #00274d;
    }

    button {
      display: block;
      width: 100%;
      background-color: #003366;
      color: white;
      padding: 14px;
      border: none;
      border-radius: 10px;
      font-weight: 600;
      font-size: 1rem;
      cursor: pointer;
      transition: background-color 0.3s ease;
    }

    button:hover {
      background-color: #001f3f;
    }

    table {
      width: 100%;
      max-width: 1000px;
      margin: auto;
      border-collapse: collapse;
      border-radius: 10px;
      overflow: hidden;
      background: white;
      box-shadow: 0 8px 20px rgba(0,0,0,0.05);
    }

    th, td {
      padding: 16px;
      text-align: left;
      border-bottom: 1px solid #eaeaea;
    }

    th {
      background-color: #00274d;
      color: white;
    }

    tr:hover {
      background-color: #f5faff;
    }

    .actions a {
      text-decoration: none;
      padding: 6px 12px;
      border-radius: 6px;
      font-size: 0.9em;
      color: white;
      margin-right: 5px;
    }

    .edit {
      background-color: #28a745;
    }

    .edit:hover {
      background-color: #218838;
    }

    .delete {
      background-color: #dc3545;
    }

    .delete:hover {
      background-color: #c82333;
    }

    .pagination {
      text-align: center;
      margin-top: 30px;
    }

    .pagination a,
    .pagination span {
      display: inline-block;
      padding: 10px 14px;
      margin: 0 4px;
      background: #00274d;
      color: white;
      border-radius: 6px;
      text-decoration: none;
      font-size: 0.9rem;
    }

    .pagination .current {
      background: #001f3f;
      font-weight: 700;
    }

    .back-link {
      display: block;
      text-align: center;
      margin-top: 40px;
      color: #003366;
      font-weight: 600;
      text-decoration: none;
    }

    .back-link:hover {
      text-decoration: underline;
    }

    @media (max-width: 600px) {
      form, table {
        width: 100%;
      }

      th, td {
        padding: 12px;
      }

      .actions a {
        margin-bottom: 4px;
        display: inline-block;
      }
    }
  </style>
</head>
<body>

  <h1>📁 Registre des Entreprises</h1>

  <?php if ($message): ?>
    <div class="message"><?= htmlspecialchars($message) ?></div>
  <?php endif; ?>

  <form method="POST" action="">
    <h2>Ajouter une entreprise</h2>
    <label for="raison_sociale">Raison Sociale</label>
    <input type="text" name="raison_sociale" id="raison_sociale" required>

    <label for="secteur">Secteur</label>
    <input type="text" name="secteur" id="secteur" required>

    <label for="adresse">Adresse</label>
    <input type="text" name="adresse" id="adresse" required>

    <button type="submit">➕ Ajouter</button>
  </form>

  <?php if (count($entreprises) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>Raison Sociale</th>
          <th>Secteur</th>
          <th>Adresse</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($entreprises as $e): ?>
          <tr>
            <td><?= htmlspecialchars($e['raison_sociale']) ?></td>
            <td><?= htmlspecialchars($e['secteur']) ?></td>
            <td><?= htmlspecialchars($e['adresse']) ?></td>
            <td class="actions">
              <a href="modifier_entreprise.php?id=<?= $e['id'] ?>" class="edit">Modifier</a>
              <a href="?delete=<?= $e['id'] ?>" class="delete" onclick="return confirm('Supprimer cette entreprise ?')">Supprimer</a>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>

    <?php if ($totalPages > 1): ?>
      <div class="pagination">
        <?php for ($i = 1; $i <= $totalPages; $i++): ?>
          <?php if ($i === $page): ?>
            <span class="current"><?= $i ?></span>
          <?php else: ?>
            <a href="?page=<?= $i ?>"><?= $i ?></a>
          <?php endif; ?>
        <?php endfor; ?>
      </div>
    <?php endif; ?>

  <?php else: ?>
    <p style="text-align:center; color:#777;">Aucune entreprise enregistrée.</p>
  <?php endif; ?>

  <a href="dashboard.php" class="back-link">← Retour au Dashboard</a>

</body>
</html>
