<?php include "header.php"; ?>

    <div class="flex justify-between items-center mb-8">
      <h1 class="text-3xl font-bold">A propos du Dr. Dupont</h1>
      <?php if (isset($_SESSION["staff_id"])) { ?>
        <a href="index.php?page=admin-about" class="text-sm bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
          Modifier cette page
        </a>
      <?php } ?>
    </div>

    <div class="bg-white shadow rounded-xl p-8">
      <?php if ($aPropos) { ?>
        <p class="text-gray-600 leading-relaxed whitespace-pre-line"><?php echo $aPropos->getContenu(); ?></p>
      <?php } else { ?>
        <p class="text-gray-500">Le contenu de cette page n'a pas encore ete renseigne.</p>
      <?php } ?>
    </div>

<?php include "footer.php"; ?>
