<h1>Liste de projets</h1>

<table>
    <tr>
        <th>Nom</th>
        <th>Description</th>
    </tr>

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
  echo $this->Html->link("Ajouter un projet", array('controller' => 'Projet', 'action'=> 'add'), array( 'class' => 'button'));
?>
