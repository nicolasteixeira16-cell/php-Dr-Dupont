<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6">Modifier le rendez-vous</h1>

    <?php if (isset($_GET["error"])) { ?>
    <div class="bg-red-100 border-red-400 text-red-700 p-4 py-3 rounded mb-4">
      <p>Merci de remplir tous les champs.</p>
    </div>
    <?php } ?>

    <form class="bg-white p-6 rounded-lg shadow space-y-4" method="POST" action="index.php?page=edit-appointment-valid">
      <input type="hidden" name="id" value="<?php echo $rendezVous->getId(); ?>">

      <select name="id_service" class="w-full border rounded-lg p-3">
        <?php foreach ($services as $service) { ?>
          <option value="<?php echo $service->getId(); ?>" <?php echo $service->getId() === $rendezVous->getIdService() ? "selected" : ""; ?>>
            <?php echo $service->getNom(); ?> (<?php echo $service->getDuree(); ?> min)
          </option>
        <?php } ?>
      </select>

      <input type="date" name="date" value="<?php echo $rendezVous->getDate(); ?>" class="w-full border rounded-lg p-3">
      <input type="time" name="heure" value="<?php echo $rendezVous->getHeure(); ?>" class="w-full border rounded-lg p-3">

      <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Enregistrer</button>
    </form>
<?php include "footer.php"; ?>
