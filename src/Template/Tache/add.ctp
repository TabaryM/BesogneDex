<!-- Début modification header -->
<?php
  use Cake\Core\Configure;
  Configure::write('titre_header_tache', $titreProjet);
?>
<!-- Fin modification header -->

<div class="container form-addTache" style="margin-top: 50px;">
      <div class="row">
          <div class="col-xl-12 offset-xl-0"><h1>Ajouter une tâche</h1></div>
      </div>
      <?= $this->Form->create('Tache'); ?>
      <div class="row">
          <div class="col-xl-2 text-center" style="margin-bottom: 20px;margin-top: 20px;"><?= $this->Form->label('titre', 'Titre de la tâche: '); ?></div>
          <div class="col-xl-10 text-center" style="margin-bottom: 20px;margin-top: 20px;"><?= $this->Form->text('titre'); ?></div>
      </div>
      <div class="col text-center" style="margin-top: 20px;"><?= $this->Form->label('description', 'Description'); ?></div>
      <div class="row">
          <div class="col text-center" style="margin-bottom: 20px;"><?= $this->Form->textarea('description'); ?></div>
      </div>
      <div class="row">
        <div class="form-check">
          <?= $this->Form->checkbox('estResponsable'); ?>
          <label class="form-check-label" for="formCheck-2">Je suis responsable de cette tâche</label>
        </div>
      </div>
      <div class="row">
          <div class="col text-center" style="margin-top: 20px;"><?= $this->Html->link("Retour", array('controller' => 'Tache', 'action'=> 'index', $idProjet), array( 'class' => 'btn btn-primary')); ?></div>

          <div class="col text-center" style="margin-top: 20px;"><?= $this->Form->submit('Ajouter une tâche', array('value' => 'idProjet', 'class' => 'btn btn-primary'), $idProjet); ?></div>
      </div>
  </div>
