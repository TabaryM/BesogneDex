<div class="container" style="margin-top: 20px">
  <div class="row d-flex align-items-start" style="height: 100%;">
    <div class="col-xl-12" style="height: 80%;">
      <div class="table-responsive">
        <table class="table table-bleu table-striped table-borderless">
          <thead class="thead-light">
            <tr>
              <th>Nom du projet</th>
              <th>Date de fin</th>
              <th>Date d'archivage</th>
            </tr>
          </thead>
          <tbody>
            <?php foreach ($archives as $archive): ?>
              <tr>
                <td>
                  <?php
                  echo $this->Html->link($archive->titre, array('controller' => 'Tache', 'action'=> 'index', $archive->idProjet));
                  ?>
                </td>
                <td>
                  <?= $archive->dateFin; ?>
                </td>
                <td>
                  <?= null; ?>
                </td>
              </tr>
            <?php endforeach; ?>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  <div class="row d-flex align-items-start" >
    <div class="col-xl-12">
        <?php
        echo $this->Html->link("Retour", array('controller' => 'Projet', 'action'=> 'index'), array( 'class' => 'btn btn-primary'));
        ?>
    </div>
  </div>
</div>
