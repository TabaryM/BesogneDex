<!-- Demande confirmation pour se déconnecter : -->
<!-- Auteur : Valérie MARISSENS -->

<div class="container" style="margin-top: 150px;">
    <!-- Question -->
    <div class="row">
        <div class="col text-center">
          <h3 class="text-center">Souhaitez-vous vraiment vous déconnecter ?</h3>
        </div>
    </div>

    <!-- Boutons Oui et Non : -->
    <div class="col text-center" style="margin-top: 150px;">
        <?= $this->Html->link("Oui", array('controller' => 'Utilisateur','action'=> 'logout'), array( 'class' => 'btn btn-primary btn-lg text-center bg-danger shadow-sm'))?>
        <?= $this->Html->link("Non", array('controller' => 'Utilisateur','action'=> '#'), array( 'class' => 'btn btn-primary btn-lg text-left bg-success shadow-sm'))?>
    </div>
</div>
