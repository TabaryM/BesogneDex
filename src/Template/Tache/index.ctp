
<!-- Auteur : Thibault -->
<div style="height: 80vh;margin-top: 20px;">
  <div class="container">
    <div class="row d-flex align-items-start" style="height: 100%;">
      <div class="col-xl-12" style="height: 80%;">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="thead-light">
              Date debut :  <?php echo '10/10/2010';//TODO: garder en mémoire le projet avec ses informations?> -
              Date fin : <?php echo '10/10/2010'; ?>
              <tr>
                <th>Tâche</th>
                <th>Attribuée à</th>
                <th>Fait ?</th>
                <th>Actions</th>
              </tr>
            </thead>
            <tbody>

              <?php foreach ($taches as $tache): ?>
                <tr>
                  <td>
                    <?= $tache->titre ?>
                  </td>
                  <td>
                    <?=
                    $tache->responsable//->pseudo //TODO: faire fonctionner?>
                  </td>
                  <td>
                    <input type="checkbox" name="<?=$tache->idTache?>" checked>
                  </td>
                  <td>
                    <?php
                    //TODO: faire la liste déroulante
                    echo $this->Html->link("Test1", array('controller' => 'Tache', 'action'=> 'index', 'id'=>$id), array( 'class' => 'btn btn-primary'));?>
                    <?php
                    echo $this->Html->link("Test2", array('controller' => 'Tache', 'action'=> 'index', 'id'=>$id), array( 'class' => 'btn btn-primary'));?>
                    <?php
                    echo $this->Html->link("Test3", array('controller' => 'Tache', 'action'=> 'index', 'id'=>$id), array( 'class' => 'btn btn-primary'));?>
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
        echo $this->Html->link("Gérer les membres", array('controller' => 'Tache', 'action'=> 'manageMembers', 'id' => $id), array( 'class' => 'btn btn-primary'));
        ?>
          <?php
          echo $this->Html->link("Projets", array('controller' => 'Projet', 'action'=> 'index'), array( 'class' => 'btn btn-primary'));
        ?>
        <?php
        echo $this->Html->link("Détails", array('controller' => 'Tache', 'action'=> 'details', 'id'=>$id), array( 'class' => 'btn btn-primary'));
        ?>
        <?php
        echo $this->Html->link("Ajouter une tâche", array('controller' => 'Tache', 'action'=> 'add', 'id' => $id), array( 'class' => 'btn btn-primary'));
        ?>
  </div>
</div>
