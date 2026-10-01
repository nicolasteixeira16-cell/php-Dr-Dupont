<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6 min-w-[700px]">Gestion des rendez-vous</h1>
    <table class="w-full bg-white shadow rounded-lg">
      <thead class="bg-gray-100 text-left">
        <tr>
          <th class="p-4">Patient</th>
          <th class="p-4">Service</th>
          <th class="p-4">Date</th>
          <th class="p-4">Heure</th>
          <th class="p-4">Statut</th>
          <th class="p-4">Actions</th>
        </tr>
      </thead>
      <tbody>
        <?php
        // Le champ "statut" en base ne contient que des codes bruts
        // ("en_attente", "confirme", "annule"), sans majuscule ni accent :
        // ce sont des valeurs faites pour le code, pas pour l'affichage.
        // On les traduit ici en libelles lisibles, exactement comme on
        // traduirait un code HTTP (200) en message ("OK") avant affichage.
        $libellesStatuts = [
            "en_attente" => "En attente",
            "confirme" => "Validé",
            "annule" => "Annulé",
        ];
        ?>
        <?php foreach ($rendezVous as $rdv) { ?>
          <tr class="border-t">
            <td class="p-4"><?php echo $rdv->getPatientNom(); ?></td>
            <td class="p-4"><?php echo $rdv->getServiceNom(); ?></td>
            <td class="p-4"><?php echo date("d/m/Y", strtotime($rdv->getDate())); ?></td>
            <td class="p-4"><?php echo $rdv->getHeure(); ?></td>
            <td class="p-4"><?php echo $libellesStatuts[$rdv->getStatut()] ?? $rdv->getStatut(); ?></td>
            <td class="p-4 space-x-2">
              <a href="index.php?page=edit-appointment&id=<?php echo $rdv->getId(); ?>" class="text-gray-600 hover:underline">Modifier</a>
              <a href="index.php?page=confirm-appointment&id=<?php echo $rdv->getId(); ?>" class="text-indigo-600 hover:underline">Confirmer</a>
              <a href="index.php?page=cancel-appointment&id=<?php echo $rdv->getId(); ?>" class="text-red-600 hover:underline">Annuler</a>
            </td>
          </tr>
        <?php } ?>
      </tbody>
    </table>
<?php include "footer.php"; ?>
