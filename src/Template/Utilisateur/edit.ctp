<div style="height: 80vh;margin-top: 20px;">
  <div class="container d-flex flex-column justify-content-start" style="height: 80vh;">
    <!-- Début Titre -->
    <div class="row" style="margin-top: 40px;">
      <div class="col">
        <h1 class="text-center">Mes informations</h1>
      </div>
    </div>
    <!-- Fin Titre -->

    <div class="row" style="margin-top: 40px;">
      <!-- Début Informations du compte -->
      <div class="col">
        <p>E-Mail : <?= $utilisateur->email; ?></p>
        <p>Nom : <?= $utilisateur->nom; ?></p>
        <p>Prénom : <?= $utilisateur->prenom; ?></p>
        <p>Pseudo actuel : <?= $utilisateur->pseudo; ?></p>
      </div>
      <!-- Fin Informations du compte -->

      <!-- Début du formulaire de modification -->
      <?= $this->Form->create('Utilisateur'); ?>
      <div class="col d-flex flex-column">

        <div class="col text-right" style="margin-bottom: 5px;margin-top: 5px;">
          <?= $this->Form->input('pseudo', array('label' => 'Nouveau pseudo :', 'value' => $utilisateur->pseudo)); ?></div>

        <div class="col text-right" style="margin-bottom: 5px;margin-top: 5px;">
          <?= $this->Form->input('nom', array('label' => 'Nouveau nom :', 'value' => $utilisateur->nom)); ?></div>

        <div class="col text-right" style="margin-bottom: 5px;margin-top: 5px;">
          <?= $this->Form->input('prenom', array('label' => 'Nouveau prénom :', 'value' => $utilisateur->prenom)); ?></div>


        <div class="col text-right" style="margin-bottom: 5px;margin-top: 5px;">
          <label for="mdpActu">Mot de passe actuel :</label><?= $this->Form->password('mdpActu'); ?></div>

        <div class="col text-right" style="margin-bottom: 5px;margin-top: 5px;">
          <div class="tooltipblblbl" style="margin-right: 4px;" >
            ?
          <div class="tooltiptext">
            - Votre mot de passe doit faire 8 caractères minimum. </br>
            - Votre mot de passe doit contenir au moins une minuscule. </br>
            - Votre mot de passe doit contenir au moins une majuscule. </br>
            - Votre mot de passe doit contenir au moins un chiffre. </br>
            - Pas de caractères spéciaux. </br>
          </div>
        </div>
          <label for="mdpNew">Nouveau mot de passe :</label><?= $this->Form->password('mdpNew'); ?></div>

        <div class="col text-right" style="margin-bottom: 5px;margin-top: 5px;">
          <label for="mdpNewConf">Confirmer le nouveau mot de passe :</label><?= $this->Form->password('mdpNewConf'); ?></div>

      </div>
      <!-- Fin du formulaire de modification -->

      <!-- Début Boutons Valider et supprimer le compte-->
      <div class="row" style="margin-top: 40px;">
        <div class="col-xl-8 d-flex justify-content-end align-items-center">
          <?= $this->Form->submit('Valider', array('class' => 'btn shadow boutonValider')); ?>
        </div>
        <div class="col d-flex flex-column justify-content-around align-items-center" style="height: 150px;">
          <?= $this->Html->link("Supprimer son compte", array('controller' => 'Utilisateur'), array('button class' => 'btn btn-danger', 'data-toggle' => 'modal', 'data-target' => '#supprimerModal')); ?>
        </div>
      </div>
      <!-- Fin Boutons Valider et supprimer le compte-->
    </div>
  </div>
</div>

<!-- Début modal supprimer son compte : -->
<div class="modal fade" id="supprimerModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                <div class="modal-body" style="text-align:center;">
                    <p style="width: 477px;">Voulez-vous vraiment supprimer votre compte ?</p>
                </div>
                <div class="modal-footer text-center">
                    <div class="row text-center" style="width: 484px;">
                        <div class="col text-right">
                          <?php echo $this->Html->link("Non", array('action'=> 'edit'), array( 'button class' => 'btn btn-primary', 'data-dismiss' => 'modal'));?>
                        </div>
                        <div class="col text-left">
                          <?php echo $this->Html->link("Oui", array('controller' => 'Utilisateur', 'action'=> 'deleteAccount'), array( 'button class' => 'btn btn-danger'));?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<!-- Fin modal supprimer son compte -->
