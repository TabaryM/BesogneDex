</br>
<?= $this->Form->create() ?>
<?= $this->Form->control('pseudo', ['placeholder' => 'Rechercher un membre...', 'label'=> '', 'style'=>'margin-left: 20px;']) ?>
</br>
<?= $this->Form->submit('Inviter', ['class'=>'btn shadow', 'style'=>'height: 40%;background-color: #b6d7a8;color: rgb(0,0,0);margin-left: 40px;'])?>
