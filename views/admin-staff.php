<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6 min-w-[700px]">Personnel du cabinet</h1>
    <a href="index.php?page=create-staff" class="mb-6 inline-block bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Nouvel acces</a>

    <table class="w-full bg-white shadow rounded-lg">
      <thead class="bg-gray-100 text-left">
        <tr>
          <th class="p-4">Nom</th>
          <th class="p-4">Email</th>
          <th class="p-4">Role</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach ($utilisateurs as $utilisateur) { ?>
          <tr class="border-t">
            <td class="p-4"><?php echo $utilisateur->getNomComplet(); ?></td>
            <td class="p-4"><?php echo $utilisateur->getEmail(); ?></td>
            <td class="p-4"><?php echo $utilisateur->getRole() === "administrateur" ? "Administrateur" : "Assistant(e)"; ?></td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
<?php include "footer.php"; ?>
