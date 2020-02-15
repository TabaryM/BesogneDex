<?= $this->Form->create($utilisateur) ?>
    <fieldset>
        <legend><?= __('Add Utilisateur') ?></legend>
        <?= $this->Form->control('pseudo') ?>
        <?= $this->Form->control('email') ?>
        <?= $this->Form->control('mdp') ?>
   </fieldset>
<?= $this->Form->submit('Valider', array('class' => 'btn btn-primary')) ?>
