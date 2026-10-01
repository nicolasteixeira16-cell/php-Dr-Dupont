<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6">Modifier la page "A propos"</h1>

    <?php if (isset($_GET["error"])) { ?>
    <div class="bg-red-100 border-red-400 text-red-700 p-4 py-3 rounded mb-4">
      <p>Merci de saisir un contenu.</p>
    </div>
    <?php } ?>

    <form class="bg-white p-6 rounded-lg shadow space-y-4" method="POST" action="index.php?page=admin-about-valid">
      <textarea name="contenu" placeholder="Contenu de la page A propos" class="w-full border rounded-lg p-3 h-64"><?php echo $aPropos ? $aPropos->getContenu() : ""; ?></textarea>
      <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Enregistrer</button>
    </form>
<?php include "footer.php"; ?>
