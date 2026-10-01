<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6">Prendre rendez-vous</h1>

    <?php if (isset($_GET["error"])) { ?>
    <div class="bg-red-100 border-red-400 text-red-700 p-4 py-3 rounded mb-4">
      <?php if ($_GET["error"] === "closed") { ?>
        <p>Le cabinet est ferme ce jour-la. Merci de choisir une autre date.</p>
      <?php } else if ($_GET["error"] === "hours") { ?>
        <p>Cet horaire est en dehors des heures d'ouverture (ou ne laisse pas assez de temps avant la fermeture). Merci de choisir un autre creneau.</p>
      <?php } else { ?>
        <p>Merci de remplir tous les champs.</p>
      <?php } ?>
    </div>
    <?php } ?>

    <?php // Rappel des horaires, pour que le patient choisisse directement
          // un creneau valide plutot que de decouvrir l'erreur apres coup. ?>
    <div class="bg-white shadow rounded-lg p-4 mb-6">
      <p class="font-semibold mb-2">Horaires d'ouverture</p>
      <ul class="text-sm text-gray-600 space-y-1">
        <?php foreach ($horaires as $horaire) { ?>
          <li>
            <?php echo $horaire->getLibelleJour(); ?> :
            <?php if ($horaire->estFerme()) { ?>
              Ferme
            <?php } else { ?>
              <?php echo substr($horaire->getHeureOuverture(), 0, 5); ?> - <?php echo substr($horaire->getHeureFermeture(), 0, 5); ?>
            <?php } ?>
          </li>
        <?php } ?>
      </ul>
    </div>

    <?php 
          // on l'invite a se connecter avant de continuer. ?>
    <?php if (!isset($_SESSION["patient_id"])) { ?>
      <div class="bg-yellow-100 border-yellow-400 text-yellow-800 p-4 py-3 rounded mb-4">
        <p>Vous devez etre connecte pour prendre rendez-vous.
           <a href="index.php?page=login" class="underline">Se connecter</a></p>
      </div>
    <?php } else { ?>

    <form class="bg-white p-6 rounded-lg shadow space-y-4" method="POST" action="index.php?page=appointment-valid">

      <?php // on utilise un <select>
            // puisqu'on ne peut choisir qu'UN SEUL service par rendez-vous
             ?>
      <?php // "required" declenche la bulle de validation native du
            // navigateur si le champ est vide : le formulaire ne part
            // meme pas vers appointment-valid tant qu'il n'est pas rempli.
            // "min" sur le champ date empeche de choisir un jour deja passe. ?>
      <select name="id_service" required class="w-full border rounded-lg p-3">
        <option value="">Choisir un type de consultation</option>
        <?php foreach ($services as $service) { ?>
          <option value="<?php echo $service->getId(); ?>"><?php echo $service->getNom(); ?> (<?php echo $service->getDuree(); ?> min)</option>
        <?php } ?>
      </select>

      <input type="date" name="date" required min="<?php echo date("Y-m-d"); ?>" class="w-full border rounded-lg p-3">
      <input type="time" name="heure" required class="w-full border rounded-lg p-3">

      <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Confirmer le rendez-vous</button>
    </form>

    <?php // Rappel : "required"/"min" ne sont qu'une AIDE cote client
          // (facile a contourner). La vraie protection reste le controle
          // fait cote serveur dans SiteController::appointmentValid()
          // (if (!$idService || !$date || !$heure) ...), qu'on garde
          // tel quel : jamais faire confiance uniquement au navigateur. ?>

    <?php } ?>
<?php include "footer.php"; ?>
