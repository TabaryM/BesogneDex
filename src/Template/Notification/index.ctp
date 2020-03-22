<!-- Auteur : Pedro -->
<div class="row d-flex align-items-start" style="margin-left:60px;margin-right:60px;margin-top:50px;">
  <div class="col-xl-12" style="height: 80%;">
    <div class="table-responsive">
      <table class="table table-borderless table-bleu">
        <tbody>
          <!-- Si il n'y a pas de notification on affiche un message -->
          <?php if (sizeof($notifs) == 0): ?>
              <td class="d-flex justify-content-center">
                <?php echo 'Aucune notification' ?>
              </td>
          <?php endif; ?>

          <!-- Pour chacune des notification -->
          <?php foreach ($notifs as $notif): ?>
              <!-- Si la notification n'a pas était vue, on la met en gras -->
              <tr <?php if (!$notif->vue) echo 'class="font-weight-bold"'; ?>>

              <!-- On affiche le contenu de la notification -->
              <td>
                <?= $notif->une_notification->contenu ?>
              </td>
              <td class="d-flex justify-content-center">

                  <!-- Si la notification est une notification à valider -->

                      <!-- Début notification à valider  -->
                      <?php if ($notif->une_notification->type !== 'Informative'): ?>

                          <!-- Si la notification n'a pas reçu de réponse -->
                          <?php if ($notif->etat == 'En attente'): ?>
                              <!-- Fonction pour accepter l'invitation -->
                              <?= $this->Html->link("Accepter", ['controller'=> 'notification', 'action'=> 'accept', $notif->idNotification], ['class' => 'btn btn-primary']); ?>
                              <!-- Fonction pour refuser l'invitation -->
                              <?= $this->Html->link("Refuser", ['controller' => 'notification', 'action'=> 'decline', $notif->idNotification], ['class' => 'btn btn btn-danger']); ?>

                          <!-- Si la notification a été acceptée -->
                          <?php elseif ($notif->etat == 'Accepté'): ?>
                              <button class="btn btn-primary" disabled="true"> Invitation acceptée </button>

                          <!-- Si la notification a été refusée -->
                          <?php else: ?>
                              <button class="btn btn-danger" disabled="true"> Invitation refusée </button>

                          <?php endif; ?>
                      <!-- Fin notification à valider -->

                  <!-- Si la notification est une notification à voir -->
                  <?php else: ?>

                      <!-- Si la fonction est liée à un projet, on peut aller à ce projet -->
                      <?php if ($notif->une_notification->idProjet != null): ?>
                          <?= $this->Html->link("Consulter le projet", ['controller' => 'tache', 'action'=> 'index', $notif->une_notification->idProjet], ['class' => 'btn btn-primary']); ?>
                      <?php endif; ?>

                  <?php endif; ?>

                  <!-- Si la notification a reçu une réponse ou si c'est une notification à voir, on peut la supprimée -->
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
