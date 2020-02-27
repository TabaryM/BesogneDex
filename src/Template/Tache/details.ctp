<div class="container d-flex flex-column justify-content-start" style="height: 80vh;margin-top: 20px;">
  <div class="row" style="margin-top: 50px;">
    <div class="col">
      <p>Description du projet :</p>
      <p class="text-center"><?= $desc ?></p>
    </div>
  </div>
  <div class="row" style="margin-top: 50px;">
    <div class="col">
      <p>Membres du projet :</p>
      <div class="card">
        <div class="card-body">
          <p><?= $mbs ?></p>
        </div>
      </div>
    </div>
  </div>
  <div class="row d-flex align-items-start" style="margin-top:20px;" >
    <div class="col-xl-12">
      <?php
      echo $this->Html->link("Retour", array('controller' => 'Tache', 'action'=> 'index', $idProjet), array( 'class' => 'btn btn-primary'));
      ?>
      <?php
      echo $this->Html->link("Projets", array('controller' => 'Projet', 'action'=> 'index'), array( 'class' => 'btn btn-primary'));
      ?>
    </div>
  </div>
</div>
