<?php include "header.php"; ?>
    <h1 class="text-3xl font-bold mb-6">Nouvel acces back-office</h1>

    <?php if (isset($_GET["error"])) { ?>
    <div class="bg-red-100 border-red-400 text-red-700 p-4 py-3 rounded mb-4">
      <?php if ($_GET["error"] === "email-format") { ?>
        <p>L'adresse email n'est pas valide.</p>
      <?php } else if ($_GET["error"] === "email-used") { ?>
        <p>Un compte existe deja avec cet email.</p>
      <?php } else { ?>
        <p>Merci de saisir completement le formulaire.</p>
      <?php } ?>
    </div>
    <?php } ?>

    <form class="bg-white p-6 rounded-lg shadow space-y-4" method="POST" action="index.php?page=create-staff-valid">
      <input type="text" name="nom" placeholder="Nom" class="w-full border rounded-lg p-3">
      <input type="text" name="prenom" placeholder="Prenom" class="w-full border rounded-lg p-3">
      <input type="email" name="email" placeholder="Email" class="w-full border rounded-lg p-3">
      <input type="password" name="password" placeholder="Mot de passe" class="w-full border rounded-lg p-3">
      <select name="role" class="w-full border rounded-lg p-3">
        <option value="assistant">Assistant(e)</option>
        <option value="administrateur">Administrateur</option>
      </select>
      <button class="bg-indigo-600 text-white px-6 py-2 rounded-lg hover:bg-indigo-700">Creer l'acces</button>
    </form>
<?php include "footer.php"; ?>
