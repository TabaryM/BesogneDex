  <div class="container" style="margin-top: 50px;">
        <div class="row">
            <div class="col-xl-12 offset-xl-0"><h1><center>Créer un projet</center></h1></div>
        </div>
        <?= $this->Form->create('Projet'); ?>
        <div class="row" style="margin-left: 20px;">
            <div class="col text-center" style="margin-bottom: 20px;margin-top: 20px;"><?= $this->Form->input('titre', array('label' => 'Titre du projet :')); ?></div>
        </div>
        <div class="row">
            <div class="col text-center" style="margin-bottom: 20px;margin-top: 20px;"><label>Date de début :&nbsp;</label><input type="date"></div>
            <div class="col text-left"><label>Date de fin :&nbsp;</label><input type="date" style="margin-top: 20px;margin-bottom: 20px;"></div>
        </div>
        <div class="row">
            <div class="col text-center" style="margin-bottom: 20px;margin-top: 20px;"><?= $this->Form->input('description', array('label' => 'Description :')); ?></div>
        </div>
        <div class="row">
            <div class="col text-center" style="margin-top: 20px;"><?= $this->Html->link("Retour", array('controller' => 'Projet', 'action'=> 'index'), array( 'class' => 'btn btn-primary')); ?></div>
            <div class="col text-center" style="margin-top: 20px;"><?= $this->Form->submit('Créer un projet', array('class' => 'btn btn-primary')); ?></div>
        </div>
    </div>
