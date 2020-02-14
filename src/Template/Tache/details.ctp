<div style="height: 80vh;margin-top: 20px;">
  <div class="container" style="height: 80vh;">
    <div class="row d-flex align-items-start" style="height: 100%;">
      <div class="col-xl-12" style="height: 80%;">
        <div class="table-responsive">
          Description:
          <?php
            echo $desc;
           ?>
        </div>
      </div>
    </div>
    <div class="row d-flex align-items-start" >
      <div class="col-xl-12">
        <?php
          echo $this->Html->link("Projets", array('controller' => 'Projet', 'action'=> 'index'), array( 'class' => 'btn btn-primary'));
        ?>
        <?php
          echo $this->Html->link("Retour", array('controller' => 'Tache', 'action'=> 'index', 'id'=>$id), array( 'class' => 'btn btn-primary'));
        ?>
      </div>
    </div>
  </div>
</div>

</table>
