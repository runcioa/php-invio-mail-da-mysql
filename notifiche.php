<?php

use PHPMailer\PHPMailer\PHPMailer;

include_once(ROOT_DIR . '/private/connect.php');

include_once(ROOT_DIR . '/class/class.mailGenerica.inc.php');

$id_notifiche = recupera_id_notifica($conn);

foreach ($id_notifiche as $id_notifica) {
    
    print_r($id_notifica["id_notifiche_mail"]);

    $notifiche_mail = recupero_notifiche($conn, $id_notifica["id_notifiche_mail"]);

    
    if ($notifiche_mail){
        invio_mail($notifiche_mail);
        set_notifica_inviata($id_notifica["id_notifiche_mail"], $conn);
    }
}

function recupero_notifiche($conn, $id_notifica)
{

    $query = 'SELECT `w_invio_mail`.`utente`,
            `w_invio_mail`.`tipologia`,
            `w_invio_mail`.`oggetto`,
            `w_invio_mail`.`testo`,
            `w_invio_mail`.`stato`,
            `w_invio_mail`.`id_notifiche_mail`
                FROM `w_invio_mail` 
                where `w_invio_mail`.`id_notifiche_mail` = ?
                AND  `w_invio_mail`.`stato` = 0 ;';

    $statement = $conn->prepare($query);

    $statement->bind_param('s', $id_notifica);

    $statement->execute();

    $result = $statement->get_result();

    $statement->close();

    if ($result === false) {
        exit("Errore: impossibile eseguire la query. " . mysqli_error($conn));
    }

    $notifiche_mail = [];

    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        array_push($notifiche_mail, $row);
    }

    return $notifiche_mail;
}

function invio_mail($notifiche_mail)
{

    foreach ($notifiche_mail as $notifica_mail) {
        $indirizzo_mail = $notifica_mail['utente'];
        $oggetto_mail = $notifica_mail['oggetto'];
        $testo_mail = $notifica_mail['testo'];
        echo $indirizzo_mail . $oggetto_mail . $testo_mail;
    }

    $mail = new MailGenerica(true);

    $mail->invia($indirizzo_mail, $oggetto_mail, $testo_mail);
}

function set_notifica_inviata($id_notifica, $conn){
    
    $query = 'update `w_invio_mail` 
            set  `w_invio_mail`.`stato` = 1
            where `w_invio_mail`.`id_notifiche_mail` = ? ;';

    $statement = $conn->prepare($query);

    $statement->bind_param('s', $id_notifica);

    $statement->execute();

    $statement->close();
   
}


function recupera_id_notifica($conn)
{

    $query = "SELECT distinct `id_notifiche_mail` FROM `w_invio_mail`;";

    $result = mysqli_query($conn, $query);

    if ($result === false) {
        exit("Errore: impossibile eseguire la query. " . mysqli_error($conn));
    }

    $id_notifica = array();

    while ($row = $result->fetch_array(MYSQLI_ASSOC)) {
        array_push($id_notifica, $row);
    }

    return $id_notifica;
}
