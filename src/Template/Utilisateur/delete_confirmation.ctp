<!-- Demande confirmation pour supprimer son compte : -->

<div class="container" style="margin-top: 150px;">
    <!-- Question -->
    <div class="row">
        <div class="col text-center">
            <h3 class="text-center">Souhaitez-vous vraiment supprimer votre compte ? (Cliquez pas sur oui, vous allez tout faire exploser, le code est pas terminé)</h3>
        </div>
    </div>

    <!-- Boutons Oui et Non : -->
    <div class="col text-center" style="margin-top: 150px;">
        <?= $this->Html->link("Oui", array('controller' => 'Utilisateur','action'=> 'deleteAccount'), array( 'class' => 'btn btn-primary btn-lg text-center bg-danger shadow-sm'))?>
        <?= $this->Html->link("Non", array('controller' => 'Utilisateur', 'action'=> 'edit'), array( 'class' => 'btn btn-primary btn-lg text-left bg-success shadow-sm'))?>
    </div>
</div>
