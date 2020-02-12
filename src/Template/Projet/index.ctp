<!-- Fichier : src/Template/Articles/index.ctp -->

<h1>Liste de projets</h1>
<table>
    <tr>
        <th>Nom</th>
        <th>Description</th>
    </tr>

    <!-- C'est ici que nous bouclons sur notre objet Query $articles pour afficher les informations de chaque article -->

    <?php foreach ($projets as $projet): ?>
    <tr>
        <td>
            <?=   $projet->titre ?>
        </td>
        <td>
          <?=     $projet->description ?>
        </td>
    </tr>
    <?php endforeach; ?>

</table>

<?php
  echo $this->Html->link("Ajouter un projet", array('controller' => 'Projet', 'action'=> 'add'), array( 'class' => 'button'))
?>
