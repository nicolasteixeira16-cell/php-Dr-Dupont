<?php include "header.php"; ?>
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md mx-auto my-12">
      <h1 class="text-2xl font-bold mb-6">Creer un compte patient</h1>

      <?php if (isset($_GET["error"]) && $_GET["error"] === "email-format") { ?>
        <div class="bg-red-100 border-red-400 text-red-700 p-4 py-3 rounded mb-4">Format d'email invalide.</div>
      <?php } ?>
      <?php if (isset($_GET["error"]) && $_GET["error"] === "email-used") { ?>
        <div class="bg-red-100 border-red-400 text-red-700 p-4 py-3 rounded mb-4">Email deja utilise.</div>
      <?php } ?>
      <?php if (isset($_GET["error"]) && $_GET["error"] === "fields") { ?>
        <div class="bg-red-100 border-red-400 text-red-700 p-4 py-3 rounded mb-4">Merci de remplir tous les champs.</div>
      <?php } ?>

      <form class="space-y-4" action="index.php?page=register-valid" method="POST">
        <input type="text" name="nom" placeholder="Nom" class="w-full border rounded-lg p-3">
        <input type="text" name="prenom" placeholder="Prenom" class="w-full border rounded-lg p-3">
        <input type="email" name="email" placeholder="Email" class="w-full border rounded-lg p-3">
        <input type="tel" name="telephone" placeholder="Telephone" class="w-full border rounded-lg p-3">
        <input type="password" name="password" placeholder="Mot de passe" class="w-full border rounded-lg p-3">
        <button class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">S'inscrire</button>
      </form>
    </div>
<?php include "footer.php"; ?>
