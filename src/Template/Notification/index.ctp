
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
              <td>
                <?php if ($notif->une_notification->a_valider): ?>
                  <!-- TODO: Fonction accepter / refuser invitation -->
                  <?= $this->Html->link("Accepter", ['action'=> '#'], ['class' => 'btn btn-primary']); ?>
                  <?= $this->Html->link("Refuser", ['action'=> '#'], ['class' => 'btn btn-primary']); ?>
                <?php else: ?>
                  <?= $this->Html->link("Consulter le projet", ['controller' => 'projet', 'action'=> 'index', $notif->une_notification->idProjet], ['class' => 'btn btn-primary']); ?>
                <?php endif; ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
