<header class="d-flex flex-row justify-content-start align-items-center header">
    <div class="d-flex flex-row justify-content-start align-items-center header">
      <?= $this->Html->image("icones/rotom_dex.png", ['class' => 'image_icone']) ?>
      <h1 class="titre_header"><?= $titre ?></h1>
    </div>
    <?php
      //TODO Booléen qui gère si l'utilisateur est connecté ou non
      if(!$loggedIn):
        ?>
          <div class="d-flex justify-content-end align-items-center div_mail_mdp_header">
              <div class="d-flex flex-column mail_div_header">
                  <div>
                    <?php $this->Form->create(null, ['url' => ['controller' => 'Utilisateur', 'action' => 'login']]); ?>
                    <?= $this->Form->control('email', array('label' => 'E-mail :')); ?>
                  </div>
                  <div class="form-check">

                    <input class="form-check-input" type="checkbox" id="formCheck-1">
                    <label class="form-check-label" for="formCheck-1">Rester connecté</label>
                  </div>
              </div>
              <div class="d-flex flex-column mdp_div_header">
                  <div>
                    <?= $this->Form->control('mdp', array('label' => 'Mot de passe :')); ?>
                  </div>
                  <a href="#">Mot de passe oublié ?</a>
              </div>

              <?= $this->Form->submit('Se connecter', array('class' => 'btn btn-primary')) ?>
              <?= $this->Flash->render() ?>
          </div>
        <?php
      endif;
    ?>
</header>
