<!-- Auteur : Valérie MARISSENS -->

<div class="container" style="margin-top: 50px;">
      <!-- Début titre -->
      <div class="row">
          <div class="col-xl-12 offset-xl-0"><h1><center>Créer un projet</center></h1></div>
      </div>
      <!-- Fin titre -->

      <!-- Début formulaire de création -->
      <?= $this->Form->create('Projet'); ?>
      <div class="row" style="margin-left: 20px;">
          <div class="col text-center" style="margin-bottom: 20px;margin-top: 20px;"><?= $this->Form->input('titre', array('label' => 'Titre du projet : ', 'maxlength'=>128)); ?></div>
      </div>
      <div class="row">
          <div class="col d-flex justify-content-center align-items-center label">
              <label>Date de début :&nbsp;</label>
              <?= $this->Form->input('dateDebut', array('label' => false, 'type' => 'date', 'default' => $today, 'minYear' => $today->year)); ?>
          </div>
          <div class="col text-left" style="margin-bottom: 20px;margin-top: 20px;">
              <label>Date de fin :&nbsp;</label>
              <?= $this->Form->date('dateFin', array('label' => 'Date de fin :  ', 'minYear' => $today->year)); ?>
          </div>
      </div>
      <div class="row">
          <div class="col d-flex justify-content-center align-items-center">
              <label class="label">Description :</label>
              <?= $this->Form->textarea('description', array('label' => false, 'style'=>'width:70%; height:90%; resize:none;',  'maxlength'=>512)); ?>
          </div>
      </div>
      <!-- Fin formulaire de création -->

      <!-- Début boutons Retour et Créer un projet -->
      <div class="row">
          <div class="col text-center" style="margin-top: 20px;"><?= $this->Html->link("Retour", array('controller' => 'Projet', 'action'=> 'index'), array( 'class' => 'btn btn-primary')); ?></div>
          <div class="col text-center" style="margin-top: 20px;"><?= $this->Form->submit('Créer un projet', array('class' => 'btn btn-primary')); ?></div>
      </div>
      <!-- Fin boutons Retour et Créer un projet -->
  </div>
