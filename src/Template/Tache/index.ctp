<?php
  use Cake\Core\Configure;

  Configure::write('titre_header_tache',$projetTab->titre);
 ?>
<!-- Auteur : Thibault CHONÉ - Valérie MARISSENS - Adrien PALMIERI -->
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
                foreach ($taches as $tache):
                 ?>

                <tr style="height: 50px;">
                  <td>
                    <?= $this->Html->link($tache->titre, array('controller' => 'Tache', 'action'=> 'index', $id));
                    ?>
                  </td>
                  <td class="text-center">
                    <?php
                    if(isset($tache->responsable->pseudo)){
                      echo $tache->responsable->pseudo;
                    }else{
                      echo '--';
                    }
                    ?>
                  </td>
                  <td class="text-center">
                    <?= $this->Form->create('Tache' . $tache->idTache, ['url' => ['controller' => 'Tache', 'action' => 'finie', $tache->idTache], 'id' => 'Tache' . $tache->idTache]) ?>
                    <input type="checkbox" onclick="che(<?=$tache->idTache?>)">
                    <?= $this->Form->end(); ?>
                  </td>
                  <td class="text-center">
                    <div class="dropdown">
                      <a class="test" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">●●●</a>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <?php echo $this->Html->link("Supprimer la tâche", array('controller' => 'Tache', 'action'=> 'delete', $id), array( 'class' => 'dropdown-item')); ?>
                        <?php echo $this->Html->link("Modifier la tâche", array('controller' => 'Tache', 'action'=> 'edit', $id), array( 'class' => 'dropdown-item'));?>
                        <?php
                        if (isset ($user) && isset($tache->responsable)) {
                            if($tache->idResponsable == $user) {
                                echo $this->Html->link("Se retirer de la tâche", array('controller' => 'Tache', 'action'=> 'notSoResponsible', $id, $tache->idTache), array( 'class' => 'dropdown-item'));
                            }
                        } else {
                           echo $this->Html->link("Se proposer pour la tâche", array('controller' => 'Tache', 'action'=> 'devenirResponsable', $id, $tache->idTache), array( 'class' => 'dropdown-item'));
                        }
                        ?>
                         </div>
                    </div>
                  </td>
                </tr>
              <?php endforeach;  ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>


      </div>
    </div>
    <div class="row" style="margin-right: 60px;margin-left: 60px;">
    <div class="col-xl-4">
        <div class="card color-card">
            <div class="card-body shadow d-flex justify-content-between align-items-center color-card">
              <?= $this->Html->image("icones/membres.png", ['class' => 'image_icone']) ?>
              <?php
              echo $this->Html->link("Détails du projet", array('controller' => 'Tache', 'action'=> 'details', $id), array( 'class' => 'btn btn-primary shadow'));
              ?>
              <?php
              if($estProprietaire){
                echo $this->Html->link("Gérer les membres", array('controller' => 'Membre', 'action'=> 'index', $id), array( 'class' => 'btn btn-primary shadow'));
              }
              ?>
            </div>
        </div>
    </div>
    <div class="col-xl-3">
        <div class="card color-card">
            <div class="card-body shadow d-flex justify-content-between align-items-center color-card">
              <?= $this->Html->image("icones/list.png", ['class' => 'image_icone']) ?>
              <?php
              if($estProprietaire){
                echo $this->Html->link("Modifier", array('controller' => 'Projet', 'action'=> 'edit', $id), array( 'class' => 'btn btn-primary shadow'));
                echo $this->Html->link("Supprimer", array('controller' => 'Projet', 'action'=> 'delete', $id), array( 'class' => 'btn btn-danger shadow'));
              } else {
                echo $this->Html->link("Quitter le projet", array('controller' => 'Projet', 'action'=> 'delete', $id), array( 'class' => 'btn btn-danger shadow'));
              }
              ?>
            </div>
        </div>
    </div>
    <div class="col-xl-5 d-flex justify-content-end align-items-center"><?php echo $this->Html->link("", array('controller' => 'Tache', 'action'=> 'add', $id), array( 'class' => 'btn btn-primary shadow rond-croix')); ?></div>
  </div>

  <?= $this->Html->script('tacheTermine.js'); ?>
