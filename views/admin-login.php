<?php include "header.php"; ?>
    <div class="bg-white p-8 rounded-xl shadow-md w-full max-w-md mx-auto my-12">
      <h1 class="text-2xl font-bold mb-6">Espace equipe</h1>

      <?php if (isset($_GET["error"]) && $_GET["error"] === "credentials") { ?>
        <div class="bg-red-100 border-red-400 text-red-700 p-4 py-3 rounded mb-4">Identifiants invalides.</div>
      <?php } ?>
      <?php if (isset($_GET["error"]) && $_GET["error"] === "fields") { ?>
        <div class="bg-red-100 border-red-400 text-red-700 p-4 py-3 rounded mb-4">Merci de remplir tous les champs.</div>
      <?php } ?>

      <form class="space-y-4" action="index.php?page=admin-login-valid" method="POST">
        <input type="email" name="email" placeholder="Email professionnel" class="w-full border rounded-lg p-3">
        <input type="password" name="password" placeholder="Mot de passe" class="w-full border rounded-lg p-3">
        <button class="w-full bg-indigo-600 text-white px-6 py-3 rounded-lg hover:bg-indigo-700">Se connecter</button>
      </form>
    </div>
<?php include "footer.php"; ?>
