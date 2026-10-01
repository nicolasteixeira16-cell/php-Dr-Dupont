<?php include "header.php"; ?>
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Tableau de bord</h1>
      <a href="index.php?page=admin-logout" class="text-sm text-gray-500 hover:text-indigo-600">Deconnexion equipe</a>
    </div>

    <?php // Resume chiffre : les 3 valeurs viennent d'AdminController::admin(),
          // qui a simplement compte les elements des listes deja recuperees
          // par les Managers (count($manager->getXxx())). ?>
    <div class="grid sm:grid-cols-3 gap-6 mb-10">
      <div class="bg-white shadow rounded-xl p-6 text-center">
        <p class="text-4xl font-bold text-indigo-600"><?php echo $nombreRendezVous; ?></p>
        <p class="text-gray-500 mt-1">Rendez-vous</p>
      </div>
      <div class="bg-white shadow rounded-xl p-6 text-center">
        <p class="text-4xl font-bold text-indigo-600"><?php echo $nombrePatients; ?></p>
        <p class="text-gray-500 mt-1">Patients</p>
      </div>
      <div class="bg-white shadow rounded-xl p-6 text-center">
        <p class="text-4xl font-bold text-indigo-600"><?php echo $nombreServices; ?></p>
        <p class="text-gray-500 mt-1">Services</p>
      </div>
    </div>

    <div class="grid sm:grid-cols-2 gap-6">
      <a href="index.php?page=admin-appointments" class="px-10 py-6 text-2xl text-center font-semibold rounded-2xl bg-blue-600 text-white shadow-lg hover:bg-blue-700 transition">
        Rendez-vous
      </a>
      <a href="index.php?page=admin-patients" class="px-10 py-6 text-2xl text-center font-semibold rounded-2xl bg-blue-600 text-white shadow-lg hover:bg-blue-700 transition">
        Patients
      </a>
      <a href="index.php?page=admin-services" class="px-10 py-6 text-2xl text-center font-semibold rounded-2xl bg-blue-600 text-white shadow-lg hover:bg-blue-700 transition">
        Services
      </a>
      <a href="index.php?page=admin-news" class="px-10 py-6 text-2xl text-center font-semibold rounded-2xl bg-blue-600 text-white shadow-lg hover:bg-blue-700 transition">
        Actualites
      </a>
      <a href="index.php?page=admin-horaires" class="px-10 py-6 text-2xl text-center font-semibold rounded-2xl bg-blue-600 text-white shadow-lg hover:bg-blue-700 transition">
        Horaires
      </a>
      <a href="index.php?page=admin-about" class="px-10 py-6 text-2xl text-center font-semibold rounded-2xl bg-blue-600 text-white shadow-lg hover:bg-blue-700 transition">
        A propos
      </a>
      <?php // Ce bloc ne s'affiche que pour le Dr. Dupont (role "administrateur"). ?>
      <?php if (($_SESSION["staff_role"] ?? "") === "administrateur") { ?>
        <a href="index.php?page=admin-staff" class="px-10 py-6 text-2xl text-center font-semibold rounded-2xl bg-indigo-800 text-white shadow-lg hover:bg-indigo-900 transition">
          Personnel
        </a>
        <a href="index.php?page=admin-home" class="px-10 py-6 text-2xl text-center font-semibold rounded-2xl bg-indigo-800 text-white shadow-lg hover:bg-indigo-900 transition">
          Page d'accueil
        </a>
      <?php } ?>
    </div>
<?php include "footer.php"; ?>
