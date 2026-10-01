<?php include "header.php"; ?>

    <h1 class="text-3xl font-bold mb-8">Nos services</h1>
    <div class="grid md:grid-cols-2 gap-8">
        <?php foreach ($services as $service) { ?>
          <div class="bg-white shadow rounded-xl p-6">
            <h2 class="text-xl font-semibold mb-2"><?php echo $service->getNom(); ?></h2>
            <p class="text-gray-600 mb-4"><?php echo $service->getDescription(); ?></p>
            <div class="flex justify-between text-sm text-gray-500">
              <span>Duree : <?php echo $service->getDuree(); ?> min</span>
              <span><?php echo $service->getPrix(); ?> &euro;</span>
            </div>
          </div>
        <?php } ?>
    </div>

<?php include "footer.php"; ?>
