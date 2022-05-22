-- phpMyAdmin SQL Dump
-- version 4.9.5
-- https://www.phpmyadmin.net/
--
-- Host: localhost:8889
-- Generation Time: May 22, 2022 at 07:48 PM
-- Server version: 5.7.30
-- PHP Version: 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

--
-- Database: `toureasy`
--

-- --------------------------------------------------------

--
-- Table structure for table `amis`
--

CREATE TABLE `amis` (
                        `amis1` int(10) UNSIGNED NOT NULL COMMENT 'on convient arbitrairement que amis1 est celui qui a l id le plus petit',
                        `amis2` int(10) UNSIGNED NOT NULL,
                        `dateCreation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `amis`
--

INSERT INTO `amis` (`amis1`, `amis2`, `dateCreation`) VALUES
    (1, 12, '2021-05-30 15:26:21');

-- --------------------------------------------------------

--
-- Table structure for table `appartenanceliste`
--

CREATE TABLE `appartenanceliste` (
                                     `numeroDansListe` smallint(5) UNSIGNED NOT NULL,
                                     `idListe` int(10) UNSIGNED NOT NULL,
                                     `idMonument` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `auteurmonumentprive`
--

CREATE TABLE `auteurmonumentprive` (
                                       `idMonument` int(10) UNSIGNED NOT NULL,
                                       `idMembre` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `auteurmonumentprive`
--

INSERT INTO `auteurmonumentprive` (`idMonument`, `idMembre`) VALUES
                                                                 (1, 1),
                                                                 (23, 1);

-- --------------------------------------------------------

--
-- Table structure for table `contribution`
--

CREATE TABLE `contribution` (
                                `idContribution` int(10) UNSIGNED NOT NULL,
                                `monumentTemporaire` int(10) UNSIGNED NOT NULL,
                                `monumentAModifier` int(10) UNSIGNED DEFAULT NULL,
                                `contributeur` int(10) UNSIGNED NOT NULL,
                                `moderateurDemande` int(10) UNSIGNED DEFAULT NULL,
                                `estNouveauMonument` tinyint(1) NOT NULL,
                                `date` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                `statutContribution` enum('enAttenteDeTraitement','acceptée','refusée') NOT NULL,
                                `description` varchar(400) DEFAULT NULL COMMENT 'sert au contributeur à synthétiser et expliquer les modifications qu il a apportées.\r\nNULL dans le cas d un nouveau monument.'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `contribution`
--

INSERT INTO `contribution` (`idContribution`, `monumentTemporaire`, `monumentAModifier`, `contributeur`, `moderateurDemande`, `estNouveauMonument`, `date`, `statutContribution`, `description`) VALUES
                                                                                                                                                                                                     (1, 2, NULL, 1, 1, 1, '2021-04-22 17:00:39', 'acceptée', NULL),
                                                                                                                                                                                                     (2, 3, NULL, 1, 1, 1, '2021-04-22 17:11:40', 'acceptée', NULL),
                                                                                                                                                                                                     (3, 4, NULL, 2, 1, 1, '2021-04-23 16:13:00', 'acceptée', NULL),
                                                                                                                                                                                                     (4, 5, NULL, 2, 1, 1, '2021-04-23 16:28:47', 'acceptée', NULL),
                                                                                                                                                                                                     (5, 6, NULL, 2, 1, 1, '2021-04-23 16:56:06', 'acceptée', NULL),
                                                                                                                                                                                                     (6, 7, NULL, 2, 1, 1, '2021-05-28 17:25:41', 'acceptée', NULL),
                                                                                                                                                                                                     (7, 8, NULL, 2, 1, 1, '2021-05-28 17:31:53', 'acceptée', NULL),
                                                                                                                                                                                                     (8, 9, NULL, 2, 1, 1, '2021-05-28 19:12:09', 'acceptée', NULL),
                                                                                                                                                                                                     (9, 10, NULL, 2, 1, 1, '2021-05-29 08:57:02', 'acceptée', NULL),
                                                                                                                                                                                                     (10, 11, NULL, 2, 1, 1, '2021-05-29 09:15:00', 'acceptée', NULL),
                                                                                                                                                                                                     (11, 12, NULL, 2, 1, 1, '2021-05-29 09:18:47', 'acceptée', NULL),
                                                                                                                                                                                                     (12, 13, NULL, 2, 1, 1, '2021-05-29 09:35:57', 'acceptée', NULL),
                                                                                                                                                                                                     (13, 14, NULL, 2, 1, 1, '2021-05-29 09:42:04', 'acceptée', NULL),
                                                                                                                                                                                                     (15, 16, NULL, 2, 1, 1, '2021-05-29 13:29:10', 'acceptée', NULL),
                                                                                                                                                                                                     (19, 20, NULL, 2, 1, 1, '2021-05-29 16:21:49', 'acceptée', NULL),
                                                                                                                                                                                                     (20, 21, NULL, 9, 1, 1, '2021-05-30 15:16:58', 'acceptée', NULL),
                                                                                                                                                                                                     (21, 22, NULL, 9, 1, 1, '2021-05-30 15:17:45', 'acceptée', NULL),
                                                                                                                                                                                                     (22, 25, NULL, 9, 1, 1, '2021-05-30 15:26:36', 'acceptée', NULL),
                                                                                                                                                                                                     (24, 27, NULL, 2, NULL, 1, '2021-06-26 15:03:54', 'enAttenteDeTraitement', NULL),
                                                                                                                                                                                                     (26, 29, NULL, 2, NULL, 1, '2021-06-26 15:17:32', 'enAttenteDeTraitement', NULL),
                                                                                                                                                                                                     (27, 30, NULL, 2, NULL, 1, '2021-06-26 15:31:15', 'enAttenteDeTraitement', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `demandeAmi`
--

CREATE TABLE `demandeAmi` (
                              `idDemandeAmi` int(10) UNSIGNED NOT NULL,
                              `idDemandeur` int(10) UNSIGNED NOT NULL,
                              `dateExpiration` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                              `token` varchar(20) DEFAULT NULL,
                              `disponible` tinyint(1) NOT NULL DEFAULT '1'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `demandeAmi`
--

INSERT INTO `demandeAmi` (`idDemandeAmi`, `idDemandeur`, `dateExpiration`, `token`, `disponible`) VALUES
                                                                                                      (1, 12, '2021-05-30 15:26:00', '4b54c76467a69a', 0),
                                                                                                      (2, 47, '2022-05-19 16:19:20', '8cff45f457aa91', 1),
                                                                                                      (3, 47, '2022-05-20 14:24:46', 'b3fb2385e85c8f', 1),
                                                                                                      (4, 49, '2022-05-22 18:09:02', 'd751f911b4e7e4', 1),
                                                                                                      (5, 49, '2022-05-22 18:09:37', '2e7954c89f1050', 1),
                                                                                                      (6, 49, '2022-05-22 18:10:39', 'acd08e80c702de', 1),
                                                                                                      (7, 49, '2022-05-22 18:11:03', '9e87b628a784e3', 1),
                                                                                                      (8, 49, '2022-05-22 18:11:09', '2746d071d6206b', 1),
                                                                                                      (9, 49, '2022-05-22 18:11:14', '61e1ae9ca61d88', 1),
                                                                                                      (10, 49, '2022-05-22 18:11:21', 'c3f1cc3fa6f4ed', 1),
                                                                                                      (11, 49, '2022-05-22 18:11:27', '58075bd340317c', 1),
                                                                                                      (12, 49, '2022-05-22 18:11:41', '1155ee1489924a', 1),
                                                                                                      (13, 49, '2022-05-22 18:11:50', 'ab5fb039746df8', 1),
                                                                                                      (14, 49, '2022-05-22 18:12:01', 'cad4f17dde4f05', 1),
                                                                                                      (15, 49, '2022-05-22 18:12:17', '600af7d9dd5817', 1),
                                                                                                      (16, 49, '2022-05-22 18:12:24', '5148b59f666584', 1),
                                                                                                      (17, 49, '2022-05-22 18:12:38', '7e0253ff89a3fb', 1),
                                                                                                      (18, 49, '2022-05-22 18:13:17', '18b40ce8130661', 1),
                                                                                                      (19, 49, '2022-05-22 18:13:20', '5715f5c34b1a9e', 1),
                                                                                                      (20, 49, '2022-05-22 18:13:43', 'c73d0cb11809e5', 1);

-- --------------------------------------------------------

--
-- Table structure for table `favoris`
--

CREATE TABLE `favoris` (
                           `idMonument` int(10) UNSIGNED NOT NULL,
                           `idMembre` int(10) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `favoris`
--

INSERT INTO `favoris` (`idMonument`, `idMembre`) VALUES
                                                     (1, 1),
                                                     (3, 1),
                                                     (10, 1),
                                                     (10, 9),
                                                     (21, 9),
                                                     (22, 9),
                                                     (25, 9),
                                                     (16, 50);

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
                         `numeroImage` tinyint(3) UNSIGNED NOT NULL COMMENT 'Numéro de l''image parmis les images illustrant le monument',
                         `idMonument` int(10) UNSIGNED NOT NULL,
                         `urlImage` varchar(200) NOT NULL COMMENT 'Chemin géneré automatiquement permettant de retrouver l''image sur le serveur'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`numeroImage`, `idMonument`, `urlImage`) VALUES
                                                                  (0, 2, 'web/img/taken_2021_04_22_17_00_39a237.jpg'),
                                                                  (0, 3, 'web/img/taken_2021_04_22_17_11_409a91.jpg'),
                                                                  (0, 4, 'web/img/taken_2021_04_23_16_13_0004cf.jpg'),
                                                                  (0, 5, 'web/img/taken_2021_04_23_16_28_47c8f1.jpg'),
                                                                  (0, 6, 'web/img/taken_2021_04_23_16_56_06e5f7.jpg'),
                                                                  (0, 7, 'web/img/taken_2021_05_28_17_25_412622.jpg'),
                                                                  (0, 8, 'web/img/taken_2021_05_28_17_31_5343b5.jpg'),
                                                                  (0, 9, 'web/img/taken_2021_05_28_19_12_0992ea.jpg'),
                                                                  (0, 10, 'web/img/taken_2021_05_29_08_57_02793a.jpg'),
                                                                  (0, 11, 'web/img/taken_2021_05_29_09_15_006ef1.jpg'),
                                                                  (0, 12, 'web/img/taken_2021_05_29_09_18_47d30c.jpg'),
                                                                  (0, 13, 'web/img/taken_2021_05_29_09_35_57583f.jpg'),
                                                                  (0, 14, 'web/img/taken_2021_05_29_09_42_041b78.jpg'),
                                                                  (0, 16, 'web/img/taken_2021_05_29_13_29_101621.jpg'),
                                                                  (0, 20, 'web/img/taken_2021_05_29_16_21_49d1bf.jpg'),
                                                                  (0, 21, 'web/img/taken_2021_05_30_15_16_58577f.jpg'),
                                                                  (0, 22, 'web/img/taken_2021_05_30_15_17_455ae2.jpg'),
                                                                  (0, 23, 'web/img/taken_2021_05_30_15_23_060fe6.jpg'),
                                                                  (0, 25, 'web/img/taken_2021_05_30_15_26_365533.jpg'),
                                                                  (0, 27, 'web/img/taken_2021_06_26_15_03_547985.jpg'),
                                                                  (0, 29, 'web/img/taken_2021_06_26_15_17_32f888.jpg'),
                                                                  (0, 30, 'web/img/taken_2021_06_26_15_31_151f66.jpg'),
                                                                  (1, 1, 'web/img/taken_2021_04_22_17_48_46f247.jpg'),
                                                                  (1, 11, 'web/img/taken_2021_05_29_09_15_00f919.jpg'),
                                                                  (1, 16, 'web/img/taken_2021_05_29_13_29_10c843.jpg');

-- --------------------------------------------------------

--
-- Table structure for table `listemonument`
--

CREATE TABLE `listemonument` (
                                 `nom` varchar(40) NOT NULL,
                                 `idListe` int(10) UNSIGNED NOT NULL,
                                 `description` varchar(200) NOT NULL DEFAULT 'Description absente',
                                 `visibilite` set('toutLeMonde','moiUniquement','UtilisateurAvecLien') NOT NULL DEFAULT 'moiUniquement',
                                 `dateCreation` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                                 `idcreateur` int(10) UNSIGNED NOT NULL,
                                 `token` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `listemonument`
--

INSERT INTO `listemonument` (`nom`, `idListe`, `description`, `visibilite`, `dateCreation`, `idcreateur`, `token`) VALUES
                                                                                                                       ('sd', 1, '', 'moiUniquement', '2022-05-20 11:07:54', 48, '06cf8cdb6c'),
                                                                                                                       ('test', 2, '', 'moiUniquement', '2022-05-20 13:39:29', 47, 'cf592351f3'),
                                                                                                                       ('sdfsdfsdfqdsqsdqsd', 3, 'sqdsqdqdsqsdqs', 'moiUniquement', '2022-05-20 14:08:22', 47, '7d56eea9ea'),
                                                                                                                       ('test', 4, '', 'moiUniquement', '2022-05-22 18:02:36', 49, 'c2621ae3f6'),
                                                                                                                       ('s;jdfs', 5, '', 'moiUniquement', '2022-05-22 18:22:52', 50, 'e8205fb83f');

-- --------------------------------------------------------

--
-- Table structure for table `membre`
--

CREATE TABLE `membre` (
                          `idMembre` int(10) UNSIGNED NOT NULL,
                          `nom` varchar(30) DEFAULT NULL,
                          `prenom` varchar(30) DEFAULT NULL,
                          `sexe` enum('homme','femme','autre','non-renseigné') NOT NULL DEFAULT 'non-renseigné',
                          `email` varchar(256) DEFAULT NULL,
                          `dateNaissance` date DEFAULT NULL,
                          `username` varchar(30) DEFAULT NULL,
                          `dateInscription` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
                          `role` tinyint(3) UNSIGNED NOT NULL DEFAULT '1',
                          `password` varchar(100) DEFAULT NULL,
                          `token` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `membre`
--

INSERT INTO `membre` (`idMembre`, `nom`, `prenom`, `sexe`, `email`, `dateNaissance`, `username`, `dateInscription`, `role`, `password`, `token`) VALUES
                                                                                                                                                     (1, 'Brancati', 'Silvio', 'homme', 'ssilvio.brancati@gmail.com', '2001-09-26', NULL, '2021-04-22 15:55:58', 2, NULL, 'e5cd0b89cd'),
                                                                                                                                                     (2, '', 'Nicolas', 'non-renseigné', NULL, '2001-06-29', NULL, '2021-04-22 17:15:17', 2, NULL, '8a9d3f85bf'),
                                                                                                                                                     (3, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-04-23 09:49:41', 1, NULL, 'cb9124dc50'),
                                                                                                                                                     (4, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-04-23 09:50:56', 1, NULL, '3c8f12ef40'),
                                                                                                                                                     (5, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-04-26 07:41:45', 1, NULL, '5f051789e7'),
                                                                                                                                                     (6, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-22 12:57:07', 1, NULL, 'f5689c105e'),
                                                                                                                                                     (7, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-22 13:01:48', 1, NULL, '67bf56fc61'),
                                                                                                                                                     (8, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-28 17:24:13', 1, NULL, 'a85a697131'),
                                                                                                                                                     (9, 'Zapp', 'Rémi', 'homme', 'remitos57350@gmail.com', '2021-05-21', NULL, '2021-05-29 09:19:54', 1, NULL, 'b8d5d71ba4'),
                                                                                                                                                     (10, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-30 15:11:07', 1, NULL, 'd17bd51b69'),
                                                                                                                                                     (11, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-30 15:18:23', 1, NULL, '5653eee878'),
                                                                                                                                                     (12, 'SIMON', 'Eliott', 'homme', NULL, '2001-07-15', NULL, '2021-05-30 15:23:44', 1, NULL, '02e96b828a'),
                                                                                                                                                     (13, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-30 16:19:03', 1, NULL, '147efe4be9'),
                                                                                                                                                     (14, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-31 16:29:40', 1, NULL, 'a1328ed70b'),
                                                                                                                                                     (15, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-31 16:30:04', 1, NULL, '9f33382c81'),
                                                                                                                                                     (16, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-31 16:30:15', 1, NULL, '8539323414'),
                                                                                                                                                     (17, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-31 16:30:37', 1, NULL, 'ebd4f487f4'),
                                                                                                                                                     (18, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-31 16:32:14', 1, NULL, 'bd5a60a5ba'),
                                                                                                                                                     (19, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-31 16:32:57', 1, NULL, '93b9c5b63b'),
                                                                                                                                                     (20, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-31 16:33:47', 1, NULL, '6e6d03f062'),
                                                                                                                                                     (21, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-31 17:21:51', 1, NULL, 'dfd06ae4b3'),
                                                                                                                                                     (22, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-31 18:05:36', 1, NULL, '2ecaacc42d'),
                                                                                                                                                     (23, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-31 18:05:39', 1, NULL, 'a22c2219d8'),
                                                                                                                                                     (24, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-05-31 19:07:58', 1, NULL, '7e4b548fe1'),
                                                                                                                                                     (25, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-06-01 17:44:54', 1, NULL, '55e7e36cea'),
                                                                                                                                                     (26, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-06-04 17:20:30', 1, NULL, '8c12134c0c'),
                                                                                                                                                     (27, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-06-04 18:04:09', 1, NULL, '0c4dcfe43d'),
                                                                                                                                                     (28, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-06-05 11:37:01', 1, NULL, '5366eb190f'),
                                                                                                                                                     (29, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2021-06-11 12:29:21', 1, NULL, '836d0b1ce7'),
                                                                                                                                                     (30, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 10:31:40', 1, NULL, '6fabbcb463'),
                                                                                                                                                     (31, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 10:32:46', 1, NULL, '4481478f5e'),
                                                                                                                                                     (32, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 10:38:35', 1, NULL, '5e89bfc1cf'),
                                                                                                                                                     (33, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 10:38:51', 1, NULL, 'ddac1e4d41'),
                                                                                                                                                     (34, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 10:40:34', 1, NULL, '0453079efb'),
                                                                                                                                                     (35, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 10:41:19', 1, NULL, '2f878d98cd'),
                                                                                                                                                     (36, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 10:41:32', 1, NULL, 'b1f6afff6c'),
                                                                                                                                                     (37, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 10:42:34', 1, NULL, 'd40cdc13d5'),
                                                                                                                                                     (38, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 10:49:12', 1, NULL, 'e4fd207bdb'),
                                                                                                                                                     (39, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 10:49:40', 1, NULL, '1705901316'),
                                                                                                                                                     (40, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 10:50:03', 1, NULL, '4076d6334e'),
                                                                                                                                                     (41, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 10:50:30', 1, NULL, '79062ada79'),
                                                                                                                                                     (42, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 13:04:57', 1, NULL, 'a0aabc5cb8'),
                                                                                                                                                     (43, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 14:14:02', 1, NULL, 'bfa0219d0c'),
                                                                                                                                                     (44, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 14:14:06', 1, NULL, '8078380806'),
                                                                                                                                                     (45, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 14:17:50', 1, NULL, '2505aeda60'),
                                                                                                                                                     (46, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-19 14:18:11', 1, NULL, '9cadde79a1'),
                                                                                                                                                     (47, 'Brancati', 'Silvio', 'homme', 'mail@mail.com', '2022-05-09', NULL, '2022-05-19 14:18:14', 1, NULL, 'e0f42c1719'),
                                                                                                                                                     (48, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-20 11:07:23', 1, NULL, '1fd3494f1f'),
                                                                                                                                                     (49, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-22 18:02:03', 1, NULL, 'e1c20c78ae'),
                                                                                                                                                     (50, NULL, NULL, 'non-renseigné', NULL, NULL, NULL, '2022-05-22 18:22:38', 1, NULL, '479c6d27f6');

-- --------------------------------------------------------

--
-- Table structure for table `monument`
--

CREATE TABLE `monument` (
                            `idMonument` int(10) UNSIGNED NOT NULL,
                            `nomMonum` varchar(100) DEFAULT NULL,
                            `descCourte` varchar(80) DEFAULT NULL,
                            `descLongue` varchar(2000) DEFAULT NULL,
                            `longitude` decimal(9,6) DEFAULT NULL,
                            `latitude` decimal(9,6) DEFAULT NULL,
                            `estTemporaire` tinyint(1) NOT NULL DEFAULT '0',
                            `estPrive` tinyint(1) NOT NULL DEFAULT '0',
                            `token` varchar(20) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `monument`
--

INSERT INTO `monument` (`idMonument`, `nomMonum`, `descCourte`, `descLongue`, `longitude`, `latitude`, `estTemporaire`, `estPrive`, `token`) VALUES
                                                                                                                                                 (1, 'Test 01', NULL, 'test', '6.885888', '48.241021', 0, 1, '5b7da5633a'),
                                                                                                                                                 (2, 'Place Stanislas', NULL, 'En plein cœur de Nancy, la place Stanislas, parfait exemple du classicisme français, est considérée comme la plus belle place royale d&#39;Europe. Joyau de l&#39;ensemble architectural du 18e siècle inscrit sur la liste du Patrimoine Mondial de l&#39;Humanité par l&#39;Unesco, avec les places d&#39;Alliance et de la Carrière.Édifiée par Emmanuel Héré, elle est entourée de grilles finement ouvragées et rehaussées d&#39;or réalisées par le ferronnier Jean Lamour, et de deux fontaines majestueuses dessinées par Barthélémy Guibal. L&#39;hôtel de ville, l&#39;Opéra-Théâtre et le musée des Beaux-Arts font partie des édifices de style classique situés tout autour de la place.Depuis 1831, la statue de Stanislas Leszczynski, ancien roi de Pologne devenu duc de Lorraine s&#39;élève au centre de la place qui porte son nom.La place Stanislas, aujourd&#39;hui entièrement piétonne, devient chaque année le théâtre estival d&#39;un son et lumière remarquable.', '6.183142', '48.693633', 0, 0, 'e5e26dc1af'),
                                                                                                                                                 (3, 'Gare de Saint-Dié-des-Vosges', NULL, 'La gare de Saint-Dié-des-Vosges est une gare ferroviaire française des lignes de Strasbourg-Ville à Saint-Dié, d&#39;Arches à Saint-Dié et de Lunéville à Saint-Dié, située sur le territoire de la commune de Saint-Dié-des-Vosges, dans le département des Vosges, en région Grand Est.', '6.948414', '48.282131', 0, 0, '98177e92da'),
                                                                                                                                                 (4, 'Notre Dame de Lourde', NULL, 'C&#39;est beau !&nbsp;', '6.177417', '48.673188', 0, 0, '93cb259d95'),
                                                                                                                                                 (5, 'Marre du parc Sainte Marie', NULL, '', '6.173032', '48.677660', 0, 0, '3970682d79'),
                                                                                                                                                 (6, 'Lune', NULL, 'Parce que parfois il suffit de lever les yeux', '6.180506', '48.674779', 0, 0, '8da4f96cc4'),
                                                                                                                                                 (7, 'Palais du Gouverneur', NULL, '', '6.181013', '48.696550', 0, 0, '8332de2e52'),
                                                                                                                                                 (8, 'porte Egee ', NULL, '', '6.181013', '48.696550', 0, 0, '63c1ca0a6a'),
                                                                                                                                                 (9, 'Cathédrale de Nancy', NULL, '', '6.187149', '48.694469', 0, 0, '784f7389e7'),
                                                                                                                                                 (10, 'Décathlon de Saint Dié', NULL, 'Lieux de tournage mythique&nbsp;', '6.936343', '48.293364', 0, 0, '3f02ee4bbd'),
                                                                                                                                                 (11, 'Cathédrale de Saint Dié', NULL, '', '6.950352', '48.289144', 0, 0, 'd83b566330'),
                                                                                                                                                 (12, 'Musée Pierre-Noël', NULL, '', '6.949658', '48.288746', 0, 0, 'e5cffbf079'),
                                                                                                                                                 (13, 'Place du marché ', NULL, '', '6.950656', '48.286091', 0, 0, '106735e98c'),
                                                                                                                                                 (14, 'Tour de la liberté ', NULL, '', '6.947318', '48.286312', 0, 0, 'ed4bcca6ef'),
                                                                                                                                                 (16, 'Roches Saint Martin', NULL, 'Très beau point de vue sur Saint Dié', '6.932347', '48.278495', 0, 0, '24c4cce807'),
                                                                                                                                                 (20, 'Église Saint Martin', NULL, '', '6.948741', '48.283803', 0, 0, 'e8f6c12e2f'),
                                                                                                                                                 (21, 'Carte géographique', NULL, 'Indique certaines directions', '6.967290', '49.203761', 0, 0, '87c2f656ee'),
                                                                                                                                                 (22, 'La grande croix', NULL, 'Célèbre monument de Spicheren', '6.967216', '49.204011', 0, 0, 'e9d0e810aa'),
                                                                                                                                                 (23, 'Charlo le puceau', NULL, 'Le titre suffit', '6.951260', '48.283262', 0, 1, '3b68e3d7a1'),
                                                                                                                                                 (25, 'Char M-24 Chaffee', NULL, 'Cadeau d&#39;Amérique', '6.968145', '49.200793', 0, 0, '8ebc0df576'),
                                                                                                                                                 (27, 'Côte de Pagny', NULL, 'Point de vue sympa,&nbsp;difficulté en vélo : échauffement&nbsp;', '5.710622', '48.534620', 1, 0, 'ddaa84ae8a'),
                                                                                                                                                 (29, 'Coin baignade ', NULL, 'Sympa pour se baigner (en toute illégalité bien sûr)&nbsp;', '5.710622', '48.534620', 1, 0, 'b861ccc963'),
                                                                                                                                                 (30, 'Château de Montbras', NULL, '', '5.693709', '48.527779', 1, 0, '8285b003ac');

-- --------------------------------------------------------

--
-- Table structure for table `role`
--

CREATE TABLE `role` (
                        `idRole` tinyint(3) UNSIGNED NOT NULL,
                        `nomRole` varchar(30) NOT NULL,
                        `permModererContrib` tinyint(1) NOT NULL DEFAULT '0',
                        `permProposerContrib` tinyint(1) NOT NULL DEFAULT '0',
                        `permAdministration` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `role`
--

INSERT INTO `role` (`idRole`, `nomRole`, `permModererContrib`, `permProposerContrib`, `permAdministration`) VALUES
                                                                                                                (1, 'utilisateur', 0, 1, 0),
                                                                                                                (2, 'utilisateurDeConfiance', 1, 1, 0),
                                                                                                                (3, 'administrateur', 1, 1, 1);

-- --------------------------------------------------------

--
-- Table structure for table `source`
--

CREATE TABLE `source` (
                          `numeroSource` tinyint(3) UNSIGNED NOT NULL COMMENT 'Numéro de la source parmis les sources accompagnant la description du monument',
                          `idMonument` int(10) UNSIGNED NOT NULL,
                          `lienSource` varchar(400) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `visite`
--

CREATE TABLE `visite` (
                          `idMonument` int(10) UNSIGNED NOT NULL,
                          `idMembre` int(10) UNSIGNED NOT NULL,
                          `dateVisite` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `amis`
--
ALTER TABLE `amis`
    ADD PRIMARY KEY (`amis1`,`amis2`),
  ADD KEY `fk_amis_membre2` (`amis2`);

--
-- Indexes for table `appartenanceliste`
--
ALTER TABLE `appartenanceliste`
    ADD PRIMARY KEY (`idListe`,`idMonument`),
  ADD KEY `fk_appartenanceListe_monument` (`idMonument`);

--
-- Indexes for table `auteurmonumentprive`
--
ALTER TABLE `auteurmonumentprive`
    ADD PRIMARY KEY (`idMonument`,`idMembre`),
  ADD KEY `fk_auteurmonumentprive_idmembre` (`idMembre`);

--
-- Indexes for table `contribution`
--
ALTER TABLE `contribution`
    ADD PRIMARY KEY (`idContribution`),
  ADD KEY `fk_contribution_monumentTemporaire` (`monumentTemporaire`),
  ADD KEY `fk_contribution_monumentAModifier` (`monumentAModifier`),
  ADD KEY `fk_contribution_idModerateur` (`moderateurDemande`),
  ADD KEY `contribution_unique_statut_date` (`statutContribution`,`date`),
  ADD KEY `contribution_unique_contributeur` (`contributeur`,`date`);

--
-- Indexes for table `demandeAmi`
--
ALTER TABLE `demandeAmi`
    ADD PRIMARY KEY (`idDemandeAmi`),
  ADD KEY `fk_demandeAmi_demandeur` (`idDemandeur`);

--
-- Indexes for table `favoris`
--
ALTER TABLE `favoris`
    ADD PRIMARY KEY (`idMonument`,`idMembre`),
  ADD KEY `fk_favoris_membre` (`idMembre`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
    ADD PRIMARY KEY (`numeroImage`,`idMonument`),
  ADD KEY `fk_image_monument` (`idMonument`);

--
-- Indexes for table `listemonument`
--
ALTER TABLE `listemonument`
    ADD PRIMARY KEY (`idListe`),
  ADD KEY `listemonument_unique_nom` (`nom`),
  ADD KEY `listemonument_unique_createur` (`idcreateur`);

--
-- Indexes for table `membre`
--
ALTER TABLE `membre`
    ADD PRIMARY KEY (`idMembre`),
  ADD UNIQUE KEY `membre_unique_username` (`username`),
  ADD UNIQUE KEY `membre_unique_email` (`email`),
  ADD KEY `fk_membre_role` (`role`),
  ADD KEY `membre_index_email_password` (`email`,`password`),
  ADD KEY `membre_index_username_password` (`username`,`password`);

--
-- Indexes for table `monument`
--
ALTER TABLE `monument`
    ADD PRIMARY KEY (`idMonument`),
  ADD KEY `monument_index_coordonnees` (`estTemporaire`,`latitude`,`longitude`),
  ADD KEY `monument_index_nom` (`estTemporaire`,`nomMonum`);

--
-- Indexes for table `role`
--
ALTER TABLE `role`
    ADD PRIMARY KEY (`idRole`);

--
-- Indexes for table `source`
--
ALTER TABLE `source`
    ADD PRIMARY KEY (`numeroSource`,`idMonument`),
  ADD KEY `fk_source_monument` (`idMonument`);

--
-- Indexes for table `visite`
--
ALTER TABLE `visite`
    ADD PRIMARY KEY (`idMonument`,`idMembre`),
  ADD KEY `fk_visite_membre` (`idMembre`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `contribution`
--
ALTER TABLE `contribution`
    MODIFY `idContribution` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT for table `demandeAmi`
--
ALTER TABLE `demandeAmi`
    MODIFY `idDemandeAmi` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;

--
-- AUTO_INCREMENT for table `listemonument`
--
ALTER TABLE `listemonument`
    MODIFY `idListe` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `membre`
--
ALTER TABLE `membre`
    MODIFY `idMembre` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `monument`
--
ALTER TABLE `monument`
    MODIFY `idMonument` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `role`
--
ALTER TABLE `role`
    MODIFY `idRole` tinyint(3) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `amis`
--
ALTER TABLE `amis`
    ADD CONSTRAINT `fk_amis_membre1` FOREIGN KEY (`amis1`) REFERENCES `membre` (`idMembre`),
  ADD CONSTRAINT `fk_amis_membre2` FOREIGN KEY (`amis2`) REFERENCES `membre` (`idMembre`);

--
-- Constraints for table `appartenanceliste`
--
ALTER TABLE `appartenanceliste`
    ADD CONSTRAINT `fk_appartenanceListe_listeMonument` FOREIGN KEY (`idListe`) REFERENCES `listemonument` (`idListe`),
  ADD CONSTRAINT `fk_appartenanceListe_monument` FOREIGN KEY (`idMonument`) REFERENCES `monument` (`idMonument`);

--
-- Constraints for table `auteurmonumentprive`
--
ALTER TABLE `auteurmonumentprive`
    ADD CONSTRAINT `fk_auteurmonumentprive_idmembre` FOREIGN KEY (`idMembre`) REFERENCES `membre` (`idMembre`),
  ADD CONSTRAINT `fk_auteurmonumentprive_idmonument` FOREIGN KEY (`idMonument`) REFERENCES `monument` (`idMonument`);

--
-- Constraints for table `contribution`
--
ALTER TABLE `contribution`
    ADD CONSTRAINT `fk_contribution_idContributeur` FOREIGN KEY (`contributeur`) REFERENCES `membre` (`idMembre`),
  ADD CONSTRAINT `fk_contribution_idModerateur` FOREIGN KEY (`moderateurDemande`) REFERENCES `membre` (`idMembre`),
  ADD CONSTRAINT `fk_contribution_monumentAModifier` FOREIGN KEY (`monumentAModifier`) REFERENCES `monument` (`idMonument`),
  ADD CONSTRAINT `fk_contribution_monumentTemporaire` FOREIGN KEY (`monumentTemporaire`) REFERENCES `monument` (`idMonument`);

--
-- Constraints for table `demandeAmi`
--
ALTER TABLE `demandeAmi`
    ADD CONSTRAINT `fk_demandeAmi_demandeur` FOREIGN KEY (`idDemandeur`) REFERENCES `membre` (`idMembre`);

--
-- Constraints for table `favoris`
--
ALTER TABLE `favoris`
    ADD CONSTRAINT `fk_favoris_membre` FOREIGN KEY (`idMembre`) REFERENCES `membre` (`idMembre`),
  ADD CONSTRAINT `fk_favoris_monument` FOREIGN KEY (`idMonument`) REFERENCES `monument` (`idMonument`);

--
-- Constraints for table `image`
--
ALTER TABLE `image`
    ADD CONSTRAINT `fk_image_monument` FOREIGN KEY (`idMonument`) REFERENCES `monument` (`idMonument`);

--
-- Constraints for table `listemonument`
--
ALTER TABLE `listemonument`
    ADD CONSTRAINT `fk_listeMonument_idMembre` FOREIGN KEY (`idcreateur`) REFERENCES `membre` (`idMembre`);

--
-- Constraints for table `membre`
--
ALTER TABLE `membre`
    ADD CONSTRAINT `fk_membre_role` FOREIGN KEY (`role`) REFERENCES `role` (`idRole`);

--
-- Constraints for table `source`
--
ALTER TABLE `source`
    ADD CONSTRAINT `fk_source_monument` FOREIGN KEY (`idMonument`) REFERENCES `monument` (`idMonument`);

--
-- Constraints for table `visite`
--
ALTER TABLE `visite`
    ADD CONSTRAINT `fk_visite_membre` FOREIGN KEY (`idMembre`) REFERENCES `membre` (`idMembre`),
  ADD CONSTRAINT `fk_visite_monument` FOREIGN KEY (`idMonument`) REFERENCES `monument` (`idMonument`);
