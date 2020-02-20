<!-- Pour faire l'auto-complétion plus tard : http://www.naidim.org/cakephp-3-tutorial-18-autocomplete -->

<?php use Cake\Routing\Router; ?>

</br>
<?= $this->Form->create() ?>
<?= $this->Form->control('recherche_utilisateurs', ['placeholder' => 'Rechercher un membre...', 'label'=> '', 'style'=>'margin-left: 20px;', 'type'=>'text']) ?>
<!-- <?php //Router::url(array('controller' => 'Utilisateur', 'action' => 'complete')); ?> -->
<script>
  var local_source= ['a', 'b','c', 'chocolat', 'cacaco', 'azert'];

// C'est bien recherche-utilisateurs et non recherche_utilisateurs pour l'id d'après le code source
    $('#recherche-utilisateurs').autocomplete({
        source:local_source,
        minLength: 1
    });
  });
</script>

</br>
<?= $this->Form->submit('Inviter', ['class'=>'btn shadow', 'style'=>'height: 40%;background-color: #b6d7a8;color: rgb(0,0,0);margin-left: 40px;'])?>

</br>
<h4> Liste des membres </h4>
<?php foreach ($membres as $membre): ?>
<?= $membre->un_utilisateur->pseudo ?>
<?php endforeach; ?>
