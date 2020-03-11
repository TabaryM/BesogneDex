<!-- Auteur : Pedro -->
<div class="row d-flex align-items-start" style="margin-left:60px;margin-right:60px;margin-top:50px;">
  <div class="col-xl-12" style="height: 80%;">
    <div class="table-responsive">
      <table class="table table-borderless table-bleu">
        <tbody>
          <?php foreach ($notifs as $notif): ?>
            <tr <?php if (!$notif->vue) echo 'class="font-weight-bold"'; ?>>
              <td>
                <?= $notif->une_notification->contenu ?>
              </td>
              <td>
                <?= $notif->une_notification->date->nice('Europe/Paris', 'fr-FR') ?>
              </td>
              <td class="d-flex justify-content-center">
                <?php if ($notif->une_notification->a_valider && $notif->etat=="En attente"): ?>
                  <!-- TODO: Fonction accepter / refuser invitation -->
                  <?= $this->Html->link("Accepter", ['action'=> '#'], ['class' => 'btn btn-primary']); ?>
                  <?= $this->Html->link("Refuser", ['controller' => 'notification', 'action'=> 'declineInvitation', $notif->idNotifProjet], ['class' => 'btn btn btn-danger']); ?>
                <?php elseif ($notif->une_notification->a_valider && $notif->etat=="Accepté"): ?>
                  <button class="btn btn-primary" disabled="true"> Invitation acceptée </button>
                <?php elseif ($notif->une_notification->a_valider && $notif->etat=="Refusé"): ?>
                  <button class="btn btn-danger" disabled="true"> Invitation refusée </button>
                <?php else : ?>
                  <?= $this->Html->link("Consulter le projet", ['controller' => 'projet', 'action'=> 'index', $notif->une_notification->idProjet], ['class' => 'btn btn-primary']); ?>
                <?php endif; ?>
                <?php if ($notif->vue != 0): ?>
                  <?= $this->Html->link("Supprimer", ['controller' => 'notification', 'action' => 'supprimerNotification',  $notif->idNotifProjet]) ?>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
