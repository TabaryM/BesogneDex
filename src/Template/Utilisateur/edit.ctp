<div style="height: 80vh;margin-top: 20px;">
  <div class="container d-flex flex-column justify-content-start" style="height: 80vh;">
    <div class="row" style="margin-top: 40px;">
      <div class="col">
        <h1 class="text-center">Mes informations</h1>
      </div>
    </div>
    <div class="row" style="margin-top: 40px;">
      <div class="col">
        <p>E-Mail : <?php echo $utilisateur->email; ?></p>
        <p>Nom : <?php echo $utilisateur->nom; ?></p>
        <p>Prénom : <?php echo $utilisateur->prenom; ?></p>
        <p>Pseudo actuel : <?php echo $utilisateur->pseudo; ?></p>
      </div>
      <?= $this->Form->create('Utilisateur'); ?>
      <div class="col d-flex flex-column">
        <div class="col text-right" style="margin-bottom: 5px;margin-top: 5px;"><?= $this->Form->input('pseudo', array('label' => 'Nouveau pseudo :', 'class' => 'label')); ?></div>
        <div class="col text-right" style="margin-bottom: 5px;margin-top: 5px;"><?= $this->Form->input('nom', array('label' => 'Nouveau nom :', 'class' => 'label')); ?></div>
        <div class="col text-right" style="margin-bottom: 5px;margin-top: 5px;"><?= $this->Form->input('prenom', array('label' => 'Nouveau prénom :', 'class' => 'label')); ?></div>
        <div class="col text-right" style="margin-bottom: 5px;margin-top: 5px;"><label class="pr-2" for="mdp_actu">Mot de passe actuel :</label><?= $this->Form->password('mdp_actu'); ?></div>
        <div class="col text-right" style="margin-bottom: 5px;margin-top: 5px;"><label class="pr-2" for="mdp-new">Nouveau mot de passe :</label><?= $this->Form->password('mdp_new'); ?></div>
        <div class="col text-right" style="margin-bottom: 5px;margin-top: 5px;"><label class="pr-2" for="mdp_new_conf">Confirmer le nouveau mot de passe :</label><?= $this->Form->password('mdp_new_conf'); ?></div>
      </div>
    </div>
    <div class="row" style="margin-top: 40px;">
      <div class="col-xl-8 d-flex justify-content-end align-items-center">
        <?= $this->Form->submit('Valider', array('class' => 'btn shadow boutonValider')); ?>
      </div>
      <div class="col d-flex flex-column justify-content-around align-items-center"
      style="height: 150px;">
<<<<<<< HEAD
      <!--<button class="btn btn-primary shadow" type="button">Modifier votre mot de passe</button>-->
=======
>>>>>>> 695da1cf2f09c8738ab3fa72f91008426bea5d8a

      <?php
      echo $this->Html->link("Supprimer mon compte", array('controller' => 'Utilisateur', 'action' => 'deleteConfirmation'), array( 'class' => 'btn btn-danger'));
      ?>
    </div>
  </div>
</div>
</div>
