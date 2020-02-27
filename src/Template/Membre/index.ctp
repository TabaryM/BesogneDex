<?php use Cake\Routing\Router; ?>

<?php // TODO: Ajouter une icône de recherche avec la ligne en dessous ?>
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
                    <p onClick="afficherGris(<?php echo $membre->idUtilisateur ?>)" id=<?php echo $membre->idUtilisateur ?> class='ligne_membre'>
                      <?= $membre->un_utilisateur->pseudo ?>
                    </p>
                <?php endforeach; ?>

            </div>
        </div>
    </div>
</div>
<?php $goto = Router::url(array('controller'=>'Membre', 'action'=> 'delete'));
?>
<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top: 50px;">
    <div class="col-xl-10 d-flex flex-row justify-content-between align-items-xl-center">
      <button class="btn btn-primary shadow grosBouton" type="button">Changer de propriétaire</button>
      <button id="bouton_supprimer_membre" class="btn btn-danger shadow grosBouton" onClick="<?= 'supprimer('.$id.')' ?>" type="button" >Supprimer</button>
      <button class="btn btn-primary shadow grosBouton boutonRouge" type="button">Retour</button></div>
</div>

<script>
  var local_source= '<?php echo Router::url(array('controller' => 'Utilisateur', 'action' => 'complete')); ?>';
  jQuery('#recherche-utilisateurs').autocomplete({
    source:local_source,
    minLength: 1
});
</script>
