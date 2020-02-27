<!-- Auteur : Valérie MARISSENS -->

<div class="container" style="margin-top: 50px;">
      <div class="row">
          <div class="col-xl-12 offset-xl-0"><h1><center>Créer un projet</center></h1></div>
      </div>

      <!-- Formulaire de création -->
      <?= $this->Form->create('Projet'); ?>
      <div class="row" style="margin-left: 20px;">
          <div class="col text-center" style="margin-bottom: 20px;margin-top: 20px;"><?= $this->Form->input('titre', array('label' => 'Titre du projet : ')); ?></div>
      </div>
      <div class="row">
          <div class="col text-center" style="margin-bottom: 20px;margin-top: 20px;"><?= $this->Form->input('dateDebut', array('type' => 'date', 'label' => 'Date de début :  ')); ?></div>
          <div class="col text-left" style="margin-bottom: 20px;margin-top: 20px;"><?= $this->Form->input('dateFin', array('label' => 'Date de fin :  ', 'type' => 'date')); ?></div>
      </div>
      <div class="row">
          <div class="col text-center" style="margin-bottom: 20px;margin-top: 20px;">Description : <?= $this->Form->textarea('description', array('label' => 'Description : '), array('style' => 'width: 70%;height: 90%;resize: none;')); ?></div>
      </div>

      <!-- Boutons Retour et Créer un projet -->
      <div class="row">
          <div class="col text-center" style="margin-top: 20px;"><?= $this->Html->link("Retour", array('controller' => 'Projet', 'action'=> 'index'), array( 'class' => 'btn btn-primary')); ?></div>
          <div class="col text-center" style="margin-top: 20px;"><?= $this->Form->submit('Créer un projet', array('class' => 'btn btn-primary')); ?></div>
      </div>
  </div>
