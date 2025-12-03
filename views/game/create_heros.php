<?php
$titre = "Créer mon Héros"; ob_start(); ?>

<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card panneau-sombre">
            <div class="card-header text-center">
                <h2 class="titre-principal text-warning">Création de Personnage</h2>
            </div>
            <div class="card-body">
                
               
            <form action="game/store" method="POST">
                    
                   
                    <div class="mb-4">
                        <label for="nom" class="form-label h4">Nom du Héros</label>
                        <input type="text" class="form-control bg-dark text-light border-secondary" id="nom" name="hero_name" required placeholder="Ex: Aragorn le Brave">
                    </div>

                    
                    <h4 class="mb-3">Choisissez votre Destinée :</h4>
                    
                    <div class="row g-3">
                        
                       
                        <?php foreach($classes as $classe): ?>
                            <div class="col-md-4">
                                <input type="radio" class="btn-check" name="class_id" id="class_<?= $classe['id'] ?>" value="<?= $classe['id'] ?>" required>
                                <label class="btn btn-outline-warning w-100 h-100 p-3 d-flex flex-column align-items-center justify-content-center" for="class_<?= $classe['id'] ?>">
                                    
                                  
                                    <?php if($classe['name'] == 'Guerrier'): ?>
                                        <i class="fa-solid fa-shield-halved fa-3x mb-2"></i>
                                    <?php elseif($classe['name'] == 'Voleur'): ?>
                                        <i class="fa-solid fa-dagger fa-3x mb-2"></i>
                                    <?php else: ?>
                                        <i class="fa-solid fa-hat-wizard fa-3x mb-2"></i>
                                    <?php endif; ?>

                                    <span class="h5 mt-2"><?= $classe['name'] ?></span>
                                    <small class="text-white-50 mt-2 text-center"><?= $classe['description'] ?></small>
                                    
                                    
                                    <div class="mt-3 badge bg-secondary">
                                        PV: <?= $classe['base_pv'] ?> | For: <?= $classe['strength'] ?>
                                    </div>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>

                    <div class="d-grid mt-5">
                        <button type="submit" class="btn bouton-action-principal">
                            Commencer l'Aventure !
                        </button>
                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

<?php $contenu = ob_get_clean(); require 'views/layout.php';


?>