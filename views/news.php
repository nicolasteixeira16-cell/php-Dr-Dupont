<?php include "header.php"; ?>

    <h1 class="text-3xl font-bold mb-8">Actualites</h1>
    <div class="grid md:grid-cols-3 gap-8">
      <?php foreach ($actualites as $actualite) { ?>
        <article class="bg-white shadow rounded-xl p-6">
          <?php if ($actualite->getImage()) { ?>
            <img class="rounded-lg mb-4" alt="<?php echo $actualite->getTitre(); ?>" src="uploads/<?php echo $actualite->getImage(); ?>">
          <?php } ?>
          <h2 class="text-xl font-semibold mb-2"><?php echo $actualite->getTitre(); ?></h2>
          <p class="text-gray-600 mb-4"><?php echo $actualite->getContenu(); ?></p>
          <p class="text-sm text-gray-500"><?php echo date("d/m/Y", strtotime($actualite->getDate())); ?></p>
        </article>
      <?php } ?>

      <?php if (count($actualites) === 0) { ?>
        <p class="text-gray-500">Aucune actualite pour le moment.</p>
      <?php } ?>
    </div>

<?php include "footer.php"; ?>
