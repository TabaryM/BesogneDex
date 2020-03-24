<!-- Début modification header -->
  <?php
    use Cake\Core\Configure;
    Configure::write('titre_header_tache', $titreProjet);
  ?>
<!-- Fin modification header -->

<?php use Cake\Routing\Router;
// TODO: Ajouter une icône de recherche avec la ligne en dessous
?>

<?= $this->Html->script('membres.js') ?>

 <?= $this->Form->create(null, ['url' => ['controller' => 'Membre', 'action' => 'add', $idProjet]] ); ?>
<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top: 50px;">
    <div class="col-xl-3 d-flex justify-content-start align-items-center align-content-center"><?= $this->Form->control('recherche_utilisateurs', ['placeholder' => 'Rechercher un membre...', 'label'=> '', 'type'=>'text']) ?> <?= $this->Form->submit('Inviter', array('class'=>'btn btn-primary shadow boutonVert', 'style' => 'width:200px;margin-left:20px;'));?></div>
</div>
<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top:30px;">
    <div class="col-xl-8"><label>Liste des membres :</label>
        <div class="card shadow">
            <div class="card-body">
                <?php foreach ($membres as $membre): ?>
                    <p onClick="afficherGris(<?= $membre->idUtilisateur ?>)" id=<?= $membre->idUtilisateur ?> class='ligne_membre'>
                      <?= $membre->un_utilisateur->pseudo ?>
                    </p>
                <?php endforeach; ?>
                <?php foreach ($invites as $membreInvite): ?>
                    <p class='ligne_membre'>
                      <?= $membreInvite->pseudo . " (En attente de réponse)" ?>
                    </p>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
</div>
<?php $goto = Router::url(array('controller'=>'Membre', 'action'=> 'delete')); ?>
<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top: 50px;">
    <div class="col-xl-10 d-flex flex-row justify-content-between align-items-xl-center">
      <?= $this->Html->link("Changer de propriétaire", "", ['class' => 'btn btn-primary shadow grosBouton boutonRouge', 'data-toggle' => 'modal', 'data-target' => '#promoteModal']); ?>
      <button id="bouton_supprimer_membre" class="btn btn-danger shadow grosBouton" type="button" data-toggle="modal" data-target="#deleteMembreModal" >Supprimer</button>
      <?= $this->Html->link("Retour", array('controller' => 'Tache', 'action'=> 'index', $idProjet), array( 'class' => 'btn btn-primary shadow grosBouton boutonRouge')); ?>
    </div>
</div>

<script>
  var local_source= '<?= Router::url(array('controller' => 'Utilisateur', 'action' => 'complete')); ?>';
  jQuery('#recherche-utilisateurs').autocomplete({
    source:local_source,
    minLength: 1
});
</script>

<!-- Modal Passer ses droits sur le projet : -->
<div class="modal fade" id="promoteModal" role="dialog" tabindex="-1">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
                <div class="modal-body" style="text-align:center;">
                    <p style="width: 477px;">Voulez-vous passer vos droits ?</p>
                </div>
                <div class="modal-footer text-center">
                    <div class="row text-center" style="width: 484px;">
                        <div class="col text-right">
                          <?= $this->Html->link("Non", array('controller' => 'Tache', 'action'=> 'index', $idProjet), array('button class' => 'btn btn-primary', 'data-dismiss' => 'modal'));?>
                        </div>
                        <div class="col text-left">
                          <button id="bouton_changer_proprietaire" class="btn btn-danger" onClick="<?= 'changerProprietaire('.$idProjet.')' ?>" type="button" >Oui</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
</div>
<!-- Fin Modal Passer ses droits sur le projet : -->

<!-- Modal supprimer un membre : -->
<div class="modal fade" id="deleteMembreModal" role="dialog" tabindex="-1">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4><button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">×</span></button></div>
        <div class="modal-body" style="text-align:center;">
          <p style="width: 477px;">Voulez-vous vraiment supprimer ce membre du projet ?</p>
        </div>
        <div class="modal-footer text-center">
          <div class="row text-center" style="width: 484px;">
            <div class="col text-right">
              <?php echo $this->Html->link("Non", array('controller' => 'Membre', 'action'=> 'index', $idProjet), array( 'button class' => 'btn btn-primary', 'data-dismiss' => 'modal'));?>
            </div>
            <div class="col text-left">
              <button id="bouton_supprimer_membre_modal" class="btn btn-danger" onClick="<?= 'supprimer('.$idProjet.')' ?>" type="button" >Oui</button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
<!-- Fin Modal supprimer un membre : -->
