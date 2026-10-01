<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6">Horaires d'ouverture</h1>

    <?php if (isset($_GET["success"])) { ?>
    <div class="bg-green-100 border-green-400 text-green-700 p-4 py-3 rounded mb-4">
      <p>Horaires mis a jour.</p>
    </div>
    <?php } ?>

    <form class="bg-white p-6 rounded-lg shadow" method="POST" action="index.php?page=admin-horaires-valid">
      <table class="w-full">
        <thead class="text-left">
          <tr>
            <th class="p-2">Jour</th>
            <th class="p-2">Ferme</th>
            <th class="p-2">Ouverture</th>
            <th class="p-2">Fermeture</th>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($horaires as $horaire) { ?>
            <?php // Le nom de chaque champ inclut le jour entre crochets
                  // (ex: heure_ouverture[lundi]) : cote PHP, $_POST["heure_ouverture"]
                  // arrivera alors comme un tableau associatif indexe par jour.
                   ?>
            <tr class="border-t">
              <td class="p-2 font-medium"><?php echo $horaire->getLibelleJour(); ?></td>
              <td class="p-2">
                <input type="checkbox" name="ferme[<?php echo $horaire->getJour(); ?>]" value="1" <?php echo $horaire->estFerme() ? "checked" : ""; ?>>
              </td>
              <td class="p-2">
                <input type="time" name="heure_ouverture[<?php echo $horaire->getJour(); ?>]" value="<?php echo $horaire->getHeureOuverture(); ?>" class="border rounded-lg p-2">
              </td>
              <td class="p-2">
                <input type="time" name="heure_fermeture[<?php echo $horaire->getJour(); ?>]" value="<?php echo $horaire->getHeureFermeture(); ?>" class="border rounded-lg p-2">
              </td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
      <button class="mt-6 bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Enregistrer</button>
    </form>
<?php include "footer.php"; ?>
