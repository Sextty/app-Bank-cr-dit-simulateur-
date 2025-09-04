<?php
$conn = new mysqli("localhost", "root", "", "bts_bank");
if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// Traitement de la suppression
if (isset($_GET['delete_id'])) {
    $delete_id = intval($_GET['delete_id']);
    $delete_stmt = $conn->prepare("DELETE FROM archives WHERE id = ?");
    $delete_stmt->bind_param("i", $delete_id);
    if ($delete_stmt->execute()) {
        $success_message = "Enregistrement supprimé avec succès!";
    } else {
        $error_message = "Erreur lors de la suppression: " . $conn->error;
    }
    $delete_stmt->close();
    
    // Redirection pour éviter la resoumission du formulaire
    header("Location: archives.php?message=" . urlencode($success_message ?? $error_message));
    exit();
}

// Afficher les messages de succès/erreur
if (isset($_GET['message'])) {
    $message = urldecode($_GET['message']);
    if (strpos($message, 'Erreur') !== false) {
        echo "<div style='color: red; padding: 10px; margin-bottom: 15px; border: 1px solid red; background: #ffeeee;'>$message</div>";
    } else {
        echo "<div style='color: green; padding: 10px; margin-bottom: 15px; border: 1px solid green; background: #eeffee;'>$message</div>";
    }
}

