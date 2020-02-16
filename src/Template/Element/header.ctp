<?= $this->Flash->render() ?>
<header class="d-flex flex-row justify-content-start align-items-center header">
    <div class="d-flex flex-row justify-content-start align-items-center header">
      <div class="col-xl-4 d-flex align-items-center">
      <?= $this->Html->image("icones/rotom_dex.png", ['class' => 'image_icone']) ?>
      <h1 class="titre_header"><?= $titre ?></h1>
      </div>
    </div>
    <?php   if(!$loggedIn):  ?>
          <div class="d-flex justify-content-end align-items-center div_mail_mdp_header">
              <div class="d-flex flex-column mail_div_header">
                  <div>
                    <?= $this->Form->create(null, ['url' => ['controller' => 'Utilisateur', 'action' => 'login']] ); ?>
                    <?= $this->Form->control('email', array('label' => 'E-mail :', 'class' => 'label')); ?>
                  </div>
                  <div class="form-check">

                    <input class="form-check-input" type="checkbox" id="formCheck-1">
                    <label class="form-check-label" for="formCheck-1">Rester connecté</label>
                  </div>
              </div>
              <div class="d-flex flex-column mdp_div_header">
                  <div>
                    <?= $this->Form->control('mdp', array('label' => 'Mot de passe :', 'class' => 'label', 'type'=>'password')); ?>
                  </div>
                  <a href="#">Mot de passe oublié ?</a>
              </div>

              <?= $this->Form->submit('Se connecter', array('class' => 'btn btn-primary')) ?>

              <?= $this->Form->end() ?>
          </div>

        <?php  endif;   ?>

    <?php   if($loggedIn):  ?>
    <div class="col d-flex flex-row-reverse justify-content-start align-items-center">
    <?= $this->Html->link("Se déconnecter", array('controller' => 'Utilisateur','action'=> 'logout'), array( 'class' => 'btn btn-primary shadow'))?>
    <?= $this->Html->link("Mon profil", array('controller' => 'Utilisateur','action'=> '#'), array( 'class' => 'btn btn-primary shadow'))?>
  </div>
    <?php  endif;   ?>
</header>
