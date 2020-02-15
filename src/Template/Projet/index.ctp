<div class="container" style="margin-top: 20px">
  <div class="row d-flex align-items-start" style="height: 100%;">
    <div class="col-xl-12" style="height: 80%;">
      <div class="table-responsive">
        <table class="table table-bordered">
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
                  <?php
                  echo $this->Html->link($projet->titre, array('controller' => 'Tache', 'action'=> 'index', 'id'=>$projet->idProjet));
                  ?>
                </td>
                <td>
                  <?= $projet->bindingKey ?>
                </td>
                <td>
                  <?= $projet->Etat ?>
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
        echo $this->Html->link("Projets archivés", array('controller' => 'Projet', 'action'=> 'archives'), array( 'class' => 'btn btn-primary'));
        ?>
        <?php
        echo $this->Html->link("Ajouter un projet", array('controller' => 'Projet', 'action'=> 'add'), array( 'class' => 'btn btn-primary'));
        ?>
    </div>
  </div>
</div>
