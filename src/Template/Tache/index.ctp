<?php
  use Cake\Core\Configure;

  Configure::write('titre_header_tache',$projetTab->titre);
 ?>
<!-- Auteur : Thibault CHONÉ - Valérie MARISSENS -->
    <div class="row d-flex align-items-start" style="margin-left:60px;margin-right:60px;margin-top:20px;">
      <div class="col-xl-12" style="height: 80%;">
        <div class="table-responsive">
          <table class="table table-borderless table-green">
            <thead class="thead-light">
              <?php
              if($estProprietaire){
                echo "Vous êtes propriétaire de ce projet.</br>";
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
                <th class="text-center" style="width: 467px;">Tâche</th>
                <th class="text-center" style="width: 238px;">Attribuée à</th>
                <th class="text-center" style="width: 105px;">Fait ?</th>
                <th class="text-center" style="width: 194px;">Actions</th>
              </tr>
            </thead>
            <tbody>
              <?php
                // Compteur utilisé pour chaque liste déroulante.
                $cpt = 1;
                foreach ($taches as $tache): ?>

                <tr style="height: 50px;">
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
                  <td class="text-center">
                    <input type="checkbox" name="<?=$tache->idTache?>">
                  </td>
                  <td>
                    <div class="text-center"><a class="btn btn-dark" data-toggle="collapse" aria-expanded="false" aria-controls="collapse-<?php echo $cpt; ?>" href="#collapse-<?php echo $cpt; ?>" role="button"></a>
                      <div class="collapse" id="collapse-<?php echo $cpt; $cpt++; ?>">
                        <?php
                        echo $this->Html->link("Supprimer la tâche", array('controller' => 'Tache', 'action'=> 'index', $id), array( 'class' => 'btn btn-primary bg-dark border-dark'));?>
                        <?php
                        echo $this->Html->link("Modifier la tâche", array('controller' => 'Tache', 'action'=> 'index', $id), array( 'class' => 'btn btn-primary bg-dark border-dark'));?>
                        <?php
                        echo $this->Html->link("Se proposer pour la tâche", array('controller' => 'Tache', 'action'=> 'devenirResponsable', $id, $tache->idTache), array( 'class' => 'btn btn-primary bg-dark border-dark'));?>
                      </div>
                    </div>
                  </td>
                </tr>
              <?php endforeach; ?>
              <!-- Pour que la table ne soit pas vide. -->
              <tr style="height: 50px;">
                <td>  </td>
                <td>  </td>
                <td>  </td>
                <td>  </td>
              </tr>
              <tr style="height: 50px;">
                <td>  </td>
                <td>  </td>
                <td>  </td>
                <td>  </td>
              </tr>
              <tr style="height: 50px;">
                <td>  </td>
                <td>  </td>
                <td>  </td>
                <td>  </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Boutons -->
    <div class="row d-flex align-items-start" style="margin-left:60px;margin-right:60px;">
      <!-- Membres -->
      <div class="col-xl-12">
        <?= $this->Html->image("icones/membres.png", ['class' => 'image_icone']) ?>
        <?php
          echo $this->Html->link("Retour", array('controller' => 'Projet', 'action'=> 'index'), array( 'class' => 'btn btn-primary'));
          ?>
        <?php
        echo $this->Html->link("Détails du projet", array('controller' => 'Tache', 'action'=> 'details', $id), array( 'class' => 'btn btn-primary'));
        ?>
        <?php
        if($estProprietaire){
          echo $this->Html->link("Gérer les membres", array('controller' => 'Membre', 'action'=> 'index', $id), array( 'class' => 'btn btn-primary'));
          echo '        ';
          echo $this->Html->link("Modifier", array('controller' => 'Tache', 'action'=> 'edit', $id), array( 'class' => 'btn btn-primary'));
        }
        ?>

        <!-- Autres options -->
        <?= $this->Html->image("icones/list.png", ['class' => 'image_icone']) ?>
        <?php
        echo $this->Html->link("Quitter le projet", array('controller' => 'Projet', 'action'=> 'delete', $id), array( 'class' => 'btn btn-danger'));
        ?>
        <?php
        echo $this->Html->link("Ajouter une tâche", array('controller' => 'Tache', 'action'=> 'add', $id), array( 'class' => 'btn btn-primary'));
        ?>
      </div>
    </div>
    <script src="assets/js/jquery.min.js"></script>
    <script src="assets/bootstrap/js/bootstrap.min.js"></script>
