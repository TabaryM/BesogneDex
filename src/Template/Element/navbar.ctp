<nav class="navbar navbar-light navbar-expand-md bg-light" style="margin-top: 10px;margin-right: 50px;margin-left: 50px;">
    <div class="container-fluid"><button data-toggle="collapse" class="navbar-toggler" data-target="#navcol-1"><span class="sr-only">Toggle navigation</span><span class="navbar-toggler-icon"></span></button>
        <div class="collapse navbar-collapse" id="navcol-1">
            <ul class="nav navbar-nav d-flex justify-content-around" style="width: 100%;">
                <li class="nav-item" role="presentation"><?= $this->Html->link("Acueil", ['controller' => 'Pages', 'action' => 'index'], ['class' => 'nav-link active']) ?></li>
                <li class="nav-item" role="presentation"><?= $this->Html->link("Mes projets", ['controller' => 'Erreur', 'action' => 'inProgress'], ['class' => 'nav-link']) ?></li>
                <li class="nav-item" role="presentation"><?= $this->Html->link("Mes tâches", ['controller' => 'Erreur', 'action' => 'inProgress'], ['class' => 'nav-link']) ?></li>
                <li class="nav-item" role="presentation"><?= $this->Html->link("A propos", ['controller' => 'Erreur', 'action' => 'inProgress'], ['class' => 'nav-link']) ?></li>
            </ul>
        </div>
    </div>
</nav>
