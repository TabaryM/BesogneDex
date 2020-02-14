<h1> Ici c'est pour ajouter une tâche ! </h1>
<?php
    echo $this->Form->create('Tache');
    echo $this->Form->input('titre', array('label' => 'Titre de la tâche :'));
    echo $this->Form->input('description', array('label' => 'Description :'));
    echo $this->Form->submit('Créer');
?>

<div class="container">
      <div class="row">
          <div class="col-xl-12 offset-xl-0"><h1><center>Ajouter une tâche</center></h1></div>
      </div>
      <div class="row" style="margin-left: 20;">
          <div class="col text-center" style="margin-bottom: 20px;margin-top: 20px;"><label>Nom de la tâche :&nbsp;</label><input type="text" name="Nom :"></div>
      </div>
      <div class="row">
          <div class="col text-center" style="margin-bottom: 20px;margin-top: 20px;"><label>Description :&nbsp;</label><input class="form-control-lg" type="text" style="margin-right: 50px;"></div>
      </div>
      <div class="row">
        <div class="form-check">
          <input id="formCheck-2" class="form-check-input" type="checkbox" />
          <label class="form-check-label" for="formCheck-2">Je suis responsable de cette tâche</label>
        </div>
      </div>
      <div class="row">
          <div class="col text-center" style="margin-top: 20px;"><button class="btn btn-primary btn-lg text-left border-dark shadow-sm" type="button">Retour</button></div>
          <div class="col text-center" style="margin-top: 20px;"><button class="btn btn-primary btn-lg border-dark shadow-sm" type="button">Ajouter la tâche</button></div>
      </div>
  </div>

  <!-- TODO Changer en un formulaire pour submit -->
