<div style="height: 80vh;margin-top: 20px;">
  <div class="container">
    <div class="row d-flex align-items-start" style="height: 100%;">
      <div class="col-xl-12" style="height: 80%;">
        <div class="table-responsive">
          <table class="table table-bordered">
            <thead class="thead-light">
              <tr>
                <th>Tache</th>
                <th>Proprietaire</th>
                <th>Etat</th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($taches as $tache):?>
                <tr>
                  <td>
                    <?=   $tache->titre ?>
                  </td>
                  <td>
                    None
                  </td>
                  <td>
                    <?=   $tache->etat ?>
                  </td>
                </tr>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
    <div class="row d-flex align-items-start" >
      <div class="col-xl-12">
        <div class="row">
          <?php
          echo $this->Html->link("Projets", array('controller' => 'Projet', 'action'=> 'index'), array( 'class' => 'btn btn-primary'));
        ?>
        <?php
        echo $this->Html->link("Détails", array('controller' => 'Tache', 'action'=> 'details', 'id'=>$id), array( 'class' => 'btn btn-primary'));
        ?>
        <?php
        echo $this->Html->link("Ajouter une tâche", array('controller' => 'Tache', 'action'=> 'add', 'id' => $id), array( 'class' => 'btn btn-primary'));
        ?>
      </div>
  </div>
</div>

</table>
