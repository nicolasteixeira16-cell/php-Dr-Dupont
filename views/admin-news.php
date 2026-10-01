<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6 min-w-[700px]">Gestion des actualites</h1>
    <a href="index.php?page=create-news" class="mb-6 inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Nouvelle actualite</a>

    <table class="w-full bg-white shadow rounded-lg">
      <thead class="bg-gray-100 text-left">
        <tr>
          <th class="p-4">Image</th>
          <th class="p-4">Titre</th>
          <th class="p-4">Date</th>
          <th class="p-4">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($actualites as $actualite) { ?>
          <tr class="border-t">
            <td class="p-4">
              <?php if ($actualite->getImage()) { ?>
                <img class="w-16 h-16 object-cover rounded" alt="<?php echo $actualite->getTitre(); ?>" src="uploads/<?php echo $actualite->getImage(); ?>">
              <?php } else { ?>
                <span class="text-gray-400 text-sm">Aucune</span>
              <?php } ?>
            </td>
            <td class="p-4"><?php echo $actualite->getTitre(); ?></td>
            <td class="p-4"><?php echo date("d/m/Y", strtotime($actualite->getDate())); ?></td>
            <td class="p-4 space-x-2">
              <a href="index.php?page=edit-news&id=<?php echo $actualite->getId(); ?>" class="text-gray-600 hover:underline">Modifier</a>
              <a href="index.php?page=delete-news&id=<?php echo $actualite->getId(); ?>" class="text-red-600 hover:underline">Supprimer</a>
            </td>
          </tr>
        <?php } ?>

        <?php if (count($actualites) === 0) { ?>
          <tr><td class="p-4 text-gray-500" colspan="4">Aucune actualite pour le moment.</td></tr>
        <?php } ?>
      </tbody>
    </table>
<?php include "footer.php"; ?>
