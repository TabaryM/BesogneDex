<!-- Auteur : Valérie MARISSENS -->
<div class="container" style="margin-top: 50px;">

    <!-- Début titre -->
    <div class="row">
        <div class="col" style="margin-bottom: 54px;">
          <h1 class="text-center">Mes informations</h1>
        </div>
    </div>
    <!-- Fin titre -->

    <!-- Début informations : -->
    <div class="row">
        <div class="col text-center" style="font-size: 14px;">
            <p>E-Mail : <?= $utilisateur->email; ?></p>
            <p>Nom : <?= $utilisateur->nom; ?></p>
            <p>Prénom : <?= $utilisateur->prenom; ?></p>
            <p>Pseudo : <?= $utilisateur->pseudo; ?></p>
        </div>
    </div>
    <!-- Fin informations -->

    <!-- Début bouton Modifier mon compte : -->
    <div class="row text-center">
        <div class="col text-center" style="margin-top: 58px;">
          <?php echo $this->Html->link("Modifier mon compte", array('controller' => 'Utilisateur', 'action'=> 'edit'), array( 'class' => 'btn btn-primary btn-lg shadow'))?>
        </div>
    </div>
    <!-- Fin bouton Modifier mon compte -->
</div>
