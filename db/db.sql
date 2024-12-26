


CREATE DATABASE car_management_db;
USE car_management_db;




CREATE TABLE `clients` (
  `cin` varchar(10) NOT NULL,
  `nom` varchar(100) DEFAULT NULL,
  `adress` varchar(255) DEFAULT NULL,
  `tel` varchar(20) DEFAULT NULL,
  PRIMARY KEY (`cin`)
);



CREATE TABLE `contrats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `dure` int DEFAULT NULL,
  `cin_client` varchar(10) DEFAULT NULL,
  `id_matric` varchar(10) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `cin_client` (`cin_client`),
  KEY `id_matric` (`id_matric`),
  CONSTRAINT `contrats_ibfk_1` FOREIGN KEY (`cin_client`) REFERENCES `clients` (`cin`),
  CONSTRAINT `contrats_ibfk_2` FOREIGN KEY (`id_matric`) REFERENCES `voitures` (`matricule`)
);

CREATE TABLE `voitures` (
  `matricule` varchar(10) NOT NULL,
  `marque` varchar(50) DEFAULT NULL,
  `modele` varchar(50) DEFAULT NULL,
  `annee` int DEFAULT NULL,
  PRIMARY KEY (`matricule`)
);

CREATE TABLE `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `username` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `reg_date` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
);


INSERT INTO `users` VALUES (1,'ali','ali','ali','2024-12-09 10:12:12'),(2,'ali','ali@gmail.com','$2y$12$e5odqCuwa/KqFn4zqH/lbO1O86J1OOrsTUN5lBnIU7etnJuRq4tCO','2024-12-09 10:24:01'),(3,'admin','admin@gmail.com','$2y$12$nYeH5nJJx7uPa8mO4zApnOF3KNcBKgVcefIjzaMbg392KQ97GcMD.','2024-12-09 10:24:44');











