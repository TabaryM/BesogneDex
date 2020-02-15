<!-- src/Template/Users/login.ctp -->

<div class="users form">
<?= $this->Flash->render() ?>
<?= $this->Form->create() ?>
    <fieldset>
        <?= $this->Form->control('email', ['label' => 'E-mail :']) ?>
        <?= $this->Form->control('mdp', ['label' => 'Mot de passe :']) ?>
    </fieldset>
<?= $this->Form->submit('Se connecter', array('class' => 'btn btn-primary')) ?>
</div>
