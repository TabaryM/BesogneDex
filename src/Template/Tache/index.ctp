<!-- Auteur : Thibault CHONÉ - Valérie MARISSENS - Adrien PALMIERI -->

   <!-- Début modification header -->
   <?php
    use Cake\Core\Configure;

    Configure::write('titre_header_tache',$projetTab->titre);
   ?>
   <!-- Fin modification du header -->

   <!-- Début table -->
    <div class="row d-flex align-items-start" style="margin-left:60px;margin-right:60px;margin-top:20px;">
      <div class="col-xl-12" style="height: 80%;">
        <div class="table-responsive">
          <table class="table table-borderless table-green">

            <!-- Début header du tableau avec informations du projet -->
            <thead class="thead-light">
              <?php
              if($estProprietaire){
                echo "Vous êtes propriétaire de ce projet.</br>";
              }

              if(isset($projetTab->dateDebut) && !empty($projetTab->dateDebut)){
                echo '<strong>Date debut : </strong>';
                echo $projetTab->dateDebut->nice('Europe/Paris', 'fr-FR');
              }
              ?>
              <?php
              if(isset($projetTab->dateFin) && !empty($projetTab->dateFin)){
                echo '<strong>Date fin : </strong>';
                echo $projetTab->dateFin->nice('Europe/Paris', 'fr-FR');
              }
              ?>
              <tr>
                <th class="text-center" style="width: 467px;">Tâche</th>
                <th class="text-center" style="width: 238px;">Attribuée à</th>
                <th class="text-center" style="width: 105px;">Fait ?</th>
                <th class="text-center" style="width: 194px;">Actions</th>
              </tr>
            </thead>
            <!-- Fin header du tableau avec informations du projet -->


            <tbody>
              <?php
                foreach ($taches as $tache):
                 ?>
                <tr style="height: 50px;">
                  <!-- Début Tâche -->
                  <td>
                    <?= $this->Html->link($tache->titre, array('controller' => 'Tache'), array('data-toggle' => 'modal', 'data-target' => '#descriptionModal' . $tache->idTache));
                    ?>
                  </td>
                  <!-- Fin Tâche -->

                  <!-- Début Attribuée à -->
                  <td class="text-center">
                    <?php
                    if(isset($tache->responsable->pseudo)){
                      echo $tache->responsable->pseudo;
                    }else{
                      echo '--';
                    }
                    ?>
                  </td>
                  <!-- Fin Attribuée à -->

                  <!-- Début  Fait? avec checkbox -->
                  <td class="text-center">
                    <?= $this->Form->create('Tache' . $tache->idTache, ['url' => ['controller' => 'Tache', 'action' => 'finie', $tache->idTache], 'id' => 'Tache' . $tache->idTache]) ?>
                    <input type="checkbox" class="checkFait" value="<?= $tache->idTache ?>"
                      <?php if ($tache->finie) echo "checked"; ?>
                      <?php if ($tache->idResponsable !== $user) echo "disabled"; ?>
                    >
                    <?= $this->Form->end(); ?>
                  </td>
                  <!-- Fin Fait? avec checkbox -->

                  <!-- Début DropdownMenu Actions avec boutons Supprimer la tâche, Modifier la tâche, Se retirer/proposer pour la tâche -->
                  <td class="text-center">
                    <div class="dropdown">
                      <a class="test" href="#" role="button" id="dropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">●●●</a>
                      <div class="dropdown-menu" aria-labelledby="dropdownMenuLink">
                        <?php echo $this->Html->link("Supprimer la tâche", array('controller' => 'Tache', 'action'=> 'index', $idProjet), array('class' => 'dropdown-item', 'data-toggle' => 'modal', 'data-target' => '#deleteModal' . $tache->idTache)); ?>
                        <?php echo $this->Html->link("Modifier la tâche", array('controller' => 'Tache', 'action'=> 'edit', $idProjet, $tache->idTache), array( 'class' => 'dropdown-item'));?>
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
                <!-- Fin DropdownMenu Actions avec boutons Supprimer la tâche, Modifier la tâche, Se retirer/proposer pour la tâche -->

                <!-- Début modal Supprimer une tâche : -->
                <div class="modal fade" id=<?php echo "deleteModal" . $tache->idTache ?> role="dialog" tabindex="-1">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title"></h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button>
                                    </div>
                                    <div class="modal-body" style="text-align:center;">
                                    <p style="width: 477px;">Êtes-vous sûr de vouloir supprimer cette tâche ?
                                    </div>

                                <div class="modal-footer text-center">
                                    <div class="row text-center" style="width: 484px;">
                                        <div class="col text-right">
                                          <?php echo $this->Html->link("Non", array('controller' => 'Tache', 'action'=> 'index', $idProjet), array( 'button class' => 'btn btn-primary', 'data-dismiss' => 'modal'));?>
                                        </div>
                                        <div class="col text-left">
                                          <?php echo $this->Html->link("Oui", array('controller' => 'Tache', 'action'=> 'delete', $idProjet, $tache->idTache), array( 'button class' => 'btn btn-danger'));?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <!-- Fin modal Supprimer une tâche -->

                <!-- Début modal Description d'une tâche -->
                <div class="modal fade" id=<?php echo "descriptionModal" . $tache->idTache ?> role="dialog" tabindex="-1">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">
                                      <?php echo $tache->titre; ?>
                                    </h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                                <div class="modal-body" style="text-align:center;">
                                    <?php echo $tache->description; ?>
                                </div>
                                <div class="modal-footer text-center">
                                    <div class="row text-center" style="width: 484px;">
                                        <div class="col text-right">
                                          <?php echo $this->Html->link("Ok", array('controller' => 'Tache', 'action'=> 'index', $idProjet), array( 'button class' => 'btn btn-primary', 'data-dismiss' => 'modal'));?>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                </div>
                <!-- Fin modal Description d'une tâche -->

              <?php endforeach;  ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <!-- Fin table -->

    <!-- Début boutons Détails du projet, Gérer les membres -->
    <div class="row" style="margin-right: 60px;margin-left: 60px;">
    <div class="col-xl-4 col-md-4 col-sm-4">
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
    <!-- Fin boutons Détails du projet, Gérer les membres -->

    <!-- Début boutons Archiver, Modifier, Supprimer, Quitter le projet, Ajouter une tâche -->
    <div class="col-xl-4">
        <div class="card color-card">
            <div class="card-body shadow d-flex justify-content-between align-items-center color-card">
              <?= $this->Html->image("icones/list.png", ['class' => 'image_icone']) ?>
              <?php if($estProprietaire): ?>
                <?= $this->Html->link("Archiver", ['controller' => 'Projet', 'action' => 'archive', $idProjet], ['class' => 'btn btn-primary shadow']); ?>
                <?= $this->Html->link("Modifier", ['controller' => 'Projet', 'action' => 'edit', $idProjet], ['class' => 'btn btn-primary shadow']); ?>
                <?= $this->Html->link("Supprimer", "", ['class' => 'btn btn-danger shadow', 'data-toggle' => 'modal', 'data-target' => '#leaveModal']); ?>
              <?php else: ?>
                <?= $this->Html->link("Quitter le projet", "", ['class' => 'btn btn-danger shadow', 'data-toggle' => 'modal', 'data-target' => '#leaveModal']); ?>
              <?php endif; ?>
            </div>
        </div>
    </div>
    <div class="col-xl-4 d-flex justify-content-end align-items-center">
      <?= $this->Html->link("", ['controller' => 'Tache', 'action'=> 'add', $idProjet], ['class' => 'btn btn-primary shadow rond-croix']); ?>
    </div>
  </div>
  <!-- Fin boutons Archiver, Modifier, Supprimer, Quitter le projet, Ajouter une tâche -->

  <!-- Début odal Quitter/Supprimer le projet : -->
  <div class="modal fade" id="leaveModal" role="dialog" tabindex="-1">
          <div class="modal-dialog" role="document">
              <div class="modal-content">
                  <div class="modal-header">
                      <h4 class="modal-title"></h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                  <div class="modal-body" style="text-align:center;">
                      <?php if($estProprietaire):?>
                      <p style="width: 477px;">Voulez-vous vraiment supprimer le projet ?</p>
                    <?php else: ?>
                      <p style="width: 477px;">Voulez-vous vraiment quitter le projet ?</p>
                    <?php endif; ?>
                  </div>
                  <div class="modal-footer text-center">
                      <div class="row text-center" style="width: 484px;">
                          <div class="col text-right">
                            <?php echo $this->Html->link("Non", array('controller' => 'Tache', 'action'=> 'index', $idProjet), array( 'button class' => 'btn btn-primary', 'data-dismiss' => 'modal'));?>
                          </div>
                          <div class="col text-left">
                            <?php echo $this->Html->link("Oui", array('controller' => 'Projet', 'action'=> 'delete', $idProjet), array( 'button class' => 'btn btn-danger'));?>
                          </div>
                      </div>
                  </div>
              </div>
          </div>
  </div>
  <!-- Fin modal Quitter/Supprimer le projet -->


  <script src="assets/js/jquery.min.js"></script>
  <script src="assets/bootstrap/js/bootstrap.min.js"></script>
