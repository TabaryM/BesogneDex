<?php
  //Il faut l'extension mysqli dans php.ini
  function query($link,$requete)
  {
    $resultat=mysqli_query($link,$requete) or die("$requete : ".mysqli_error($link));
	  return($resultat);
  }


$mysqli=mysqli_connect('127.0.0.1', 'root', '') or die("Erreur de connexion");
$base="BesogneDex";

$Sql="
	DROP DATABASE IF EXISTS $base;
	CREATE DATABASE $base;
	USE $base;

    CREATE TABLE Utilisateur (
		idUtilisateur INT NOT NULL AUTO_INCREMENT,
		nom VARCHAR(50),
		prenom VARCHAR(50),
		pseudo VARCHAR(50) NOT NULL UNIQUE,
		email VARCHAR(100) NOT NULL UNIQUE,
		mdp VARCHAR(255),
		PRIMARY KEY (idUtilisateur)
    );

	CREATE TABLE Projet (
		idProjet INT NOT NULL AUTO_INCREMENT,
		titre VARCHAR(128) NOT NULL,
		description VARCHAR(500),
		dateDebut DATE DEFAULT NOW(),
		dateFin DATE,
		etat VARCHAR(10) DEFAULT 'En cours',
		idProprietaire INT NOT NULL,
		PRIMARY KEY (idProjet),
		FOREIGN KEY (idProprietaire) REFERENCES Utilisateur(idUtilisateur)
	);

	CREATE TABLE Tache (
		idTache INT NOT NULL AUTO_INCREMENT,
		titre VARCHAR(50) NOT NULL,
		description VARCHAR(255),
		finie BOOLEAN NOT NULL DEFAULT 0,
		idResponsable INT DEFAULT NULL,
		idProjet INT NOT NULL,
		PRIMARY KEY (idTache),
		FOREIGN KEY (idResponsable) REFERENCES Utilisateur(idUtilisateur),
		FOREIGN KEY (idProjet) REFERENCES Projet(idProjet)
    );

	CREATE TABLE Membre (
		idUtilisateur INT NOT NULL,
		idProjet INT NOT NULL,
		PRIMARY KEY (idUtilisateur, idProjet),
		FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur),
		FOREIGN KEY (idProjet) REFERENCES Projet(idProjet)
	);

	CREATE TABLE NotificationProjet (
		idNotificationProjet INT NOT NULL AUTO_INCREMENT,
		a_valider BOOLEAN NOT NULL,
		contenu VARCHAR(500),
		date DATE DEFAULT NOW(),
		idProjet INT NOT NULL,
		PRIMARY KEY (idNotificationProjet),
		FOREIGN KEY (idProjet) REFERENCES Projet(idProjet)
	);

	CREATE TABLE NotificationTache (
		idNotificationTache INT NOT NULL AUTO_INCREMENT,
		a_valider BOOLEAN NOT NULL,
		contenu VARCHAR(500),
		date DATE DEFAULT NOW(),
		idTache INT NOT NULL,
		PRIMARY KEY (idNotificationTache),
		FOREIGN KEY (idTache) REFERENCES Tache(idTache)
	);

	CREATE TABLE VueNotificationProjet (
		idUtilisateur INT NOT NULL,
		idNotifProjet INT NOT NULL,
		vue BOOLEAN DEFAULT 0,
		PRIMARY KEY (idUtilisateur, idNotifProjet),
		FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur),
		FOREIGN KEY (idNotifProjet) REFERENCES NotificationProjet(idNotificationProjet)
	);

	CREATE TABLE VueNotificationTache (
		idUtilisateur INT NOT NULL,
		idNotifTache INT NOT NULL,
		vue BOOLEAN DEFAULT 0,
		PRIMARY KEY (idUtilisateur, idNotifTache),
		FOREIGN KEY (idUtilisateur) REFERENCES Utilisateur(idUtilisateur),
		FOREIGN KEY (idNotifTache) REFERENCES NotificationTache(idNotificationTache)
	)";

foreach(explode(';',$Sql) as $Requete){
  echo $Requete;
  query($mysqli,$Requete);
}

mysqli_close($mysqli);
