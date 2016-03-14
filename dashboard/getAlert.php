<?php 

    $count = 0;

    $requete = $db->prepare("SELECT * FROM tickets WHERE TYPE_CLIENT = :type_client AND (AVANCEMENT = 'af' OR AVANCEMENT = 'ec' OR AVANCEMENT = 'arc' OR AVANCEMENT = 'ap') ORDER BY PRIORITE DESC");
    $requete->bindValue(':type_client', $cat);
    $requete->execute();
    $nbrow = $requete->rowCount();
    $today = new DateTime();

    echo '<table class="table">';
    if ($nbrow == 0) {
        echo '<tr><td>Pas de ticket ouvert dans cette catégorie.</td></tr>';
    }
    while ($data = $requete->fetch()) {
    	if ($data["PRIORITE"] == 0) $class = "alert alert-success";
    	else if ($data["PRIORITE"] == 1) $class = "alert alert-warning";
    	else $class = "alert alert-danger";

    	$datelivraison = new DateTime($data["DATE_LIVRAISON"]);
        if ($datelivraison > $today) {
            $days = $datelivraison->diff($today)->format('%d jour(s) restant(s)');
        }
    	else $days = $datelivraison->diff($today)->format('Dépassé de %r%a jour(s)');

    	echo '<tr class="'.$class.'"><td>
          <strong>'.abbrToFull($data["AVANCEMENT"]).' : '.abbrToFull($data["TYPE_INTER"]).' pour '.$data["REF_CLIENT"].'.</strong> '.$days.'.</td></tr>';
        if(++$count >= $max_row) break;
    }

    if ($nbrow > $max_row) echo '<tr><td><a class="btn btn-default" href="allTickets.php">Tickets suivants <span class="badge">'.($nbrow-$max_row).'</span></a></td></tr>';

?>

</table>