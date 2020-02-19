
<!-- Auteur : Thibault CHONÉ -->
<div style="height: 80vh;margin-top: 20px;">
  <div class="container">
    <div class="row d-flex align-items-start" style="height: 100%;">
      <div class="col-xl-12" style="height: 80%;">
        <div class="table-responsive">
          <table class="table table-borderless table-green">
            <thead class="thead-light">
              <?php
              if($estProprietaire){
                echo "Vous êtes le propriétaire du projet.</br>";
              }

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
                    <?= $this->Html->link($tache->titre, array('controller' => 'Tache', 'action'=> 'index', $id));
                    ?>
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
                    <input type="checkbox" name="<?=$tache->idTache?>">
                  </td>
                  <td>
                    <?php
                    //TODO: faire la liste déroulante
                    echo $this->Html->link("Supprimer la tâche", array('controller' => 'Tache', 'action'=> 'index', $id), array( 'class' => 'btn btn-primary'));?>
                    <?php
                    echo $this->Html->link("Modifier la tâche", array('controller' => 'Tache', 'action'=> 'index', $id), array( 'class' => 'btn btn-primary'));?>
                    <?php
                    echo $this->Html->link("Se proposer pour la tâche", array('controller' => 'Tache', 'action'=> 'index', $id), array( 'class' => 'btn btn-primary'));?>
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
        <?php
        echo $this->Html->link("Détails", array('controller' => 'Tache', 'action'=> 'details', $id), array( 'class' => 'btn btn-primary'));
        ?>
        <?php
        if($estProprietaire){
          echo $this->Html->link("Gérer les membres", array('controller' => 'Tache', 'action'=> 'manageMembers', $id), array( 'class' => 'btn btn-primary'));
          echo '        ';
          echo $this->Html->link("Modifier", array('controller' => 'Tache', 'action'=> 'edit', $id), array( 'class' => 'btn btn-primary'));
        }
        ?>
        <?php
        echo $this->Html->link("Quitter le projet", array('controller' => 'Projet', 'action'=> 'delete', $id), array( 'class' => 'btn btn-danger'));
        ?>
        <?php
        echo $this->Html->link("Ajouter une tâche", array('controller' => 'Tache', 'action'=> 'add', $id), array( 'class' => 'btn btn-primary'));
        ?>
      </div>
    </div>
  </div>
</div>
