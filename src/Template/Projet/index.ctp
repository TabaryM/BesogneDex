  <div class="row d-flex align-items-start" style="margin-right: 60px;margin-left: 60px;">
    <div class="col-xl-12" style="height: 80%;margin-top: 50px;">
      <div class="table-responsive">
        <table class="table table-bleu table-striped table-borderless">
          <thead class="thead-light">
            <tr>
              <th>Nom du projet</th>
              <th>Proprietaire</th>
              <th>Etat</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($projets as $projet): ?>
              <tr>
                <td>
                  <?= $this->Html->link($projet->titre, array('controller' => 'Tache', 'action'=> 'index', $projet->idProjet)); ?>
                </td>
                <td>
                  <?= $projet->un_utilisateur->pseudo; ?>
                </td>
                <td>
                  <?= $projet->etat ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="row d-flex justify-content-end" style="margin-right: 60px;margin-left: 60px;margin-top:60px;">
    <div class="col-xl-12 d-flex justify-content-end">
        <?=
          $this->Html->link("Projets archivés", array('controller' => 'Projet', 'action'=> 'archives'), array( 'class' => 'btn btn-primary grosBouton shadow'));
        ?>
        <?=
          $this->Html->link("Ajouter un projet", array('controller' => 'Projet', 'action'=> 'add'), array( 'class' => 'btn btn-primary grosBouton shadow'));
        ?>
    </div>
  </div>
