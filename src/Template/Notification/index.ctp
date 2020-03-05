<p> Bienvenue dans le monde magnifique des notifications (et ceci est par ailleurs juste un élément de debug dans le index.ctp de Notification)</p>

<table>
<?php foreach ($notificationsProjet as $notifProjet): ?>
  <tr>
      <td>
          <?= $notifProjet->idNotifProjet ?>
      </td>
  </tr>


<?php endforeach; ?>
</table>
