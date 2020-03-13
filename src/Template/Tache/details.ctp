<?php
 use Cake\Core\Configure;
 Configure::write('titre_header_tache', $titre);
?>

<div class="container d-flex flex-column justify-content-start" style="height: 80vh;margin-top: 20px;">
  <!-- Début description du projet -->
  <div class="row" style="margin-top: 50px;">
    <div class="col">
      <p><strong>Description du projet :</strong></p>
      <p class="text-center"><?= $desc ?></p>
    </div>
  </div>
  <!-- Fin description du projet -->

  <!-- Début liste des membres -->
  <div class="row" style="margin-top: 50px;">
    <div class="col">
      <p><strong>Membres du projet :</strong></p>
      <div class="card">
        <div class="card-body">
          <p>
            <?php
            foreach ($mbs as $ps) {
              echo $ps.'<br>';
            }
            ?>
          </p>
        </div>
      </div>
    </div>
  </div>
  <!-- Fin liste des membres -->

  <!-- Début Boutons 'Retour' & 'Projets' -->
  <div class="row d-flex align-items-start" style="margin-top:20px;" >
    <div class="col-xl-12">
      <!-- Bouton 'Retour' qui renvoie sur l'index d'un projet
      - nom : Retour
      - controller : Tache
      - action : index
      - $idProjet : id du projet à afficher
      -->
      <?= $this->Html->link("Retour", array('controller' => 'Tache', 'action'=> 'index', $idProjet), array( 'class' => 'btn btn-primary')); ?>
      <!-- Bouton 'Projets' qui renvoie sur la liste des projets
      - nom : Projets
      - controller : Projet
      - action : index
      -->
      <?= $this->Html->link("Projets", array('controller' => 'Projet', 'action'=> 'index'), array( 'class' => 'btn btn-primary')); ?>
    </div>
  </div>
  <!-- Fin Boutons 'Retour' & 'Projets' -->
</div>
