<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6">Modifier le patient</h1>

    <?php if (isset($_GET["error"])) { ?>
    <div class="bg-red-100 border-red-400 text-red-700 p-4 py-3 rounded mb-4">
      <?php if ($_GET["error"] === "email-format") { ?>
        <p>L'adresse email n'est pas valide.</p>
      <?php } else { ?>
        <p>Merci de saisir completement le formulaire.</p>
      <?php } ?>
    </div>
    <?php } ?>

    <form class="bg-white p-6 rounded-lg shadow space-y-4" method="POST" action="index.php?page=edit-patient-valid">
      <input type="hidden" name="id" value="<?php echo $patient->getId(); ?>">
      <input type="text" name="nom" value="<?php echo $patient->getNom(); ?>" placeholder="Nom" class="w-full border rounded-lg p-3">
      <input type="text" name="prenom" value="<?php echo $patient->getPrenom(); ?>" placeholder="Prenom" class="w-full border rounded-lg p-3">
      <input type="email" name="email" value="<?php echo $patient->getEmail(); ?>" placeholder="Email" class="w-full border rounded-lg p-3">
      <input type="tel" name="telephone" value="<?php echo $patient->getTelephone(); ?>" placeholder="Telephone" class="w-full border rounded-lg p-3">
      <p class="text-sm text-gray-400">Le mot de passe n'est pas modifiable depuis cette page : seul le patient peut le changer.</p>
      <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Enregistrer</button>
    </form>
<?php include "footer.php"; ?>
