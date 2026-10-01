<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6">Mes rendez-vous</h1>

    <?php if (empty($rendezVous)) { ?>
      <p class="text-gray-600">Vous n'avez aucun rendez-vous pour le moment.</p>
      <a href="index.php?page=appointment" class="inline-block mt-4 bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">
        Prendre rendez-vous
      </a>
    <?php } else { ?>
      <table class="w-full bg-white shadow rounded-lg">
        <thead class="bg-gray-100 text-left">
          <tr>
            <th class="p-4">Service</th>
            <th class="p-4">Date</th>
            <th class="p-4">Heure</th>
            <th class="p-4">Statut</th>
          </tr>
        </thead>
        <tbody>
          <?php
          // Meme dictionnaire de correspondance que dans admin-appointments.php :
          // il traduit le code brut stocke en base (en_attente / confirme / annule)
          // en un libelle propre a afficher (majuscule + accent).
          $libellesStatuts = [
              "en_attente" => "En attente",
              "confirme" => "Validé",
              "annule" => "Annulé",
          ];
          ?>
          <?php foreach ($rendezVous as $rdv) { ?>
            <tr class="border-t">
              <td class="p-4"><?php echo $rdv->getServiceNom(); ?></td>
              <td class="p-4"><?php echo date("d/m/Y", strtotime($rdv->getDate())); ?></td>
              <td class="p-4"><?php echo $rdv->getHeure(); ?></td>
              <td class="p-4"><?php echo $libellesStatuts[$rdv->getStatut()] ?? $rdv->getStatut(); ?></td>
            </tr>
          <?php } ?>
        </tbody>
      </table>
    <?php } ?>
<?php include "footer.php"; ?>
