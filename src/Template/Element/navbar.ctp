<?php   if($loggedIn):  ?>
<nav class="navbar navbar-light navbar-expand-md bleuClair" style="margin-top: 10px;margin-right: 50px;margin-left: 50px;">
    <div class="container-fluid"><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav d-flex justify-content-around mr-auto" style="width: 100%;">
                <li class="nav-item" role="presentation"><?= $this->Html->link("Accueil", ['controller' => 'Accueil', 'action' => 'index'], ['class' => 'nav-link']) ?></li>
                <li class="nav-item" role="presentation"><?= $this->Html->link("Mes projets", ['controller' => 'Projet', 'action' => 'index'], ['class' => 'nav-link']) ?></li>
                <li class="nav-item" role="presentation"><?= $this->Html->link("Mes tâches", ['controller' => 'Tache', 'action' => 'my'], ['class' => 'nav-link']) ?></li>
                <li class="nav-item" role="presentation"><?= $this->Html->link("À propos", ['controller' => 'Erreur', 'action' => 'inProgress'], ['class' => 'nav-link']) ?></li>
            </ul>
        </div>
    </div>
</nav>
<?php  endif;  ?>
