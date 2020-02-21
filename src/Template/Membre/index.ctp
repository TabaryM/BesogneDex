<!-- Pour faire l'auto-complétion plus tard : http://www.naidim.org/cakephp-3-tutorial-18-autocomplete -->

<?php use Cake\Routing\Router; ?>

</br>
<?= $this->Form->create(null, ['url' => ['controller' => 'Membre', 'action' => 'add', $id]] ); ?>
<?= $this->Form->control('recherche_utilisateurs', ['placeholder' => 'Rechercher un membre...', 'label'=> '', 'style'=>'margin-left: 20px;', 'type'=>'text']) ?>

<script>
  var local_source= '<?php echo Router::url(array('controller' => 'Utilisateur', 'action' => 'complete')); ?>';
  jQuery('#recherche-utilisateurs').autocomplete({
    source:local_source,
    minLength: 1
});
</script>

</br>
<?= $this->Form->submit('Inviter', array('class'=>'btn shadow', 'style'=>'height: 40%;background-color: #b6d7a8;color: rgb(0,0,0);margin-left: 40px;'));?>

</br>
<h4> Liste des membres </h4>
<?php foreach ($membres as $membre): ?>
<?= $membre->un_utilisateur->pseudo ?>
</br>
<?php endforeach;

 ?>
