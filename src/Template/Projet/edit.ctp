<!-- Début titre -->
<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top: 20px;">
    <div class="col">
        <h1 class="text-center">Modifier le projet</h1>
    </div>
</div>
<!-- Fin titre -->

<!-- Création du formulaire
- action : modifierInfos
-->
<?= $this->Form->create('Projet', array('url' => ['action' => 'modifierInfos'])); ?>
<!-- Input (Hidden) de l'id du projet
 (on ajoute l'id du projet en input caché pour pouvoir le récupéré facilement)
 -->
<?= $this->Form->hidden('id',array('default' => $id)); ?>

<!-- Début du titre du projet -->
<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top: 20px;">
    <div class="col text-center d-flex justify-content-center align-items-center label">
      <label>Titre :&nbsp;</label>
      <!-- Input du titre du projet :
      - index : titre
      - label : aucun
      - default : titre du projet précédent
      -->
      <?= $this->Form->input('titre', array('label' => false,'default' => $projet->titre)); ?>
    </div>
</div>
<!-- Fin du titre du projet -->

<!-- Début des dates de projet -->
<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top: 20px;">
    <!-- Début date de début du projet -->
    <div class="col d-flex justify-content-center align-items-center label">
      <label>Date de début :&nbsp;</label>
      <!-- Input de la date de début du projet
      - index : dateDeb
      - label : aucun
      - type : date
      - default : date de début du projet précédente
      - minYear : année minimale pouvant être sélectionnée
      -->
      <?= $this->Form->input('dateDeb', array('label' => false, 'type' => 'date', 'default' => $projet->dateDebut, 'minYear' => $today->year)); ?>
    </div>
    <!-- Fin date de début du projet -->

    <!-- Début date de fin du projet -->
    <div class="col d-flex justify-content-center">
      <label>Date de fin :&nbsp;</label>
      <!-- Input (Date) de la date de fin du projet
      - index : dateFin
      - label : aucun
      - default : date de fin du projet précédente
      - minYear : année minimale pouvant être sélectionnée
      -->
      <?= $this->Form->date('dateFin', array('label' => false, 'default' => $projet->dateFin, 'minYear' => $today->year)); ?>
    </div>
    <!-- Fin date de fin du projet -->
</div>

<!-- Début de la description du projet -->
<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top: 20px;height: 20vh;">
    <div class="col d-flex justify-content-center align-items-center">
      <label class="label">Description :</label>
      <!-- Input (textarea) de la description du projet
      - index : descr
      - label : aucun
      - default : description du projet précédente
      - style : paramètres de style du textarea
      -->
      <?= $this->Form->textarea('descr', array('label' => false, 'default' => $projet->description,'style'=>'width:70%; height:90%; resize:none;')); ?>
    </div>
</div>
<!-- Fin de la description du projet -->

<!-- Début Boutons 'Retour' & 'Modifier le projet' -->
<div class="row" style="margin-right: 60px;margin-left: 60px;margin-top: 20px;height: 10vh;">
    <div class="col-xl-5">
      <!-- Bouton 'Retour' qui renvoie sur l'index d'un projet
      - nom : Retour
      - controller : Tache
      - action : index
      - $id : id du projet à afficher
      -->
      <?= $this->Html->link("Retour", array('controller' => 'Tache', 'action'=> 'index', $id), array( 'class' => 'btn btn-primary grosBouton shadow')); ?>
    </div>
    <div class="col">
      <!-- Bouton 'Modifier le projet' qui envoie le formulaire -->
      <?= $this->Form->submit('Modifier le projet', array('class' => 'btn btn-primary grosBouton shadow')); ?>
    </div>
</div>
<!-- Fin Boutons 'Retour' & 'Modifier le projet' -->
