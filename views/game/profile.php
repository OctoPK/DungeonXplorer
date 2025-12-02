<?php $titre = "Mon Héros"; ob_start(); ?>

<div class="container mt-5">
    <div class="row align-items-center">
        
        
        <div class="col-md-5 text-center">
            <div class="card panneau-sombre p-4 border-warning">
              
                <div class="mb-3 text-warning">
                    <?php if($hero['class_name'] == 'Guerrier'): ?>
                        <i class="fa-solid fa-shield-halved fa-6x"></i>
                    <?php elseif($hero['class_name'] == 'Voleur'): ?>
                        <i class="fa-solid fa-dagger fa-6x"></i>
                    <?php else: ?>
                        <i class="fa-solid fa-hat-wizard fa-6x"></i>
                    <?php endif; ?>
                </div>

                <h1 class="titre-principal"><?= $hero['name'] ?></h1>
                <span class="badge bg-warning text-dark fs-5"><?= $hero['class_name'] ?></span>
                
                <hr class="separateur-dore my-4">
                
                <p class="text-white-50">Niveau <?= $hero['current_level'] ?> | XP: <?= $hero['xp'] ?></p>

           >
                <a href="index.php?route=play" class="btn bouton-action-principal w-100 mt-3">
                    <i class="fa-solid fa-scroll me-2"></i> Lire le Chapitre 1
                </a>
            </div>
        </div>

      
        <div class="col-md-7">
            <div class="card panneau-sombre p-4">
                <h3 class="text-warning mb-4"><i class="fa-solid fa-chart-bar"></i> Caractéristiques</h3>
                
                <div class="row text-center">
              
                    <div class="col-4">
                        <div class="p-3 border border-secondary rounded bg-dark">
                            <i class="fa-solid fa-heart text-danger fa-2x mb-2"></i>
                            <div class="h3 mb-0 text-white"><?= $hero['pv'] ?></div>
                            <small class="text-muted">Santé</small>
                        </div>
                    </div>
                 
                    <div class="col-4">
                        <div class="p-3 border border-secondary rounded bg-dark">
                            <i class="fa-solid fa-bolt text-primary fa-2x mb-2"></i>
                            <div class="h3 mb-0 text-white"><?= $hero['mana'] ?></div>
                            <small class="text-muted">Mana</small>
                        </div>
                    </div>
              
                    <div class="col-4">
                        <div class="p-3 border border-secondary rounded bg-dark">
                            <i class="fa-solid fa-fist-raised text-success fa-2x mb-2"></i>
                            <div class="h3 mb-0 text-white"><?= $hero['strength'] ?></div>
                            <small class="text-muted">Force</small>
                        </div>
                    </div>
                </div>

                <div class="mt-4">
                    <h4 class="text-warning"><i class="fa-solid fa-suitcase"></i> Inventaire</h4>
                    <p class="text-muted fst-italic">Votre sac est vide...</p>
                </div>

            </div>
        </div>

    </div>
</div>

<?php $contenu = ob_get_clean(); require 'views/layout.php'; ?>