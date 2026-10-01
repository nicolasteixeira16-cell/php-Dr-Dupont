<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6 min-w-[700px]">Gestion des services</h1>
    <a href="index.php?page=create-service" class="mb-6 inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Nouveau service</a>
    <table class="w-full bg-white shadow rounded-lg">
      <thead class="bg-gray-100 text-left">
        <tr>
          <th class="p-4">Nom</th>
          <th class="p-4">Duree</th>
          <th class="p-4">Prix</th>
          <th class="p-4">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($services as $service) { ?>
          <tr class="border-t">
            <td class="p-4"><?php echo $service->getNom(); ?></td>
            <td class="p-4"><?php echo $service->getDuree(); ?> min</td>
            <td class="p-4"><?php echo $service->getPrix(); ?> &euro;</td>
            <td class="p-4 space-x-2">
              <a href="index.php?page=edit-service&id=<?php echo $service->getId(); ?>" class="text-gray-600 hover:underline">Modifier</a>
              <a href="index.php?page=delete-service&id=<?php echo $service->getId(); ?>" class="text-red-600 hover:underline">Supprimer</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
<?php include "footer.php"; ?>
