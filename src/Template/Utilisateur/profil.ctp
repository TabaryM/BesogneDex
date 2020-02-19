<div class="container" style="margin-top: 50px;">
    <div class="row">
        <div class="col" style="margin-bottom: 54px;">
          <h1 class="text-center">Mes informations</h1>
        </div>
    </div>

    <!-- Informations : -->
    <div class="row">
        <div class="col text-center" style="font-size: 14px;">
          <p>E-Mail : <?php echo $utilisateur->email; ?></p>
          <p>Nom : <?php echo $utilisateur->nom; ?></p>
          <p>Prénom : <?php echo $utilisateur->prenom; ?></p>
          <p>Pseudo : <?php echo $utilisateur->pseudo; ?></p>
        </div>
    </div>

    <!-- Bouton Modifier mon compte : -->
    <div class="row text-center">
        <div class="col text-center" style="margin-top: 58px;">
          <?php echo $this->Html->link("Modifier mon compte", array('controller' => 'Utilisateur', 'action'=> 'edit'), array( 'class' => 'btn btn-primary btn-lg shadow'))?>
        </div>
    </div>
</div>
