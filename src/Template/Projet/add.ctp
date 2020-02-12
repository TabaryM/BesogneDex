<h1> Ici c'est pour ajouter un projet ! </h1>
<?php
    echo $this->Form->create('Projet');
    echo $this->Form->input('titre', array('label' => 'Titre du projet :'));
    echo $this->Form->input('description', array('label' => 'Description :'));
    echo $this->Form->submit('Ajouter');
?>
