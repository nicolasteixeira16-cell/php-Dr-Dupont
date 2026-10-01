<?php include "header.php"; ?>

    <section class="text-center mb-16">
      <?php // Titre et texte viennent de la base (table accueil) plutot que
            // d'etre ecrits en dur, exactement comme about.php. Le "?:"
            // sert de filet de securite si la table est encore vide au
            // tout premier chargement du site (avant meme la 1ere modif). ?>
      <h1 class="text-4xl font-bold mb-4"><?php echo $accueil ? $accueil->getTitre() : "Cabinet du Dr. Dupont"; ?></h1>
      <p class="text-gray-600 max-w-2xl mx-auto mb-6 whitespace-pre-line">
        <?php echo $accueil ? $accueil->getTexte() : "Chirurgien-dentiste a votre ecoute, notre cabinet vous accueille pour tous vos soins dentaires dans un environnement moderne et rassurant."; ?>
      </p>
      <?php // Meme "reflexe image nullable" que pour une actualite : tant
            // qu'aucune image n'a ete uploadee, on retombe sur l'image
            // de remplacement plutot que d'afficher une image cassee. ?>
      <?php if ($accueil && $accueil->getImage()) { ?>
        <img src="uploads/<?php echo $accueil->getImage(); ?>" alt="<?php echo $accueil->getTitre(); ?>" class="rounded-xl mx-auto mb-6 max-h-[350px] w-full object-cover max-w-3xl">
      <?php } else { ?>
        <img src="https://placehold.co/900x350?text=Cabinet+Dr.+Dupont" alt="Cabinet Dr. Dupont" class="rounded-xl mx-auto mb-6">
      <?php } ?>

      <?php // Meme bouton "Modifier cette page" que sur about.php, mais
            // reserve au Dr. Dupont (role administrateur) et non a tout
            // le personnel - c'est ce que le patient/visiteur ne voit
            // jamais, seul un compte administrateur connecte le voit. ?>
      <?php if (($_SESSION["staff_role"] ?? "") === "administrateur") { ?>
        <div class="mb-4">
          <a href="index.php?page=admin-home" class="text-sm bg-gray-700 text-white px-4 py-2 rounded-lg hover:bg-gray-800">
            Modifier cette section
          </a>
        </div>
      <?php } ?>

      <a href="index.php?page=appointment" class="bg-indigo-600 text-white px-8 py-3 rounded-lg text-lg hover:bg-indigo-700 inline-block">
        Prendre rendez-vous
      </a>
    </section>

    <section>
      <h2 class="text-2xl font-bold mb-6">Nos soins</h2>
      <div class="grid md:grid-cols-3 gap-8">
        <?php 
        ?>
        <?php foreach ($services as $service) { ?>
          <div class="bg-white shadow rounded-xl p-6">
            <h3 class="text-xl font-semibold mb-2"><?php echo $service->getNom(); ?></h3>
            <p class="text-gray-600 mb-4"><?php echo $service->getDescription(); ?></p>
            <p class="text-sm text-gray-500"><?php echo $service->getDuree(); ?> min - <?php echo $service->getPrix(); ?> &euro;</p>
          </div>
        <?php } ?>
      </div>
    </section>

<?php include "footer.php"; ?>
