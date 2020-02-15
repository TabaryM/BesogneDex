<?php
/**
 * CakePHP(tm) : Rapid Development Framework (https://cakephp.org)
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice.
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://cakephp.org CakePHP(tm) Project
 * @since         0.10.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */
use Cake\Cache\Cache;
use Cake\Core\Configure;
use Cake\Core\Plugin;
use Cake\Datasource\ConnectionManager;
use Cake\Error\Debugger;
use Cake\Http\Exception\NotFoundException;

$this->layout = false;

if (!Configure::read('debug')) :
    throw new NotFoundException(
        'Please replace src/Template/Pages/home.ctp with your own version or re-enable debug mode.'
    );
endif;

$cakeDescription = 'BesogneDex';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>
    </title>

    <?= $this->Html->meta('icon') ?>
    <?= $this->Html->css('bootstrap.min.css') ?>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/3.5.2/animate.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.css">
    <?= $this->Html->css('besogne.css') ?>

    <link href="https://fonts.googleapis.com/css?family=Raleway:500i|Roboto:300,400,700|Roboto+Mono" rel="stylesheet">

</head>
<body class="home">

      <?= $this->element('header', ['titre' => 'BesogneDex']) ?>

      <!-- TODO Il faudra mettre tout les styles dans le css -->
      <div style="height: 80vh;margin-top: 20px;">
          <div class="container" style="height: 80vh;">
              <div class="row d-flex align-items-center" style="height: 100%;">
                  <div class="col-md-6 text-center d-flex flex-column justify-content-around colonne_description_accueil" style="font-size: 20px;font-weight: normal;height: 80%;">
                      <div class="row text-center">
                          <div class="col-xl-10 text-center" data-aos="fade-right" data-aos-duration="1000" data-aos-delay="100">
                              <p style="font-size: 25px;">BesogneDex est votre gestionnaire de tâches en ligne !</p>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-xl-10" data-aos="fade-right" data-aos-duration="700" data-aos-delay="800">
                              <p style="font-size: 25px;">Grâce à BesogneDex, vous pouvez organiser vos projets et vos évènement tout en restant organisés et connectés !</p>
                          </div>
                      </div>
                      <div class="row text-center">
                          <div class="col-lg-12 col-xl-10" data-aos="fade-right" data-aos-duration="700" data-aos-delay="1500">
                              <p style="font-size: 25px;">Améliorez votre productivité, invitez vos amis et attribuez leur des tâches, et organisez vos projets de manière révolutionnaire !</p>
                          </div>
                      </div>
                  </div>
                  <div class="col-xl-6 d-flex flex-column justify-content-around" data-aos="fade-left" data-aos-duration="700" data-aos-delay="2200" style="height: 80%;">
                      <div class="row" style="height: 60%;">
                          <div class="col-md-6 col-xl-6 text-center d-flex flex-column justify-content-around align-items-end" style="height: 100%;">

                              <div class="row">
                                  <div class="col-xl-12"><label class="col-form-label label">E-mail :</label></div>
                              </div>
                              <div class="row">
                                  <div class="col"><label class="col-form-label label">Mot de passe :</label></div>
                              </div>
                              <div class="row">
                                  <div class="col"><label class="col-form-label label">Confirmation :</label></div>
                              </div>
                              <div class="row">
                                  <div class="col"><label class="col-form-label label">Pseudo :</label></div>
                              </div>
                              <div class="row">
                                  <div class="col"><label class="col-form-label label">Nom :</label></div>
                              </div>
                              <div class="row">
                                  <div class="col"><label class="col-form-label label">Prénom :</label></div>
                              </div>
                              <?= $this->Form->create('Utilisateur', ['url' => ['controller' => 'Utilisateur', 'action' => 'add']]); ?>
                          </div>
                          <div class="col-xl-6 d-flex flex-column justify-content-around" style="height: 100%;">

                              <div class="row">
                                  <div class="col" style="height: 38px;"><?= $this->Form->control('email', ['label' => '', 'style'=>'height: 38px;']) ?></div>
                              </div>
                              <div class="row">
                                  <div class="col" style="height: 38px;"><?= $this->Form->control('mdp', ['label' => '', 'style'=>'height: 38px;']) ?></a></div>
                              </div>
                              <div class="row">
                                  <div class="col" style="height: 38px;"><?= $this->Form->control('mdpp', ['label' => '', 'style'=>'height: 38px;']) ?></div>
                              </div>
                              <div class="row">
                                  <div class="col" style="height: 38px;"><?= $this->Form->control('pseudo', ['label' => '', 'style'=>'height: 38px;']) ?></div>
                              </div>
                              <div class="row">
                                  <div class="col" style="height: 38px;"><?= $this->Form->control('nom', ['label' => '', 'style'=>'height: 38px;']) ?></div>
                              </div>
                              <div class="row">
                                  <div class="col" style="height: 38px;"><?= $this->Form->control('prenom', ['label' => '', 'style'=>'height: 38px;']) ?></div>
                              </div>
                          </div>
                      </div>
                      <div class="row">
                          <div class="col-xl-12 text-right" style="height: 80px;"><?= $this->Form->submit('Créer mon compte', array('class' => 'btn btn-primary', 'style' =>'height: 100%;width: 60%;font-size: 28px;')) ?></div>
                          <?= $this->Form->end(); ?>
                      </div>
                  </div>
              </div>
          </div>
      </div>

      <script src="https://code.jquery.com/jquery-3.4.1.min.js" integrity="sha256-CSXorXvZcTkaix6Yvo6HppcZGetbYMGWSFlBw8HfCJo=" crossorigin="anonymous"></script>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
      <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
      <?= $this->Html->script('bs-init.js') ?>
      <script src="https://cdnjs.cloudflare.com/ajax/libs/aos/2.1.1/aos.js"></script>

</body>
</html>
