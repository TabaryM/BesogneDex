<!-- Auteur : Pedro -->
<div style="height: 80vh;margin-top: 20px;">
  <div class="container">
    <div class="row d-flex align-items-start" style="height: 100%;">
      <div class="col-xl-12" style="height: 80%;">
        <div class="table-responsive">
          <table class="table table-blue table-striped table-borderless">
            <thead class="thead-light">
              <tr>
                <th>Tâche</th>
                <th>Fait ?</th>
                <th>Date de fin projet</th>
                <th>Projet</th>
              </tr>
            </thead>
            <tbody>

              <?php foreach ($taches as $tache): ?>
                <tr>
                  <td>
                    <?= $tache->titre ?>
                  </td>
                  <td>
                    <input type="checkbox" name="<?=$tache->idTache?>" checked>
                  </td>
                  <td>
                    <?= $tache->leProjet->dateFin ?>
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
  </div>
</div>