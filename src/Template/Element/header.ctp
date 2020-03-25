<header class="d-flex flex-row justify-content-start align-items-center header">
    <div class="d-flex flex-row justify-content-start align-items-center header">
      <div class="col-xl-12 d-flex align-items-center">
      <?php echo $this->Html->image("icones/rotom_dex.png", ['class' => 'image_icone_header']); ?>
      <?php if(!$estExpire): ?>
        <h1 class="titre_header"><?= $titre ?></h1>
      <?php else: ?>
        <h1 class="titre_header_rouge"><?= $titre ?></h1>
      <?php endif; ?>
      <?php if($utilisateurProprietaire): ?>
        <?php echo $this->Html->image("icones/crown.png", ['class' => 'image_icone_header', 'style' => 'margin-left:20px;']); ?>
      <?php endif; ?>
      </div>
    </div>
    <?php   if(!$loggedIn):  ?>
          <div class="d-flex justify-content-end align-items-center div_mail_mdp_header">
              <div class="d-flex flex-column mail_div_header" style='margin-bottom:25px'>
                  <div>
                    <?= $this->Form->create(null, ['url' => ['controller' => 'Utilisateur', 'action' => 'login']] ); ?>
                    <?= $this->Form->control('email', array('label' => 'E-mail :', 'class' => 'label')); ?>
                  </div>
              </div>
              <div class="d-flex flex-column mdp_div_header">
                  <div>
                    <?= $this->Form->control('mdp', array('label' => 'Mot de passe :', 'class' => 'label', 'type'=>'password')); ?>
                  </div>
                  <div class="form-check">
                   <?=  $this->Form->control('resterConnecte', array('label'=>' Rester connecté', 'type'=>'checkbox')) ?>

                 </div>
              </div>

              <?= $this->Form->submit('Se connecter', array('class' => 'btn btn-primary')) ?>

              <?= $this->Form->end() ?>
          </div>

        <?php  endif;   ?>

    <?php   if($loggedIn):  ?>
    <div class="col d-flex flex-row-reverse justify-content-start align-items-center">
    <a class="btn logout" data-toggle="modal" data-target="#logoutModal"></a>
    <?= $this->Html->link('', array('controller' => 'Utilisateur','action'=> 'profil'), array( 'class' => 'btn user'))?>
    <?php
    if($nbNotif == ''):
      echo $this->Html->link('', ['controller' => 'notification', 'action' => 'index'], ['class' => 'btn bell notificaton']);
    else:
      echo $this->Html->link("<span class='badge'> $nbNotif </span>", ['controller' => 'notification', 'action' => 'index'], ['class' => 'btn bell notificaton', 'escape' => false]);
    endif;
    ?>



  </div>
    <?php  endif;   ?>

    <!-- Modal déconnexion : -->
    <div class="modal fade" id="logoutModal" role="dialog" tabindex="-1">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title"></h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                    <div class="modal-body">
                        <p style="width: 477px;text-align:center;">Êtes-vous sûr/e de vouloir vous déconnecter ?</p>
                    </div>
                    <div class="modal-footer text-center">
                        <div class="row text-center" style="width: 484px;">
                            <div class="col text-left">
                              <?php echo $this->Html->link("Non", array('controller' => 'Utilisateur', 'action'=> '#'), array('button class' => 'btn btn-primary', 'data-dismiss' => 'modal'));?>
                            </div>
                            <div class="col text-right">

                              <?php echo $this->Html->link("Oui", array('controller' => 'Utilisateur', 'action'=> 'logout'), array('button class' => 'btn btn-danger'));?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
      </div>
</header>
<?= $this->Flash->render() ?>
