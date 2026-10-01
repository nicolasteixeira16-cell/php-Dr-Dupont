<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6">Modifier la section d'accueil</h1>

    <?php if (isset($_GET["error"])) { ?>
    <div class="bg-red-100 border-red-400 text-red-700 p-4 py-3 rounded mb-4">
      <?php if ($_GET["error"] === "extension") { ?>
        <p>Format d'image non accepte (jpg, jpeg, png ou webp uniquement).</p>
      <?php } else if ($_GET["error"] === "size") { ?>
        <p>L'image est trop lourde (5 Mo maximum).</p>
      <?php } else { ?>
        <p>Merci de remplir le titre et le texte.</p>
      <?php } ?>
    </div>
    <?php } ?>

    <?php if ($accueil && $accueil->getImage()) { ?>
      <div class="mb-4">
        <p class="text-sm text-gray-500 mb-2">Image actuelle :</p>
        <img src="uploads/<?php echo $accueil->getImage(); ?>" class="w-64 rounded-lg">
      </div>
    <?php } ?>

    <form class="bg-white p-6 rounded-lg shadow space-y-4" method="POST" action="index.php?page=admin-home-valid" enctype="multipart/form-data">
      <input type="text" name="titre" value="<?php echo $accueil ? $accueil->getTitre() : ""; ?>" placeholder="Titre" class="w-full border rounded-lg p-3">
      <textarea name="texte" placeholder="Texte de presentation" class="w-full border rounded-lg p-3 h-32"><?php echo $accueil ? $accueil->getTexte() : ""; ?></textarea>
      <div>
        <label class="block text-sm text-gray-600 mb-1">Remplacer l'image (facultatif)</label>
        <input type="file" name="image" accept="image/*" class="w-full border rounded-lg p-3">
      </div>
      <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Enregistrer</button>
    </form>
<?php include "footer.php"; ?>
