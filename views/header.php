<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Cabinet Dr. Dupont - Chirurgien-dentiste</title>
  <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-50 text-gray-800 flex flex-col min-h-screen">
  <header class="bg-white shadow-md">
    <div class="max-w-6xl mx-auto px-4 py-4 flex justify-between items-center">
      <a href="index.php" class="text-2xl font-bold text-indigo-600">Dr. Dupont</a>
      <nav class="space-x-6 flex items-center">
        <a href="index.php" class="text-gray-700 hover:text-indigo-600">Accueil</a>
        <a href="index.php?page=services" class="text-gray-700 hover:text-indigo-600">Services</a>
        <a href="index.php?page=about" class="text-gray-700 hover:text-indigo-600">A propos</a>
        <a href="index.php?page=news" class="text-gray-700 hover:text-indigo-600">Actualites</a>

        <?php // Bloc "patient" : deux etats possibles,
              // avec la cle de session "patient_id". ?>
        <?php if (isset($_SESSION["patient_id"])) { ?>
          <a href="index.php?page=my-appointments" class="text-gray-700 hover:text-indigo-600">Mes rendez-vous</a>
          <a href="index.php?page=appointment" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Prendre rendez-vous</a>
          <a href="index.php?page=logout" class="text-gray-700 hover:text-indigo-600">Deconnexion</a>
        <?php } else { ?>
          <a href="index.php?page=login" class="text-gray-700 hover:text-indigo-600">Connexion</a>
          <a href="index.php?page=register" class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700">Prendre rendez-vous</a>
        <?php } ?>

        <?php // Bloc "equipe du cabinet" : totalement independant du bloc
              // patient ci-dessus, avec sa propre cle "staff_id". ?>
        <?php if (isset($_SESSION["staff_id"])) { ?>
          <a href="index.php?page=admin" class="text-sm text-gray-400 hover:text-indigo-600">Equipe medicale</a>
        <?php } else { ?>
          <a href="index.php?page=admin-login" class="text-sm text-gray-400 hover:text-indigo-600">Espace pro</a>
        <?php } ?>
      </nav>
    </div>
  </header>
  <main class="max-w-6xl mx-auto px-4 py-10 grow">