$result = $conn->query("SELECT * FROM archives ORDER BY date_simulation DESC");
?>
<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Archives BTS Bank</title>
  <style>
    :root{--bg:#f6f8fb;--card:#fff;--accent:#0f62fe;--muted:#6b7280}
    *{box-sizing:border-box}
    body{font-family:Inter, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, "Helvetica Neue", Arial; margin:0; padding:20px; background:var(--bg); color:#111}
    .wrap{max-width:1100px;margin:0 auto}
    header{display:flex;align-items:center;gap:16px;margin-bottom:18px}
    h1{font-size:20px;margin:0}
    .card{background:var(--card);border-radius:12px;padding:16px;box-shadow:0 6px 20px rgba(15,23,42,0.06);}
    form .row{display:flex;gap:12px;margin-bottom:8px}
    label{font-size:13px;color:var(--muted)}
    input,select{padding:8px;border-radius:8px;border:1px solid #e6e9ef;font-size:14px;width:100%}
    button{background:var(--accent);color:white;border:0;padding:10px 14px;border-radius:8px;font-weight:600;cursor:pointer}
    button.ghost{background:#eef2ff;color:var(--accent);border:1px solid rgba(15,98,254,0.12)}
    .grid{display:grid;grid-template-columns:1fr;gap:16px;margin-top:16px}
    table{width:100%;border-collapse:collapse;font-size:13px}
    th,td{padding:8px;border-bottom:1px solid #f1f5f9;text-align:right}
    th{background:#fbfdff;text-align:right;font-weight:700}
    td:first-child,th:first-child{text-align:left}
    .small{font-size:12px;color:var(--muted)}
    .muted{color:var(--muted)}
    .toast{position:fixed;right:20px;bottom:20px;background:#111;color:white;padding:10px 14px;border-radius:10px;opacity:0.95}
  
    /* ==== BTS Bank brand upgrade ==== */
    :root{
      --bts-primary:#0f62fe;        /* deep blue */
      --bts-primary-2:#14b8a6;      /* teal accent */
      --bts-text:#0b1220;
      --bts-subtle:#64748b;
    }
    body{background:var(--bg); color:var(--bts-text)}
    .brandbar{
      background:linear-gradient(90deg,var(--bts-primary),var(--bts-primary-2));
      border-radius:16px;
      padding:14px 16px;
      color:white;
      box-shadow:0 10px 24px rgba(2,6,23,0.18);
      display:flex; align-items:center; gap:12px;
      margin-bottom:16px;
    }
    .brand-logo-img {
      width: 48px;
      height: 48px;
      object-fit: contain;
      background-color: white;
      padding: 4px;
      border-radius: 8px;
    }
    .brand-name{ font-size:18px; font-weight:700; letter-spacing:0.3px }
    .brand-sub{ font-size:12px; opacity:0.9 }
    header h1{ font-size:20px; margin:0 }
    .header-wrap{ display:flex; flex-direction:column; gap:10px }
    @media (min-width:700px){
      .header-wrap{ flex-direction:row; align-items:center; justify-content:space-between }
    }
    .tag{
      display:inline-flex; align-items:center; gap:8px;
      padding:6px 10px; border-radius:999px; font-size:12px;
      background:#eef2ff; color:var(--bts-primary); border:1px solid rgba(15,98,254,.2);
    }
    .watermark{
      margin-top:16px; font-size:12px; color:var(--bts-subtle);
    }
    /* table polish */
    table{border-radius:12px; overflow:hidden}
    thead th{ position:sticky; top:0; z-index:1 }
    tr:hover td{ background:#fafcff }
    /* print tweaks */
    @media print{
      .brandbar{ border-radius:0; margin-bottom:8px }
      .card{ box-shadow:none }
      button, .toast{ display:none !important }
    }

    /* Styles spécifiques aux archives */
    .details-btn {
      background: #0f62fe;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
      margin: 2px;
    }
    .edit-btn {
      background: #28a745;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
      margin: 2px;
    }
    .delete-btn {
      background: #dc3545;
      color: white;
      border: none;
      padding: 6px 12px;
      border-radius: 4px;
      cursor: pointer;
      margin: 2px;
    }
    .btn-group {
      display: flex;
      justify-content: center;
    }
    .modal {
      display: none;
      position: fixed;
      z-index: 100;
      left: 0;
      top: 0;
      width: 100%;
      height: 100%;
      overflow: auto;
      background-color: rgba(0,0,0,0.4);
    }
    .modal-content {
      background-color: #fefefe;
      margin: 5% auto;
      padding: 20px;
      border: 1px solid #888;
      width: 80%;
      max-width: 900px;
      border-radius: 8px;
    }
    .close {
      color: #aaa;
      float: right;
      font-size: 28px;
      font-weight: bold;
      cursor: pointer;
    }
    .close:hover {
      color: black;
    }
    .edit-form {
      display: grid;
      grid-template-columns: 1fr 1fr;
      gap: 15px;
      margin-top: 20px;
    }
    .edit-form div {
      display: flex;
      flex-direction: column;
    }
    .edit-form label {
      font-weight: bold;
      margin-bottom: 5px;
    }
    .edit-form input {
      padding: 8px;
      border: 1px solid #ddd;
      border-radius: 4px;
    }
    .form-actions {
      grid-column: span 2;
      display: flex;
      justify-content: flex-end;
      gap: 10px;
      margin-top: 15px;
    }
    .delete-modal {
      text-align: center;
      padding: 20px;
    }
    .delete-modal p {
      font-size: 18px;
      margin-bottom: 20px;
    }
    .delete-actions {
      display: flex;
      justify-content: center;
      gap: 15px;
    }
  </style>
</head>
<body>
  <div class="wrap">
    <header class="header-wrap">
      <div class="brandbar">
        <img src="logo_bts_officiel.png" alt="BTS Bank" class="brand-logo-img" />
        <div>
          <div class="brand-name">BTS Bank — Banque Tunisienne de Solidarité</div>
          <div class="brand-sub">Tunisie • Archives des simulations</div>
        </div>
      </div>
      <div>
        <h1>Archives des simulations</h1>
        <a href="indx.html" class="ghost" style="display:inline-flex;align-items:center;text-decoration:none;padding:10px 14px;border-radius:8px;background:#eef2ff;color:#0f62fe;font-weight:600;cursor:pointer">Retour au simulateur</a>
      </div>
    </header>

    <div class="card">
      <table>
        <tr>
          <th>Actions</th>
          <th>ID</th>
          <th>Nom</th>
          <th>Prénom</th>
          <th>CIN</th>
          <th>Montant</th>
          <th>Taux (%)</th>
          <th>Durée (mois)</th>
          <th>Mensualité</th>
          <th>Date</th>
        </tr>
        <?php if($result && $result->num_rows > 0): ?>
          <?php while($row = $result->fetch_assoc()): ?>
            <tr>
              <td>
                <div class="btn-group">
                  <button class="details-btn" onclick="showDetails(<?= $row['id'] ?>, <?= $row['montant'] ?>, <?= $row['taux'] ?>, <?= $row['duree'] ?>, <?= $row['mensualite'] ?>)">
                    Voir
                  </button>
                  <button class="edit-btn" onclick="showEditForm(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nom']) ?>', '<?= htmlspecialchars($row['prenom']) ?>', '<?= htmlspecialchars($row['cin']) ?>', <?= $row['montant'] ?>, <?= $row['taux'] ?>, <?= $row['duree'] ?>, <?= $row['mensualite'] ?>)">
                    Modifier
                  </button>
                  <button class="delete-btn" onclick="confirmDelete(<?= $row['id'] ?>, '<?= htmlspecialchars($row['nom']) ?>', '<?= htmlspecialchars($row['prenom']) ?>')">
                    Supprimer
                  </button>
                </div>
              </td>
              <td><?= htmlspecialchars($row['id']) ?></td>
              <td><?= htmlspecialchars($row['nom']) ?></td>
              <td><?= htmlspecialchars($row['prenom']) ?></td>
              <td><?= htmlspecialchars($row['cin']) ?></td>
              <td><?= htmlspecialchars($row['montant']) ?> TND</td>
              <td><?= htmlspecialchars($row['taux']) ?></td>
              <td><?= htmlspecialchars($row['duree']) ?></td>
              <td><?= htmlspecialchars($row['mensualite']) ?> TND</td>
              <td><?= htmlspecialchars($row['date_simulation']) ?></td>
            </tr>
          <?php endwhile; ?>
        <?php else: ?>
          <tr><td colspan="10">Aucune donnée</td></tr>
        <?php endif; ?>
      </table>
    </div>

    <div class="watermark"> 2025 — BTS Bank Archives </div>
  </div>

  <!-- Modal pour afficher les détails -->
  <div id="detailsModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('detailsModal')">&times;</span>
      <h2>Détails du crédit #<span id="creditId"></span></h2>
      <div id="creditDetails"></div>
    </div>
  </div>

  <!-- Modal pour modifier un enregistrement -->
  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close" onclick="closeModal('editModal')">&times;</span>
      <h2>Modifier le crédit #<span id="editId"></span></h2>
      <form id="editForm" method="POST" action="update_credit.php">
        <input type="hidden" name="id" id="editIdInput">
        <div class="edit-form">
          <div>
            <label for="editNom">Nom</label>
            <input type="text" id="editNom" name="nom" required>
          </div>
          <div>
            <label for="editPrenom">Prénom</label>
            <input type="text" id="editPrenom" name="prenom" required>
          </div>
          <div>
            <label for="editCin">CIN</label>
            <input type="text" id="editCin" name="cin" required>
          </div>
          <div>
            <label for="editMontant">Montant (TND)</label>
            <input type="number" id="editMontant" name="montant" step="0.01" required>
          </div>
          <div>
            <label for="editTaux">Taux (%)</label>
            <input type="number" id="editTaux" name="taux" step="0.01" required>
          </div>
          <div>
            <label for="editDuree">Durée (mois)</label>
            <input type="number" id="editDuree" name="duree" required>
          </div>
          <div>
            <label for="editMensualite">Mensualité (TND)</label>
            <input type="number" id="editMensualite" name="mensualite" step="0.01" required>
          </div>
          <div class="form-actions">
            <button type="button" onclick="closeModal('editModal')">Annuler</button>
            <button type="submit">Enregistrer</button>
          </div>
        </div>
      </form>
    </div>
  </div>

  <!-- Modal pour confirmer la suppression -->
  <div id="deleteModal" class="modal">
    <div class="modal-content delete-modal">
      <h2>Confirmer la suppression</h2>
      <p>Êtes-vous sûr de vouloir supprimer le crédit de <span id="deleteName"></span> ?</p>
      <div class="delete-actions">
        <button onclick="closeModal('deleteModal')">Annuler</button>
        <button id="confirmDeleteBtn" style="background: #dc3545; color: white;">Supprimer</button>
      </div>
    </div>
  </div>

  <script>
    // Fonction pour formater les nombres
    const fmt = (v) => Number(v).toLocaleString(undefined,{minimumFractionDigits:2,maximumFractionDigits:2});
    
    // Fonction pour calculer le tableau d'amortissement
    function computeSchedule(amount, annualRate, duree, mensualite) {
      const n = duree;
      const r = annualRate / 100 / 12; // Taux mensuel proportionnel
      
      const rows = [];
      let balance = Number(amount);
      let totalInterest = 0;

      rows.push({
        period: 0,
        balance: balance,
        payment: 0,
        interest: 0,
        principal: 0
      });

      for(let i = 1; i <= n && balance > 0.005; i++) {
        const interest = Number((balance * r).toFixed(10));
        let principal = Number((mensualite - interest).toFixed(10));
        if(principal > balance) { 
          principal = balance; 
        }
        const actualPayment = Number((principal + interest).toFixed(2));
        balance = Number((balance - principal).toFixed(10));
        totalInterest += interest;

        rows.push({
          period: i,
          balance: Math.max(0, Number(balance.toFixed(2))),
          payment: actualPayment,
          interest: Number(interest.toFixed(2)),
          principal: Number(principal.toFixed(2))
        });
      }

      return {rows, totalInterest: Number(totalInterest.toFixed(2))};
    }

    // Fonction pour afficher les détails
    function showDetails(id, amount, taux, duree, mensualite) {
      const result = computeSchedule(amount, taux, duree, mensualite);
      
      document.getElementById('creditId').textContent = id;
      
      let html = `
        <div style="margin-bottom: 20px;">
          <p><strong>Montant:</strong> ${fmt(amount)} TND</p>
          <p><strong>Taux annuel:</strong> ${taux}%</p>
          <p><strong>Durée:</strong> ${duree} mois</p>
          <p><strong>Mensualité:</strong> ${fmt(mensualite)} TND</p>
          <p><strong>Coût total des intérêts:</strong> ${fmt(result.totalInterest)} TND</p>
        </div>
        <div style="overflow: auto; max-height: 400px;">
          <table style="width: 100%;">
            <thead>
              <tr>
                <th>Mois</th>
                <th>Capital restant</th>
                <th>Mensualité</th>
                <th>Intérêt</th>
                <th>Amortissement</th>
              </tr>
            </thead>
            <tbody>
      `;
      
      result.rows.forEach(row => {
        html += `
          <tr>
            <td>${row.period}</td>
            <td>${fmt(row.balance)} TND</td>
            <td>${fmt(row.payment)} TND</td>
            <td>${fmt(row.interest)} TND</td>
            <td>${fmt(row.principal)} TND</td>
          </tr>
        `;
      });
      
      html += `
            </tbody>
          </table>
        </div>
      `;
      
      document.getElementById('creditDetails').innerHTML = html;
      document.getElementById('detailsModal').style.display = 'block';
    }

    // Fonction pour afficher le formulaire de modification
    function showEditForm(id, nom, prenom, cin, montant, taux, duree, mensualite) {
      document.getElementById('editId').textContent = id;
      document.getElementById('editIdInput').value = id;
      document.getElementById('editNom').value = nom;
      document.getElementById('editPrenom').value = prenom;
      document.getElementById('editCin').value = cin;
      document.getElementById('editMontant').value = montant;
      document.getElementById('editTaux').value = taux;
      document.getElementById('editDuree').value = duree;
      document.getElementById('editMensualite').value = mensualite;
      
      document.getElementById('editModal').style.display = 'block';
    }

    // Fonction pour confirmer la suppression
    function confirmDelete(id, nom, prenom) {
      document.getElementById('deleteName').textContent = nom + ' ' + prenom;
      
      const confirmBtn = document.getElementById('confirmDeleteBtn');
      // Supprimer l'ancien événement pour éviter les duplications
      confirmBtn.replaceWith(confirmBtn.cloneNode(true));
      
      document.getElementById('confirmDeleteBtn').onclick = function() {
        window.location.href = 'archives.php?delete_id=' + id;
      };
      
      document.getElementById('deleteModal').style.display = 'block';
    }

    // Fonction pour fermer les modals
    function closeModal(modalId) {
      document.getElementById(modalId).style.display = 'none';
    }

    // Fermer la modal en cliquant à l'extérieur
    window.addEventListener('click', function(event) {
      const modals = ['detailsModal', 'editModal', 'deleteModal'];
      modals.forEach(modalId => {
        const modal = document.getElementById(modalId);
        if (event.target == modal) {
          modal.style.display = 'none';
        }
      });
    });
  </script>
</body>
</html>