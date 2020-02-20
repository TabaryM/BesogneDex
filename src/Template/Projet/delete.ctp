  <div class="container">
        <div class="row">
            <div class="col-xl-12 offset-xl-0"><h1><center>Quitter le projet</center></h1></div>
            <div class="col-xl-12 offset-xl-0"><h5><center>Souhaitez-vous vraiment quitter le projet ?</center></h5></div>
        </div>
        <?= $this->Form->create('Quitter Projet'); ?>
        <div class="row">
            <div class="col text-center" style="margin-top: 20px;"><?= $this->Html->link("Non", array('controller' => 'Projet', 'action'=> 'index'), array( 'class' => 'btn btn-primary')); ?></div>
            <div class="col text-center" style="margin-top: 20px;"><?= $this->Form->submit('Oui', array('class' => 'btn btn-danger')); ?></div>
        </div>
    </div>
