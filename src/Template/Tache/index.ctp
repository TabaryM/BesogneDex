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
                    <?= $this->Html->link($tache->titre, array('controller' => 'Tache', 'action'=> 'index', $idProjet));
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
                    <?= $this->Form->create('Tache' . $tache->idTache, ['url' => ['controller' => 'Tache', 'action' => 'finie', $idProjet, $tache->idTache], 'id' => 'Tache' . $tache->idTache]) ?>
                    <input type="checkbox" onclick="che(<?=$tache->idTache?>)">
                    <?= $this->Form->end(); ?>
                  </td>
                  <td class="text-center">
                    <div class="dropdown">
                      <a class="test" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">●●●</a>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <?php
                        if (isset ($user) && isset($tache->responsable) || isset($estProprietaire)) {
                            if($tache->idResponsable == $user || $estProprietaire) {
                                echo $this->Html->link("Supprimer la tâche", array('controller' => 'Tache', 'action'=> 'delete',$idProjet, $tache->idTache), array( 'class' => 'dropdown-item'));
                            }
                        }
                        ?>
                        <?php echo $this->Html->link("Modifier la tâche", array('controller' => 'Tache', 'action'=> 'edit', $idProjet), array( 'class' => 'dropdown-item'));?>
                        <?php
                        if (isset ($user) && isset($tache->responsable)) {
                            if($tache->idResponsable == $user) {
                                echo $this->Html->link("Se retirer de la tâche", array('controller' => 'Tache', 'action'=> 'notSoResponsible', $idProjet, $tache->idTache), array( 'class' => 'dropdown-item'));
                            }
                        } else {
                           echo $this->Html->link("Se proposer pour la tâche", array('controller' => 'Tache', 'action'=> 'devenirResponsable', $idProjet, $tache->idTache), array( 'class' => 'dropdown-item'));
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

<!-- Modal Supprimer une tâche : -->
<div class="modal fade" id="deleteModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                <div class="modal-body">
                    <p style="width: 477px;">Êtes-vous sûr de vouloir demander la suppression de cette tâche ?</p>
                </div>
                <div class="modal-footer text-center">
                    <div class="row text-center" style="width: 484px;">
                        <div class="col text-right">
                          <?php echo $this->Html->link("Non", array('controller' => 'Tache', 'action'=> 'index', $idProjet), array( 'button class' => 'btn btn-light', 'data-dismiss' => 'modal'));?>
                        </div>
                        <div class="col text-left">
                          <?php echo $this->Html->link("Oui", array('controller' => 'Tache', 'action'=> 'index', $idProjet), array( 'button class' => 'btn btn-danger', 'data-dismiss' => 'modal'));?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

      </div>
    </div>

<!-- Boutons : -->
    <div class="row" style="margin-right: 60px;margin-left: 60px;">
    <div class="col-xl-5 col-md-5">
        <div class="card color-card">
            <div class="card-body shadow d-flex justify-content-between align-items-center color-card">
              <?= $this->Html->image("icones/membres.png", ['class' => 'image_icone']) ?>
              <?= $this->Html->link("Détails du projet", array('controller' => 'Tache', 'action'=> 'details', $idProjet), array( 'class' => 'btn btn-primary shadow')); ?>
              <?php if($estProprietaire): ?>
                <?= $this->Html->link("Gérer les membres", array('controller' => 'Membre', 'action'=> 'index', $idProjet), array( 'class' => 'btn btn-primary shadow')); ?>
              <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-5 col-md-4">
        <div class="card color-card">
            <div class="card-body shadow d-flex justify-content-between align-items-center color-card">
              <?= $this->Html->image("icones/list.png", ['class' => 'image_icone']) ?>
              <?php if($estProprietaire): ?>
                <?= $this->Html->link("Archiver", ['controller' => 'Projet', 'action' => 'archive', $idProjet], ['class' => 'btn btn-primary shadow']); ?>
                <?= $this->Html->link("Modifier", ['controller' => 'Projet', 'action' => 'edit', $idProjet], ['class' => 'btn btn-primary shadow']); ?>
                <?= $this->Html->link("Supprimer", ['controller' => 'Projet', 'action' => 'delete', $idProjet], ['class' => 'btn btn-danger shadow']); ?>
              <?php else: ?>
                <?= $this->Html->link("Quitter le projet", ['controller' => 'Projet', 'action'=> 'delete', $idProjet], ['class' => 'btn btn-danger shadow']); ?>
              <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-2 col-md-2 d-flex justify-content-end align-items-center">
      <?= $this->Html->link("", ['controller' => 'Tache', 'action'=> 'add', $id], ['class' => 'btn btn-primary shadow rond-croix']); ?>
    </div>
  </div>

  <?= $this->Html->script('tacheTermine.js'); ?>

  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
