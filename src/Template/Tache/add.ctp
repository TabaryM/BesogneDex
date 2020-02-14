<div class="container">
      <div class="row">
          <div class="col-xl-12 offset-xl-0"><h1><center>Ajouter une tâche</center></h1></div>
      </div>
      <?= $this->Form->create('Tache'); ?>
      <div class="row" style="margin-left: 20;">
          <div class="col text-center" style="margin-bottom: 20px;margin-top: 20px;"><?= $this->Form->input('titre', array('label' => 'Titre de la tâche :')); ?></div>
      </div>
      <div class="row">
          <div class="col text-center" style="margin-bottom: 20px;margin-top: 20px;"><?= $this->Form->input('description', array('label' => 'Description :')); ?></div>
      </div>
      <div class="row">
        <div class="form-check">
          <input id="formCheck-2" class="form-check-input" type="checkbox" />
          <label class="form-check-label" for="formCheck-2">Je suis responsable de cette tâche</label>
        </div>
      </div>
      <div class="row">
          <div class="col text-center" style="margin-top: 20px;"><button class="btn btn-primary btn-lg text-left border-dark shadow-sm" type="button">Retour</button></div>

          <div class="col text-center" style="margin-top: 20px;"><?= $this->Form->submit('Créer', array('class' => 'btn btn-primary')); ?></div>
      </div>
  </div>

  <!-- TODO Changer en un formulaire pour submit -->
