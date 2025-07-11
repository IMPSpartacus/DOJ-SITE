<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<title>Vos Droits - DOJ San Andreas</title>
<style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

  body {
    font-family: 'Inter', sans-serif;
    background: #e6f0ff;
    color: #1a2a4a;
    margin: 0;
    padding: 40px 20px;
    min-height: 100vh;
    display: flex;
    justify-content: center;
    align-items: flex-start;
  }

  .container {
    max-width: 900px;
    width: 100%;
    background: #ffffff;
    padding: 40px 40px 50px;
    border-radius: 20px;
    box-shadow: 0 12px 40px rgba(0, 55, 145, 0.15);
    box-sizing: border-box;
  }

  h1 {
    text-align: center;
    color: #00274d;
    margin-bottom: 40px;
    font-size: 2.8rem;
    font-weight: 700;
    letter-spacing: 1px;
    text-shadow: 0 2px 4px rgba(0,0,0,0.1);
  }

  .accordion {
    background-color: #007bff;
    color: white;
    cursor: pointer;
    padding: 18px 24px;
    width: 100%;
    text-align: left;
    border: none;
    outline: none;
    font-size: 1.3rem;
    font-weight: 600;
    border-radius: 12px;
    margin-bottom: 12px;
    box-shadow: 0 6px 15px rgba(0,123,255,0.3);
    display: flex;
    align-items: center;
    justify-content: space-between;
    transition: background-color 0.3s ease, box-shadow 0.3s ease;
    user-select: none;
  }

  .accordion:hover,
  .accordion.active {
    background-color: #0056b3;
    box-shadow: 0 8px 25px rgba(0,86,179,0.5);
  }

  .accordion::after {
    content: "\25BC"; /* Down arrow */
    font-size: 1rem;
    transition: transform 0.3s ease;
  }

  .accordion.active::after {
    transform: rotate(180deg);
  }

  .panel {
    background: #e9f0ff;
    border-radius: 0 0 16px 16px;
    margin-bottom: 20px;
    color: #00274d;
    font-size: 1.1rem;
    line-height: 1.75;
    padding: 0 24px;
    max-height: 0;
    overflow: hidden;
    transition: max-height 0.4s ease, padding 0.4s ease;
  }

  .panel.open {
    padding: 20px 24px;
    max-height: 1000px; /* suffisamment grand pour tout le contenu */
  }

  a.back {
    display: inline-block;
    margin-top: 30px;
    color: #007bff;
    text-decoration: none;
    font-weight: 600;
    font-size: 1.1rem;
    transition: color 0.3s ease;
  }

  a.back:hover {
    color: #0056b3;
    text-decoration: underline;
  }

  @media (max-width: 600px) {
    h1 {
      font-size: 2rem;
    }
    .accordion {
      font-size: 1.1rem;
      padding: 16px 20px;
    }
    .panel {
      font-size: 1rem;
    }
  }
</style>
</head>
<body>
  <div class="container">
    <h1>Vos Droits</h1>

    <button class="accordion">Droit à l'assistance d'un avocat</button>
    <div class="panel">
      <p>Tous les citoyens peuvent jouir du droit d'appeler un avocat pour le représenter lorsqu'il est arrêté, c'est le droit "Miranda", relatif au procès Miranda v. État d'Arizona.</p>
      <p>Lorsque vous êtes arrêtés, vous pouvez prévenir les agents que vous souhaitez faire appel à un avocat commis d'office ou contacter directement votre propre avocat via téléphone, c’est un droit !</p>
      <p><strong>Art. 6.1 - Code de Procédure Pénale</strong><br>
         Tout au long de la procédure, le prévenu en état d’arrestation doit pouvoir jouir de ses droits cités dans les droits “Miranda”. Tout manquement à cet article constitue un vice de procédure grave.</p>
    </div>

    <button class="accordion">Droit de contester une arrestation</button>
    <div class="panel">
      <p>Tous les citoyens, avec l'aide d'un avocat, peuvent contester une arrestation ou un casier judiciaire.</p>
      <p>En faisant appel à un avocat, vous pouvez faire rejuger votre arrestation devant le tribunal, demander des dommages et intérêts, une révision de la peine, ou la suppression de l'accusation de votre casier judiciaire.</p>
      <p><strong>Art. 43.1 - Code de Procédure Pénale</strong><br>
         Tout avocat peut faire appel d’une décision de police au nom de son client.</p>
    </div>

    <button class="accordion">Droit de blanchir son casier judiciaire</button>
    <div class="panel">
      <p>Vous pouvez demander au Department Of Justice de blanchir votre casier judiciaire si vous avez choisi de retourner dans la légalité.</p>
      <p>Faites appel à un avocat pour cela. Retrouvez toutes les conditions ici → <a href="#" target="_blank" rel="noopener noreferrer">Blanchir son casier</a></p>
    </div>

    <button class="accordion">Droit de réclamer des dommages et intérêts</button>
    <div class="panel">
      <p>Tous les citoyens peuvent faire appel à la justice via leur avocat pour réclamer des dommages et intérêts.</p>
      <p>Une entreprise vous a arnaqué ? Un citoyen vous a cassé votre voiture ? Vous pouvez assigner la partie adverse au procès civil pour demander des compensations, dommages et sanctions.</p>
    </div>

    <a href="dojcrad.html" class="back">&larr; Retour au portail DOJ</a>
  </div>

<script>
  const accordions = document.querySelectorAll(".accordion");
  accordions.forEach(acc => {
    acc.addEventListener("click", () => {
      const isActive = acc.classList.contains("active");
      // Fermer tous les panels
      accordions.forEach(a => {
        a.classList.remove("active");
        a.nextElementSibling.classList.remove("open");
      });
      // Ouvrir ou fermer le panel cliqué
      if (!isActive) {
        acc.classList.add("active");
        acc.nextElementSibling.classList.add("open");
      }
    });
  });
</script>

</body>
</html>
