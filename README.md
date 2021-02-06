# Script per l'invio mail con PHPMAILER e crontab

Questo codice PHP permette l'invio di mail con dati presenti in una tabella di un database (MYSQL), se schedulato con crontab su Linux permette l'invio schedulato.

## Tabella Mysql per notifiche

E' presente una tabella 

```SQL
CREATE TABLE `notifiche_mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `oggetto` varchar(50) DEFAULT NULL,
  `testo` varchar(500) DEFAULT NULL,
  `stato` int(1) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `tipologia` varchar(45) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=137 DEFAULT CHARSET=latin1;
SELECT * FROM we.notifiche_mail;
```

dove stato indica se la mail deve essere inviata '0' o è già stata inviata '1'.

## Tabella Mysql per utenti

E' presente una tabella che lega 

```SQL
CREATE TABLE `utenti_mail` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `utente` varchar(50) NOT NULL,
  `tipologia_1` varchar(20) DEFAULT NULL,
  `tipologia_2` varchar(20) DEFAULT NULL,
  `tipologia_3` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=latin1;
```

I campi notifica_X corrispondono alla tipologia della tabella utenti.

## Vista per legare le notifiche agli utenti

```SQL
CREATE view `we`.`w_invio_mail`
AS
  SELECT `um`.`utente`    AS `utente`,
         `nm`.`tipologia` AS `tipologia`,
         `nm`.`oggetto`   AS `oggetto`,
         `nm`.`testo`     AS `testo`,
         `nm`.`stato`     AS `stato`,
         `nm`.`id`        AS `id_notifiche_mail`
  FROM   (`we`.`utenti_mail` `um`
          JOIN `we`.`notifiche_mail` `nm`
            ON(( ( `um`.`tipologia_1` = `nm`.`tipologia` )
                  OR ( `um`.`tipologia_2` = `nm`.`tipologia` )
                  OR ( `um`.`tipologia_3` = `nm`.`tipologia` ) ))); 
```

## Codice PHP

Il codice PHP processa una notifica mail alla volta secondo il suo id, 
manda la mail all'utente e imposta a 1 lo stato della notifica (letta). 