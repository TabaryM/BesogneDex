
<!-- Auteur : Pedro -->

<div class="row d-flex align-items-start" style="margin-left:60px;margin-right:60px;margin-top:50px;">
  <div class="col-xl-12" style="height: 80%;">
    <div class="table-responsive">
      <table class="table table-borderless table-orange">
        <tbody>
          <?php foreach ($notif as $notifs): ?>
            <tr>
              <td>
                <?= $tache->titre ?>
              </td>
              <td>
                <input type="checkbox" class="checkFait" value="<?= $tache->idTache ?>" <?php if ($tache->finie) echo "checked"; ?>>
              </td>
              <td>
                <?= $tache->leProjet->dateFin->nice('Europe/Paris', 'fr-FR') ?>
              </td>
              <td>
                <?= $this->Html->link($tache->leProjet->titre, ['controller' => 'Projet', 'action' => 'details', $tache->leProjet->idProjet]) ?>
              </td>
            </tr>
          <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
