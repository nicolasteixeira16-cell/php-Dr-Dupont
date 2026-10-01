<?php include "header.php"; ?>

    <a href="index.php?page=news" class="text-indigo-600 hover:underline mb-6 inline-block">&larr; Retour aux actualites</a>

    <article class="bg-white shadow rounded-xl p-8 max-w-3xl mx-auto">
      <?php if ($actualite->getImage()) { ?>
        <img class="rounded-lg mb-6 w-full" alt="<?php echo $actualite->getTitre(); ?>" src="uploads/<?php echo $actualite->getImage(); ?>">
      <?php } ?>
      <h1 class="text-3xl font-bold mb-2"><?php echo $actualite->getTitre(); ?></h1>
      <p class="text-sm text-gray-500 mb-6"><?php echo date("d/m/Y", strtotime($actualite->getDate())); ?></p>
      <p class="text-gray-700 leading-relaxed whitespace-pre-line"><?php echo $actualite->getContenu(); ?></p>
    </article>

<?php include "footer.php"; ?>
