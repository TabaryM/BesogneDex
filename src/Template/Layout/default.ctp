<?php

use Cake\Core\Configure;
use Cake\ORM\TableRegistry;

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

$cakeDescription = 'BesogneDex';
?>
<!DOCTYPE html>
<html>
<head>
    <?= $this->Html->charset() ?>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>
        <?= $cakeDescription ?>:
        <?= $this->fetch('title') ?>
    </title>
    <?= $this->Html->meta('icon') ?>


    <?= $this->Html->css('bootstrap.min.css') ?>
    <?= $this->Html->css('besogne.css') ?>

    <?= $this->fetch('meta') ?>
    <?= $this->fetch('css') ?>
    <?= $this->fetch('script') ?>

    <?= $this->Html->script('https://code.jquery.com/jquery-1.10.2.js'); ?>
    <?= $this->Html->script('https://code.jquery.com/ui/1.11.4/jquery-ui.js'); ?>
    <?= $this->Html->css('https://code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css'); ?>

    <?= $this->Html->script('navbar.js') ?>

</head>
<body class="default">

  <?php

    $session = $this->request->getSession();
    // Initialisation des tables
    $tableNotifications = TableRegistry::getTableLocator()->get('VueNotification');
    // Récupération des notifications
    $notifs = $tableNotifications->find()->contain('Notification')->where(['idUtilisateur' => $session->read('Auth.User.idUtilisateur'), 'vue' => 0])->toArray();

    $nbNotifications = sizeof($notifs);
    if($nbNotifications == 0){
      $nbNotif = '';
    }else{
      if($nbNotifications > 9){
        $nbNotif = '9+';
      }else{
        $nbNotif = $nbNotifications;
      }
    }
   ?>
  <?= $this->element('header', ['titre' => Configure::read('titre_header_tache'), 'utilisateurProprietaire' => Configure::read('utilisateurProprietaire'), 'estExpire' => Configure::read('estExpire'), 'nbNotif' => $nbNotif]) ?>
  <?= $this->element('navbar') ?>
  <?= $this->Flash->render() ?>
  <?= $this->fetch('content') ?>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <?= $this->Html->script('tacheTermine.js'); ?>

</body>
</html>
