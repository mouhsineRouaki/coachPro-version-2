Drop DATABASE coaching_platform ; 
CREATE DATABASE IF NOT EXISTS coaching_platform;
USE coaching_platform;

CREATE TABLE utilisateur (
    id_utilisateur INT AUTO_INCREMENT PRIMARY KEY,
    nom VARCHAR(50),
    prenom VARCHAR(50),
    email VARCHAR(100) UNIQUE,
    mot_de_pass VARCHAR(300),
    telephone VARCHAR(20),
    role VARCHAR(10),
    img_utilisateur varchar(200,)
    date_creation DATETIME,
    inscritComplet BOOLEAN
);

CREATE TABLE coach (
    id_coach INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT UNIQUE,
    biographie TEXT,
    niveau VARCHAR(50),
    annee_experience int ,
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
);

CREATE TABLE sportif (
    id_sportif INT AUTO_INCREMENT PRIMARY KEY,
    id_utilisateur INT UNIQUE,
    niveau VARCHAR(100),
    objectif VARCHAR(100),
    FOREIGN KEY (id_utilisateur) REFERENCES utilisateur(id_utilisateur)
);

CREATE TABLE sport (
    id_sport INT AUTO_INCREMENT PRIMARY KEY,
    nom_sport VARCHAR(100),
    description_sport TEXT
);

CREATE TABLE coach_sport (
    id_coach INT,
    id_sport INT,
    PRIMARY KEY (id_coach, id_sport),
    FOREIGN KEY (id_coach) REFERENCES coach(id_coach),
    FOREIGN KEY (id_sport) REFERENCES sport(id_sport)
);

CREATE TABLE disponibilite (
    id_disponibilite INT AUTO_INCREMENT PRIMARY KEY,
    id_coach INT,
    date DATE,
    heure_debut DATETIME,
    heure_fin DATETIME,
    isReserved BOOLEAN,
    FOREIGN KEY (id_coach) REFERENCES coach(id_coach)
);

CREATE TABLE experiences (
    id_experience INT AUTO_INCREMENT PRIMARY KEY,
    id_coach INT,
    date_debut DATE,
    date_fin DATE,
    duree INT,
    domaine VARCHAR(200),
    FOREIGN KEY (id_coach) REFERENCES coach(id_coach)
);

CREATE TABLE certification (
    id_certif INT AUTO_INCREMENT PRIMARY KEY,
    id_coach INT,
    titre VARCHAR(50),
    organisme VARCHAR(100),
    annee INT,
    FOREIGN KEY (id_coach) REFERENCES coach(id_coach)
);

CREATE TABLE reservation (
    id_reservation INT AUTO_INCREMENT PRIMARY KEY,
    id_sportif INT,
    id_coach INT,
    date_seance DATE,
    heure_debut INT,
    heure_fin INT,
    status VARCHAR(50),
    date_reservation DATETIME,
    id_sport INT,
    FOREIGN KEY (id_sportif) REFERENCES sportif(id_sportif),
    FOREIGN KEY (id_coach) REFERENCES coach(id_coach),
    FOREIGN KEY (id_sport) REFERENCES sport(id_sport)
);

