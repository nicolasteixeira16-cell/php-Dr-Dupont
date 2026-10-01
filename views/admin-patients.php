<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6 min-w-[700px]">Patients</h1>
    <a href="index.php?page=create-patient" class="mb-6 inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Nouveau patient</a>

    <table class="w-full bg-white shadow rounded-lg">
      <thead class="bg-gray-100 text-left">
        <tr>
          <th class="p-4">Nom</th>
          <th class="p-4">Email</th>
          <th class="p-4">Telephone</th>
          <th class="p-4">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($patients as $patient) { ?>
          <tr class="border-t">
            <td class="p-4"><?php echo $patient->getNomComplet(); ?></td>
            <td class="p-4"><?php echo $patient->getEmail(); ?></td>
            <td class="p-4"><?php echo $patient->getTelephone(); ?></td>
            <td class="p-4 space-x-2">
              <a href="index.php?page=edit-patient&id=<?php echo $patient->getId(); ?>" class="text-gray-600 hover:underline">Modifier</a>
              <a href="index.php?page=delete-patient&id=<?php echo $patient->getId(); ?>" class="text-red-600 hover:underline">Supprimer</a>
            </td>
          </tr>
        <?php } ?>

        <?php if (count($patients) === 0) { ?>
          <tr><td class="p-4 text-gray-500" colspan="4">Aucun patient enregistre pour le moment.</td></tr>
        <?php } ?>
      </tbody>
    </table>
<?php include "footer.php"; ?>
