<!-- Modifier une tâche -->
<!-- Auteur : Valérie MARISSENS -->


<div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col"><h1><center>Modifier la tâche</center></h1></div>
        </div>

        <!-- Formulaire de modification -->
        <?= $this->Form->create('Tache'); ?>
        <div class="row" style="margin-left: 20px;">
            <div class="col text-center" style="margin-bottom: 20px;margin-top: 20px;"><?= $this->Form->input('titre', array('label' => 'Nom de la tâche * :  ', 'value' => $titre)); ?></div>
        </div>
        <div class="row">
            <div class="col text-center" style="margin-bottom: 20px;margin-top: 20px; height: 100px;"><?= $this->Form->input('description', array('label' => 'Description :  ', 'value' => $description)); ?></div>
        </div>

        <!-- Boutons Retour et Modifier la tâche -->
        <div class="row">
            <div class="col text-left" style="margin-top: 20px;"><label class="col-form-label text-danger">* Champs obligatoires.</label></div>
            <div class="col text-center" style="margin-top:20px;"><?= $this->Html->link("Retour", array('controller' => 'Tache', 'action'=> 'index', $idProjet), array('class' => 'btn btn-primary')); ?></div>
            <div class="col text-center" style="margin-top: 20px;"><?= $this->Form->submit('Modifier la tâche', array('class' => 'btn btn-primary', 'controller' => 'Tache', 'action'=> 'index', $idProjet, $idTache)); ?></div>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
