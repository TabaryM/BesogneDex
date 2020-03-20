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
              <td class="d-flex justify-content-center">

                  <?php if ($notif->une_notification->a_valider): ?>

                      <?php if ($notif->une_notification->type == 'Invitation'): ?>

                          <?php if ($notif->etat == 'En attente'): ?>
                              <?= $this->Html->link("Accepter", ['controller'=> 'notification', 'action'=> 'acceptInvitation', $notif->idNotification], ['class' => 'btn btn-primary']); ?>
                              <?= $this->Html->link("Refuser", ['controller' => 'notification', 'action'=> 'declineInvitation', $notif->idNotification], ['class' => 'btn btn btn-danger']); ?>

                          <?php elseif ($notif->etat == 'Accepté'): ?>
                              <button class="btn btn-primary" disabled="true"> Invitation acceptée </button>

                          <?php else: ?>
                              <button class="btn btn-danger" disabled="true"> Invitation refusée </button>

                          <?php endif; ?>

                      <?php elseif ($notif->une_notification->type == 'Suppression'): ?>

                          <?php if ($notif->etat == 'En attente'): ?>
                              <?= $this->Html->link("Accepter", ['action'=> '#'], ['class' => 'btn btn-primary']); ?>
                              <?= $this->Html->link("Refuser", ['controller' => 'notification', 'action'=> 'refuserSuppressionTache', $notif->idNotification], ['class' => 'btn btn btn-danger']); ?>

                          <?php elseif ($notif->etat == 'Accepté'): ?>
                              <button class="btn btn-primary" disabled="true"> Tâche supprimée </button>

                          <?php else: ?>
                              <button class="btn btn-danger" disabled="true"> Tâche non supprimée </button>

                          <?php endif; ?>

                      <?php else: ?>

                        <!-- @TODO Changement de propriétaire -->

                      <?php endif; ?>

                  <?php else: ?>

                      <?php if ($notif->une_notification->idProjet != null): ?>
                          <?= $this->Html->link("Consulter le projet", ['controller' => 'tache', 'action'=> 'index', $notif->une_notification->idProjet], ['class' => 'btn btn-primary']); ?>
                      <?php endif; ?>

                  <?php endif; ?>

                  <?php if ($notif->vue  || !($notif->une_notification->a_valider)): ?>

                    <?= $this->Html->link("Supprimer","", ['class' => 'btn btn-danger shadow', 'data-toggle' => 'modal', 'data-target' => '#deleteModal' . $notif->idNotification]) ?>

                  <?php endif; ?>
              </td>
            </tr>


            <!-- Début modal Supprimer une notification : -->
            <div class="modal fade" id=<?= "deleteModal" . $notif->idNotification ?>>
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h4 class="modal-title"></h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                </div>
                                <div class="modal-body" style="text-align:center;">
                                <p style="width: 477px;">Êtes-vous sûr de vouloir supprimer cette notification ?</p>
                                </div>

                            <div class="modal-footer text-center">
                                <div class="row text-center" style="width: 484px;">
                                    <div class="col text-right">
                                      <?= $this->Html->link("Non", array('controller' => 'Notification', 'action'=> 'index'), array( 'button class' => 'btn btn-primary', 'data-dismiss' => 'modal'));?>
                                    </div>
                                    <div class="col text-left">
                                      <?= $this->Html->link("Oui", array('controller' => 'Notification', 'action'=> 'supprimerNotification',  $notif->idNotification), array( 'button class' => 'btn btn-danger'));?>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
            </div>
            <!-- Fin modal Supprimer une notification -->


          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
