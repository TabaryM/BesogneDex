
<!-- Auteur : Thibault -->
<div style="height: 80vh;margin-top: 20px;">
  <div class="container">
    <div class="row d-flex align-items-start" style="height: 100%;">
      <div class="col-xl-12" style="height: 80%;">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="thead-light">
              <?php
                if(isset($projetTab->dateDebut) && !empty($projetTab->dateDebut)){
                  echo 'Date debut : ';
                  echo date('Y-m-d H:i:s', strtotime($projetTab->dateDebut));
                }
                ?>
                <?php
                if(isset($projetTab->dateFin) && !empty($projetTab->dateFin)){
                  echo 'Date fin : ';
                  echo date('Y-m-d H:i:s', strtotime($projetTab->dateFin)); //TODO:changer format affichage heure
                }
                ?>
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
                    <?php
                    if(isset($tache->responsable->pseudo)){
                        echo $tache->responsable->pseudo;
                    }else{
                      echo 'None';
                    }
                    ?>
                  </td>
                  <td>
                    <input type="checkbox" name="<?=$tache->idTache?>" checked>
                  </td>
                  <td>
                    <?php
                    //TODO: faire la liste déroulante
                    echo $this->Html->link("Test1", array('controller' => 'Tache', 'action'=> 'index', $id), array( 'class' => 'btn btn-primary'));?>
                    <?php
                    echo $this->Html->link("Test2", array('controller' => 'Tache', 'action'=> 'index', $id), array( 'class' => 'btn btn-primary'));?>
                    <?php
                    echo $this->Html->link("Test3", array('controller' => 'Tache', 'action'=> 'index', $id), array( 'class' => 'btn btn-primary'));?>
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
        echo $this->Html->link("Gérer les membres", array('controller' => 'Tache', 'action'=> 'manageMembers', $id), array( 'class' => 'btn btn-primary'));
        ?>
          <?php
          echo $this->Html->link("Projets", array('controller' => 'Projet', 'action'=> 'index'), array( 'class' => 'btn btn-primary'));
        ?>
        <?php
        echo $this->Html->link("Détails", array('controller' => 'Tache', 'action'=> 'details', $id), array( 'class' => 'btn btn-primary'));
        ?>
        <?php
        echo $this->Html->link("Ajouter une tâche", array('controller' => 'Tache', 'action'=> 'add', $id), array( 'class' => 'btn btn-primary'));
        ?>
  </div>
</div>
