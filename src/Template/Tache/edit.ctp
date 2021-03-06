<!-- Début modification header -->
<?php
  use Cake\Core\Configure;
  Configure::write('titre_header_tache', $titreProjet);
?>
<!-- Fin modification header -->

<!-- Modifier une tâche -->
<!-- Auteur : Valérie MARISSENS -->

<div class="container" style="margin-top: 50px;">
        <!-- Début titre -->
        <div class="row">
            <div class="col"><h1><center>Modifier la tâche</center></h1></div>
        </div>
        <!-- Fin titre -->

        <!-- Début formulaire de modification -->
        <?= $this->Form->create('Tache'); ?>
        <div class="row" style="margin-left: 20px;">
            <div class="col d-flex justify-content-center align-items-center" style="margin-bottom: 20px;margin-top: 20px;">
              <?= $this->Form->input('titre', array('label' => 'Nom de la tâche * :  ', 'value' => $titre)); ?>
            </div>
        </div>
        <div class="row">
            <div class="col d-flex justify-content-center align-items-center" style="margin-bottom: 20px;margin-top: 20px; height: 100px;">
              <label class="label">Description :</label>
              <?= $this->Form->textarea('description', array('label' => 'Description : ', 'style'=>'width:70%; height:90%; resize:none;')); ?>
            </div>
        </div>
        <!-- Fin formulaire de modification -->

        <!-- Début boutons Retour et Modifier la tâche -->
        <div class="row">
            <div class="col text-left" style="margin-top: 20px;"><label class="col-form-label text-danger">* Champs obligatoires.</label></div>
            <div class="col text-center" style="margin-top:20px;"><?= $this->Html->link("Retour", array('controller' => 'Tache', 'action'=> 'index', $idProjet), array('class' => 'btn btn-primary')); ?></div>
            <div class="col text-center" style="margin-top: 20px;"><?= $this->Form->submit('Modifier la tâche', array('class' => 'btn btn-primary', 'controller' => 'Tache', 'action'=> 'index', $idProjet, $idTache)); ?></div>
        </div>
        <!-- Fin boutons Retour et Modifier la tâche -->
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
