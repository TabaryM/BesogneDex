<div class="row" style="margin-right: 60px;margin-left: 60px;height: 80vh;">
  <!-- Début du tableau des tâches prioritaires -->
  <div class="col-xl-6" style="margin-top: 50px;">
    <div class="card shadow" style="background-color: #6fa8dc;">
      <div class="card-body">
        <p style="font-weight: bold;">Tâches prioritaires :</p>
        <div class="table-responsive table-borderless table-green">
          <table class="table table-striped table-borderless table-green">
            <thead>
              <tr>
                <th>Tâche</th>
                <th>Expire le</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $nbColonnes = 0;
              while ($nbColonnes < 10) { //On affiche pas plus de 10 colonnes
                if(isset($tachesPrioritaires[$nbColonnes])){
                  //Affichage du titre et de la date de fin
                  ?>
                  <tr>
                    <td> <?= $tachesPrioritaires[$nbColonnes]['titre']?> </td>
                    <td> <?= $tachesPrioritaires[$nbColonnes]['dateFin']->nice('Europe/Paris', 'fr-FR') ?> </td> <!-- Il y a sûrement un meilleur affichage de date -->
                  </tr>
                  <?php
                }else{
                  //Affichage d'une cellule vide
                  ?>
                  <tr>
                    <td> </td>
                    <td> </td>
                  </tr>
                  <?php
                }//Fin if else
                $nbColonnes++;
              }//Fin boucle
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- Fin du tableau des tâches prioritaires -->
  <!-- Début second tableau -->
  <div class="col-xl-6" style="margin-top: 50px;">
    <div class="card shadow" style="background-color: #6fa8dc;">
      <div class="card-body">
        <p style="font-weight: bold;">Notifications récentes :</p>
        <div class="table-responsive table-borderless table-green">
          <table class="table table-striped table-green table-borderless">
            <tbody>
              <?php
              $nbLigne = 0;
              while ($nbLigne < 11) { //On affiche pas plus de 10 lignes
                if(isset($notifs[$nbLigne])){
                  //Affichage de la notification
                  ?>
                  <tr>
                    <?php // Mis en commentaire parce que cassé et casse l'accueil ?>
                    <td><?php //$notifs[$nbLigne]->une_notification->contenu ?></td>
                    <td><?php // $notifs[$nbLigne]->une_notification->date->nice('Europe/Paris', 'fr-FR') ?></td>
                  </tr>
                  <?php
                }else{
                  //Affichage d'une cellule vide
                  ?>
                  <tr>
                    <td> </td>
                    <td> </td>
                  </tr>
                  <?php
                }//Fin if else
                $nbLigne++;
              }//Fin boucle
              ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!-- Fin second tableau -->
</div>
