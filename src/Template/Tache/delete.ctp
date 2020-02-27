<!-- Supprimer une tâche -->
<!-- Auteur : Valérie MARISSENS -->

<div class="container" style="margin-top: 215px;">
        <!-- Question -->
        <div class="row">
            <div class="col text-center"><h4 class="text-center">Êtes-vous sûr de vouloir demander la suppression de cette tâche ?</h4></div>
        </div>

        <!-- Boutons Oui et Non -->
        <div class="col text-center" style="margin-top: 60px;">
            <?php
            echo $this->Html->link("Non", array('controller' => 'Tache', 'action'=> 'index', $idProj), array( 'class' => 'btn btn-lg btn-success'));
            ?>
            <?php
            echo $this->Html->link("Oui", array('controller' => 'Tache', 'action'=> 'index', $idProj, $idTach), array( 'class' => 'btn btn-lg btn-danger'));
            ?>
        </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
