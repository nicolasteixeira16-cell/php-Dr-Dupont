<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6">Modifier l'actualite</h1>

    <?php if (isset($_GET["error"])) { ?>
    <div class="bg-red-100 border-red-400 text-red-700 p-4 py-3 rounded mb-4">
      <?php if ($_GET["error"] === "extension") { ?>
        <p>Format d'image non accepte (jpg, jpeg, png ou webp uniquement).</p>
      <?php } else if ($_GET["error"] === "size") { ?>
        <p>L'image est trop lourde (5 Mo maximum).</p>
      <?php } else { ?>
        <p>Merci de saisir completement le formulaire (titre : 3 caracteres min, contenu : 10 caracteres min).</p>
      <?php } ?>
    </div>
    <?php } ?>

    <?php if ($actualite->getImage()) { ?>
      <div class="mb-4">
        <p class="text-sm text-gray-500 mb-2">Image actuelle :</p>
        <img src="uploads/<?php echo $actualite->getImage(); ?>" class="w-40 rounded-lg">
      </div>
    <?php } ?>

    <form class="bg-white p-6 rounded-lg shadow space-y-4" method="POST" action="index.php?page=edit-news-valid" enctype="multipart/form-data">
      <input type="hidden" name="id" value="<?php echo $actualite->getId(); ?>">
      <input type="text" name="titre" value="<?php echo $actualite->getTitre(); ?>" placeholder="Titre de l'actualite" class="w-full border rounded-lg p-3">
      <textarea name="contenu" placeholder="Contenu" class="w-full border rounded-lg p-3 h-32"><?php echo $actualite->getContenu(); ?></textarea>
      <div>
        <label class="block text-sm text-gray-600 mb-1">Remplacer l'image (facultatif)</label>
        <input type="file" name="image" accept="image/*" class="w-full border rounded-lg p-3">
      </div>
      <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Enregistrer</button>
    </form>
<?php include "footer.php"; ?>
