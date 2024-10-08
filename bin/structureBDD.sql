-- 04/01/2024
CREATE TABLE `ca_utilisateur` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nom` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `statut` int NOT NULL
);

CREATE TABLE `ca_statut` (
  `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
  `nom` varchar(255) NOT NULL
);

INSERT INTO `ca_statut` (`nom`) VALUES ('visiteur');
INSERT INTO `ca_statut` (`nom`) VALUES ('gestionnaire');
INSERT INTO `ca_statut` (`nom`) VALUES ('admin');
INSERT INTO `ca_statut` (`nom`) VALUES ('test');

INSERT INTO `ca_utilisateur` (`nom`, `password`, `statut`)
VALUES ('test', 'test', '4');
INSERT INTO `ca_utilisateur` (`nom`, `password`, `statut`)
VALUES ('tomtom', 'EwenetGael', '3');

INSERT INTO `ca_utilisateur` (`nom`, `password`, `statut`)
VALUES ('camille', 'camille', '2');

CREATE TABLE `ca_eleve` (
                            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
                            `nom` varchar(255) NOT NULL,
                            `prenom` varchar(255) NOT NULL,
                            `username` varchar(255) NOT NULL,
                            `password` varchar(255) NOT NULL,
                            `date_naissance` varchar(255) NULL,
                            `date_inscription` varchar(255) NOT NULL,
                            `commentaire` varchar(255) NOT NULL,
                            `statut` int NOT NULL,
                            `acces` int NOT NULL,
                            `date_debut_acces` date NOT NULL,
                            `date_fin_acces` date NOT NULL
);

CREATE TABLE `ca_acces` (
                            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
                            `nom` varchar(255) NOT NULL
);

INSERT INTO `ca_acces` (`nom`)
VALUES ('limite');
INSERT INTO `ca_acces` (`nom`)
VALUES ('illimite');

CREATE TABLE `ca_type_video` (
                                 `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
                                 `nom` varchar(255) NOT NULL
);

CREATE TABLE `ca_video` (
                            `id` int NOT NULL AUTO_INCREMENT PRIMARY KEY,
                            `nom` varchar(255) NOT NULL,
                            `type` int NOT NULL,
                            `date_creation` date NOT NULL,
                            `visibilite` tinyint NOT NULL,
                            `nombre_visionnage` varchar(255) NOT NULL,
                            `ordre` int NOT NULL
);

# 18/01/2024
ALTER TABLE `ca_eleve`
    ADD `telephone` varchar(255) COLLATE 'utf8mb4_0900_ai_ci' NULL AFTER `prenom`,
    ADD `mail` varchar(255) COLLATE 'utf8mb4_0900_ai_ci' NULL AFTER `telephone`;

# 24/01/2024
ALTER TABLE `ca_video`
    ADD `nom_fichier_video` varchar(255) COLLATE 'utf8mb4_0900_ai_ci' NOT NULL AFTER `nom`;

ALTER TABLE `ca_video`
    ADD `extension_fichier` varchar(255) COLLATE 'utf8mb4_0900_ai_ci' NOT NULL AFTER `nom_fichier_video`;

# 29/01/2024
INSERT INTO `ca_statut` (`nom`)
VALUES ('élève');

ALTER TABLE `ca_video`
    ADD `commentaire` text NOT NULL AFTER `visibilite`;

# 05/02/2024
ALTER TABLE `ca_type_video`
    ADD `commentaire` text COLLATE 'utf8mb4_0900_ai_ci' NOT NULL;