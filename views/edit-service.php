<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6">Modifier le service</h1>

    <?php if (isset($_GET["error"])) { ?>
    <div class="bg-red-100 border-red-400 text-red-700 p-4 py-3 rounded mb-4">
      <p>Merci de saisir completement le formulaire.</p>
    </div>
    <?php } ?>

    <form class="bg-white p-6 rounded-lg shadow space-y-4" method="POST" action="index.php?page=edit-service-valid">
      <input type="hidden" name="id" value="<?php echo $service->getId(); ?>">
      <input type="text" name="nom" value="<?php echo $service->getNom(); ?>" placeholder="Nom du service" class="w-full border rounded-lg p-3">
      <textarea name="description" placeholder="Description" class="w-full border rounded-lg p-3 h-24"><?php echo $service->getDescription(); ?></textarea>
      <input type="number" name="duree" value="<?php echo $service->getDuree(); ?>" placeholder="Duree (en minutes)" class="w-full border rounded-lg p-3">
      <input type="number" step="0.01" name="prix" value="<?php echo $service->getPrix(); ?>" placeholder="Prix (en euros)" class="w-full border rounded-lg p-3">
      <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Enregistrer</button>
    </form>
<?php include "footer.php"; ?>
