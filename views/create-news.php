<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6">Nouvelle actualite</h1>

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

    <?php // enctype="multipart/form-data" est OBLIGATOIRE des qu'un formulaire
          // contient un champ type="file" - Sans lui, $_FILES reste vide. ?>
    <form class="bg-white p-6 rounded-lg shadow space-y-4" method="POST" action="index.php?page=create-news-valid" enctype="multipart/form-data">
      <input type="text" name="titre" placeholder="Titre de l'actualite" class="w-full border rounded-lg p-3">
      <textarea name="contenu" placeholder="Contenu" class="w-full border rounded-lg p-3 h-32"></textarea>
      <div>
        <label class="block text-sm text-gray-600 mb-1">Image (facultative)</label>
        <input type="file" name="image" accept="image/*" class="w-full border rounded-lg p-3">
      </div>
      <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Publier</button>
    </form>
<?php include "footer.php"; ?>
