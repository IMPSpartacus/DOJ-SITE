<?php
session_start();
if (!isset($_SESSION['agent_id'])) {
    header('Location: login.php');
    exit;
}
require 'config.php';

$message = '';

// Traitement formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $entreprise_id = $_POST['entreprise_id'] ?? '';
    $montant_du = $_POST['montant_du'] ?? '';
    $date_echeance = $_POST['date_echeance'] ?? '';

    if ($entreprise_id && $montant_du && $date_echeance) {
        $stmt = $pdo->prepare("INSERT INTO paiements_en_retard (entreprise_id, montant_du, date_echeance) VALUES (?, ?, ?)");
        try {
            $stmt->execute([$entreprise_id, $montant_du, $date_echeance]);
            $message = "✅ Paiement ajouté avec succès.";
        } catch (PDOException $e) {
            $message = "❌ Erreur : " . $e->getMessage();
        }
    } else {
        $message = "⚠️ Veuillez remplir tous les champs.";
    }
}

// Récupération des paiements
$stmt = $pdo->query("SELECT entreprise_id, montant_du, date_echeance FROM paiements_en_retard ORDER BY date_echeance ASC LIMIT 20");
$retards = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8" />
  <title>Gestion Fiscale – DOJ CRAD</title>
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet" />
  <style>
    * {
      box-sizing: border-box;
    }

    body {
      margin: 0;
      padding: 0;
      font-family: 'Inter', sans-serif;
      background: linear-gradient(135deg, #dfeffc, #f3f7fb);
      color: #1a2a4a;
      min-height: 100vh;
      padding: 40px 20px;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    h1 {
      font-size: 2rem;
      font-weight: 700;
      color: #00274d;
      margin-bottom: 30px;
      text-align: center;
    }

    .message {
      background-color: #fff;
      border-left: 6px solid #4caf50;
      padding: 16px 20px;
      border-radius: 10px;
      font-weight: 600;
      margin-bottom: 30px;
      max-width: 600px;
      box-shadow: 0 6px 16px rgba(0,0,0,0.06);
      animation: fadeIn 0.5s ease;
    }

    .message.error {
      border-left-color: #d93025;
      background-color: #ffe6e6;
    }

    form {
      background: rgba(255, 255, 255, 0.9);
      backdrop-filter: blur(8px);
      padding: 30px 35px;
      border-radius: 14px;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
      max-width: 480px;
      width: 100%;
      margin-bottom: 50px;
    }

    form h2 {
      margin: 0 0 20px;
      font-size: 1.5rem;
      color: #003366;
    }

    label {
      display: block;
      font-weight: 600;
      margin-bottom: 6px;
      color: #00274d;
    }

    input[type="number"],
    input[type="date"] {
      width: 100%;
      padding: 12px;
      margin-bottom: 18px;
      border: 1.6px solid #cfd8dc;
      border-radius: 8px;
      font-size: 1rem;
      background: #f8fcff;
      color: #1a2a4a;
      transition: 0.3s ease;
    }

    input:focus {
      border-color: #004080;
      outline: none;
      box-shadow: 0 0 0 3px rgba(0, 64, 128, 0.1);
    }

    button {
      width: 100%;
      padding: 14px;
      background-color: #004080;
      color: white;
      font-weight: 700;
      font-size: 1.1rem;
      border: none;
      border-radius: 10px;
      cursor: pointer;
      transition: background-color 0.3s ease, transform 0.2s ease;
    }

    button:hover {
      background-color: #003366;
      transform: translateY(-1px);
    }

    table {
      width: 100%;
      max-width: 1000px;
      border-collapse: collapse;
      background: white;
      border-radius: 14px;
      overflow: hidden;
      box-shadow: 0 10px 25px rgba(0, 0, 0, 0.08);
      margin-bottom: 50px;
    }

    thead {
      background-color: #00274d;
      color: white;
    }

    th, td {
      padding: 14px 20px;
      text-align: left;
      border-bottom: 1px solid #eaeaea;
    }

    tr:hover {
      background-color: #f1f9ff;
    }

    .back-link {
      display: inline-block;
      font-weight: 600;
      color: #004080;
      text-decoration: none;
      font-size: 1rem;
      margin-top: 20px;
      transition: 0.3s;
    }

    .back-link:hover {
      text-decoration: underline;
      color: #002f5f;
    }

    @keyframes fadeIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }

    @media (max-width: 600px) {
      form, table {
        width: 100%;
      }

      th, td {
        padding: 10px 12px;
      }
    }
  </style>
</head>
<body>

  <h1>💰 Gestion Fiscale — Retrait des Impots</h1>

  <?php if ($message): ?>
    <div class="message <?= str_contains($message, 'Erreur') ? 'error' : '' ?>">
      <?= htmlspecialchars($message) ?>
    </div>
  <?php endif; ?>

  <form method="POST" action="">
    <h2>Ajouter un retard fiscal</h2>

    <label for="entreprise_id">ID Entreprise</label>
    <input type="number" name="entreprise_id" id="entreprise_id" required placeholder="Ex : 101" min="1" />

    <label for="montant_du">Montant dû</label>
    <input type="number" name="montant_du" id="montant_du" step="0.01" required placeholder="Ex : 12000.00" min="0" />

    <label for="date_echeance">Date d’échéance</label>
    <input type="date" name="date_echeance" id="date_echeance" required />

    <button type="submit">Ajouter le paiement</button>
  </form>

  <?php if (count($retards) > 0): ?>
    <table>
      <thead>
        <tr>
          <th>ID Entreprise</th>
          <th>Montant dû</th>
          <th>Date d’échéance</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($retards as $r): ?>
          <tr>
            <td><?= htmlspecialchars($r['entreprise_id']) ?></td>
            <td><?= number_format($r['montant_du'], 2, ',', ' ') ?>   </td>
            <td><?= htmlspecialchars($r['date_echeance']) ?></td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php else: ?>
    <p style="text-align:center; font-style:italic; color:#555; margin-top:20px;">Aucun paiement en retard enregistré.</p>
  <?php endif; ?>

  <a href="dashboard.php" class="back-link">← Retour au Dashboard</a>

</body>
</html>
