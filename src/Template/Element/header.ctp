<header class="d-flex flex-row justify-content-start align-items-center header">
    <div class="d-flex flex-row justify-content-start align-items-center header">
      <?= $this->Html->image("icones/rotom_dex.png", ['class' => 'image_icone']) ?>
      <h1 class="titre_header"><?= $titre ?></h1>
    </div>
    <?php
      //Booléen qui gère si l'utilisateur est connecté ou non
      if(true):
        ?>
          <div class="d-flex justify-content-end align-items-center div_mail_mdp_header">
              <div class="d-flex flex-column mail_div_header">
                  <div>
                    <label class="mail_label_header">E-mail :</label>
                    <input type="text">
                  </div>
                  <div class="form-check">
                    <input class="form-check-input" type="checkbox" id="formCheck-1">
                    <label class="form-check-label" for="formCheck-1">Rester connecté</label>
                  </div>
              </div>
              <div class="d-flex flex-column mdp_div_header">
                  <div>
                    <label class="label_mdp_header">Mot de passe :</label>
                    <input type="text">
                  </div>
                  <a href="#">Mot de passe oublié ?</a>
              </div>
              <button class="btn btn-primary" type="button">Se connecter</button>
          </div>
        <?php
      endif;
    ?>
</header>
