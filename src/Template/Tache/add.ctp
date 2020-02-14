<h1> Ici c'est pour ajouter une tâche ! </h1>
<?php
    echo $this->Form->create('Tache');
    echo $this->Form->input('titre', array('label' => 'Titre de la tâche :'));
    echo $this->Form->input('description', array('label' => 'Description :'));
    echo $this->Form->submit('Créer');
?>
