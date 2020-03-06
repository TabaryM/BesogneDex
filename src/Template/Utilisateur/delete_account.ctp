<?php $this->Flash->render(); ?>

<!-- Demande confirmation pour supprimer son compte : -->

<div class="container" style="margin-top: 150px;">
    <!-- Question -->
    <div class="row">
        <div class="col-xl-12 text-center">
            <h3 class="text-center">Supprimer mon compte</h3>
            <p style="margin-top:40px;font-size:20px;"> La suppression de votre compte entraînera la suppression de tous les projets que vous avez créés ainsi que toutes vos données utilisateur.<br/> Êtes-vous sûr de vouloir continuer ? </p>
        </div>
    </div>

    <!-- Boutons Oui et Non : -->
    <div class="col text-center  d-flex flex-column justify-content-center align-items-center" style="margin-top: 50px;">
        <?= $this->Html->link("Oui, supprimer définitivement mon compte", array('controller' => 'Utilisateur','action'=> 'deleteAccount'), array( 'class' => 'btn btn-danger btn-lg text-center bg-danger shadow-sm'))?>
        <?= $this->Html->link("Non, je souhaite continuer à utiliser BesogneDex", array('controller' => 'Utilisateur', 'action'=> 'edit'), array( 'class' => 'btn boutonVert btn-lg text-left shadow-sm', 'style' => 'margin-top:40px;'))?>
    </div>
</div>
