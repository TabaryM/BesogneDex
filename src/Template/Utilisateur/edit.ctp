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
      <div class="col d-flex flex-column justify-content-around align-items-end align-content-end">
        <div class="col text-center" style="margin-bottom: 5px;margin-top: 5px;"><?= $this->Form->input('pseudo', array('label' => 'Nouveau pseudo :')); ?></div>
        <div class="col text-center" style="margin-bottom: 5px;margin-top: 5px;"><?= $this->Form->input('nom', array('label' => 'Nouveau nom :')); ?></div>
        <div class="col text-center" style="margin-bottom: 5px;margin-top: 5px;"><?= $this->Form->input('prenom', array('label' => 'Nouveau prénom :')); ?></div>
      </div>
    </div>
    <div class="row" style="margin-top: 40px;">
      <div class="col-xl-8 d-flex justify-content-end align-items-center" style="width: 30%;height: 40%;background-color: #b6d7a8;color: rgb(0,0,0);margin-right: 80px;">
        <?= $this->Form->submit('Valider', array('class' => 'btn shadow')); ?>
      </div>
      <div class="col d-flex flex-column justify-content-around align-items-center"
      style="height: 150px;">
      <button class="btn btn-primary shadow" type="button">Modifier votre mot de passe</button>
      <button class="btn shadow" type="button" style="background-color: #ea9999;color: rgb(0,0,0);">Supprimer mon compte</button>
    </div>
  </div>
</div>
</div>
