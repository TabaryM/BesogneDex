<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top: 20px;">
    <div class="col">
        <h1 class="text-center">Modifier le projet</h1>
    </div>
</div>
<?= $this->Form->create('Projet', array('url' => ['action' => 'modifierInfos'])); ?>
<?= $this->Form->hidden('id',array('default' => $id)); ?>
<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top: 20px;">
    <div class="col text-center d-flex justify-content-center align-items-center label">
      <label>Titre :&nbsp;</label>
      <?= $this->Form->input('titre', array('label' => false,'default' => $projet->titre)); ?>
    </div>
</div>
<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top: 20px;">
    <div class="col d-flex justify-content-center align-items-center label">
      <label>Date de début :&nbsp;</label>
      <?= $this->Form->input('dateDeb', array('label' => false, 'type' => 'date', 'default' => $projet->dateDebut, 'minYear' => $today->year)); ?>
    </div>
    <div class="col d-flex justify-content-center">
      <label>Date de fin :&nbsp;</label>
      <?= $this->Form->date('dateFin', array('label' => false, 'default' => $projet->dateFin, 'minYear' => $today->year)); ?>
    </div>
</div>
<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top: 20px;height: 20vh;">
    <div class="col d-flex justify-content-center align-items-center">
      <label class="label">Description :</label>
      <?= $this->Form->textarea('descr', array('label' => false, 'default' => $projet->description,'style'=>'width:70%; height:90%; resize:none;')); ?>
    </div>
</div>
<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top: 20px;height: 10vh;">
    <div class="col-xl-5">
      <?= $this->Html->link("Retour", array('controller' => 'Tache', 'action'=> 'index', $id), array( 'class' => 'btn btn-primary grosBouton shadow')); ?>
    </div>
    <div class="col">
      <?= $this->Form->submit('Modifier le projet', array('class' => 'btn btn-primary grosBouton shadow')); ?>
    </div>
</div>
