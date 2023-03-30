-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : lun. 13 mars 2023 à 12:13
-- Version du serveur :  5.7.31
-- Version de PHP : 7.4.9

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `parcours_client`
--

-- --------------------------------------------------------

--
-- Structure de la table `adresse_facturation`
--

DROP TABLE IF EXISTS `adresse_facturation`;
CREATE TABLE IF NOT EXISTS `adresse_facturation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cp` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bdc`
--

DROP TABLE IF EXISTS `bdc`;
CREATE TABLE IF NOT EXISTS `bdc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `societe_facturation_id` int(11) DEFAULT NULL,
  `tva_id` int(11) DEFAULT NULL,
  `devise_id` int(11) DEFAULT NULL,
  `resume_lead_id` int(11) NOT NULL,
  `statut_client_id` int(11) DEFAULT NULL,
  `pays_production_id` int(11) NOT NULL,
  `pays_facturation_id` int(11) NOT NULL,
  `num_version` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `titre` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse_facturation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `diffusions` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `cgv` longtext COLLATE utf8mb4_unicode_ci,
  `cdc` longtext COLLATE utf8mb4_unicode_ci,
  `resume_prestation` longtext COLLATE utf8mb4_unicode_ci,
  `date_create` datetime DEFAULT NULL,
  `date_modification` datetime DEFAULT NULL,
  `mode_reglement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `delais_paiment` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `marge_cible` decimal(10,2) DEFAULT NULL,
  `statut_lead` int(11) DEFAULT NULL,
  `uniq_id` varchar(50) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date_signature` datetime DEFAULT NULL,
  `signature_package_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_bdc` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `id_mere` int(11) DEFAULT NULL,
  `signature_package_com_id` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `destinataire_signataire` longtext COLLATE utf8mb4_unicode_ci COMMENT '(DC2Type:array)',
  `destinataire_facture` longtext COLLATE utf8mb4_unicode_ci COMMENT '(DC2Type:array)',
  `client_irm_id` int(11) DEFAULT NULL,
  `description_globale` longtext COLLATE utf8mb4_unicode_ci,
  PRIMARY KEY (`id`),
  KEY `IDX_6138581DE7D306A2` (`societe_facturation_id`),
  KEY `IDX_6138581D4D79775F` (`tva_id`),
  KEY `IDX_6138581DF4445056` (`devise_id`),
  KEY `IDX_6138581D3615FA65` (`resume_lead_id`),
  KEY `IDX_6138581DB845FCE3` (`statut_client_id`),
  KEY `IDX_6138581DDD21E7CC` (`pays_production_id`),
  KEY `IDX_6138581D899CF741` (`pays_facturation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=47 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bdc`
--

INSERT INTO `bdc` (`id`, `societe_facturation_id`, `tva_id`, `devise_id`, `resume_lead_id`, `statut_client_id`, `pays_production_id`, `pays_facturation_id`, `num_version`, `titre`, `adresse_facturation`, `diffusions`, `date_debut`, `date_fin`, `cgv`, `cdc`, `resume_prestation`, `date_create`, `date_modification`, `mode_reglement`, `delais_paiment`, `marge_cible`, `statut_lead`, `uniq_id`, `date_signature`, `signature_package_id`, `num_bdc`, `id_mere`, `signature_package_com_id`, `destinataire_signataire`, `destinataire_facture`, `client_irm_id`, `description_globale`) VALUES
(1, 10, 2, 2, 1, NULL, 3, 3, '1_V1_2023-02-17', 'Titre bon de commande', NULL, 'juliodimbinirina@gmail.com;', '2023-01-26', NULL, NULL, NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2023-01-26 07:40:15', NULL, 'Prélèvement', '60 jours', '0.92', 6, '63d22e5fb92ef', NULL, NULL, '3.3.4.19.1', 1, NULL, 'a:1:{i:0;i:1;}', 'a:1:{i:0;i:1;}', NULL, 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable.'),
(2, 15, 3, 3, 1, NULL, 2, 2, '2_V1_2023-01-26', 'Titre bon de commande', NULL, 'juliodimbinirina@gmail.com;', '2023-01-26', NULL, NULL, NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2023-01-26 07:40:16', NULL, 'Prélèvement', '30 jours', '0.63', 14, '63d22e6085dfa', NULL, NULL, '2.2.3.19.2', 2, NULL, 'a:1:{i:0;i:1;}', 'a:1:{i:0;i:1;}', NULL, 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable.'),
(3, 6, 1, 1, 1, NULL, 1, 1, '3_V1_2023-01-26', 'Titre bon de commande', NULL, 'juliodimbinirina@gmail.com;', '2023-01-26', NULL, NULL, NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2023-01-26 07:40:17', NULL, 'Virement', '30 jours', '0.67', 8, '63d22e61acd3c', NULL, NULL, '1.1.1.19.3', 3, NULL, 'a:1:{i:0;i:1;}', 'a:1:{i:0;i:1;}', NULL, 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable.'),
(4, 14, 3, 3, 2, NULL, 2, 2, '4_V1_2023-02-17', 'Titre bon de commande', NULL, 'juliodimbinirina@gmail.com;', '2023-02-17', NULL, NULL, NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', '2023-02-17 07:38:10', NULL, 'Virement', '60 jours', '0.75', 8, '63ef2ee2c2769', NULL, NULL, '2.2.1.19.4', 4, NULL, 'a:1:{i:0;i:1;}', 'a:1:{i:0;i:1;}', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry'),
(5, 6, 1, 1, 3, NULL, 1, 1, '5_V1_2023-02-24', 'Titre bon de commande', NULL, 'juliodimbinirina@gmail.com;', '2023-02-24', NULL, NULL, NULL, NULL, '2023-02-24 12:03:10', NULL, 'Virement', '30 jours', '0.21', -1, '63f8a77ed27d0', NULL, NULL, '1.1.2.19.5', 5, NULL, 'a:1:{i:0;i:1;}', 'a:1:{i:0;i:1;}', NULL, 'Lorem ipsum'),
(6, 14, 3, 3, 3, NULL, 2, 2, '6_V1_2023-03-02', 'Titre bon de commande', NULL, 'juliodimbinirina@gmail.com;', '2023-02-24', NULL, NULL, NULL, NULL, '2023-02-24 12:03:11', NULL, 'Virement', '30 jours', '0.83', -1, '63f8a77f71eb9', NULL, NULL, '2.2.1.19.6', 6, NULL, 'a:1:{i:0;i:1;}', 'a:1:{i:0;i:1;}', NULL, 'Lorem ipsum'),
(7, 4, 4, 4, 4, NULL, 4, 4, '7_V1_2023-03-07', 'Titre bon de commande', NULL, 'juliodimbinirina@gmail.com;', '2023-03-07', NULL, NULL, NULL, NULL, '2023-03-07 11:13:06', NULL, 'Virement', '60 jours', '0.92', -1, '64071c4250ba3', NULL, NULL, '4.4.2.19.7', 7, NULL, 'a:1:{i:0;i:1;}', 'a:1:{i:0;i:1;}', NULL, 'There are many variations of passages of Lorem Ipsum available, but the majority have suffered alteration in some form, by injected humour, or randomised words which don\'t look even slightly believable.'),
(8, NULL, NULL, NULL, 5, NULL, 1, 1, NULL, 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-07', NULL, NULL, NULL, NULL, '2023-03-07 11:32:58', NULL, NULL, NULL, NULL, NULL, '640720ea51d05', NULL, NULL, NULL, NULL, NULL, 'a:0:{}', 'a:0:{}', NULL, NULL),
(9, NULL, NULL, NULL, 5, NULL, 2, 2, NULL, 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-07', NULL, NULL, NULL, NULL, '2023-03-07 11:32:58', NULL, NULL, NULL, NULL, NULL, '640720eaabcc9', NULL, NULL, NULL, NULL, NULL, 'a:0:{}', 'a:0:{}', NULL, NULL),
(10, NULL, NULL, NULL, 5, NULL, 3, 3, NULL, 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-07', NULL, NULL, NULL, NULL, '2023-03-07 11:32:58', NULL, NULL, NULL, NULL, NULL, '640720eaeacd0', NULL, NULL, NULL, NULL, NULL, 'a:0:{}', 'a:0:{}', NULL, NULL),
(11, 6, 1, 1, 6, NULL, 1, 1, '11_V1_2023-03-08', 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, NULL, '2023-03-08 08:56:05', NULL, 'Virement', '30 jours', '0.24', -1, '64084da5eb5dc', NULL, NULL, '1.1.2.19.11', 11, NULL, 'a:1:{i:0;i:2;}', 'a:1:{i:0;i:2;}', NULL, 'Mon teste tet svdgvdfy'),
(12, NULL, NULL, NULL, 6, NULL, 2, 2, NULL, 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, NULL, '2023-03-08 08:56:06', NULL, NULL, NULL, NULL, NULL, '64084da6696f5', NULL, NULL, NULL, NULL, NULL, 'a:0:{}', 'a:0:{}', NULL, NULL),
(13, 10, 2, 2, 7, NULL, 3, 3, '13_V1_2023-03-08', 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, NULL, '2023-03-08 09:02:27', NULL, 'Virement', '60 jours', '0.91', -1, '64084f23ce7da', NULL, NULL, '3.3.1.19.13', 13, NULL, 'a:1:{i:0;i:2;}', 'a:1:{i:0;i:2;}', NULL, 'reuzeiojhrtioerhuiohiheruio djydudfh'),
(14, NULL, NULL, NULL, 7, NULL, 2, 2, NULL, 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, NULL, '2023-03-08 09:02:28', NULL, NULL, NULL, NULL, NULL, '64084f245f5d4', NULL, NULL, NULL, NULL, NULL, 'a:0:{}', 'a:0:{}', NULL, NULL),
(15, 6, 1, 1, 8, NULL, 1, 1, '15_V1_2023-03-08', 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, NULL, '2023-03-08 09:18:03', NULL, 'Prélèvement', '30 jours', '0.25', -1, '640852cbf021e', NULL, NULL, '1.1.1.19.15', 15, NULL, 'a:1:{i:0;i:2;}', 'a:1:{i:0;i:2;}', NULL, 'sdkdhgudid fgijddfiodfh kjffkfd'),
(16, 2, 2, 2, 8, NULL, 3, 3, '16_V1_2023-03-08', 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, NULL, '2023-03-08 09:18:04', NULL, 'Prélèvement', '60 jours', '0.94', -1, '640852cc596d4', NULL, NULL, '3.3.2.19.16', 16, NULL, 'a:1:{i:0;i:2;}', 'a:1:{i:0;i:2;}', NULL, 'sg jsdgysdfgsdudgyqsgiudsisdfhidfuhidfshi'),
(17, 6, 1, 1, 9, NULL, 1, 1, '17_V1_2023-03-08', 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-07', NULL, NULL, NULL, NULL, '2023-03-08 11:01:47', NULL, 'Virement', '60 jours', '0.13', -1, '64086b1b51f22', NULL, NULL, '1.1.1.19.17', 17, NULL, 'a:1:{i:0;i:2;}', 'a:1:{i:0;i:2;}', NULL, 'Mon lorem teste bla bla bla'),
(18, 14, 3, 3, 9, NULL, 2, 2, '18_V1_2023-03-08', 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-07', NULL, NULL, NULL, NULL, '2023-03-08 11:01:47', NULL, 'Virement', '30 jours', '0.83', -1, '64086b1bbd634', NULL, NULL, '2.2.2.19.18', 18, NULL, 'a:1:{i:0;i:2;}', 'a:1:{i:0;i:2;}', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry'),
(19, 10, 2, 3, 9, NULL, 3, 3, '19_V1_2023-03-08', 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-07', NULL, NULL, NULL, NULL, '2023-03-08 11:01:48', NULL, 'Prélèvement', '30 jours', '0.97', -1, '64086b1c22daa', NULL, NULL, '3.3.4.19.19', 19, NULL, 'a:1:{i:0;i:2;}', 'a:1:{i:0;i:2;}', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry'),
(20, NULL, NULL, NULL, 10, NULL, 1, 1, NULL, 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-07', NULL, NULL, NULL, NULL, '2023-03-08 12:24:57', NULL, NULL, NULL, NULL, NULL, '64087e999ce1a', NULL, NULL, NULL, NULL, NULL, 'a:0:{}', 'a:0:{}', NULL, NULL),
(21, NULL, NULL, NULL, 10, NULL, 2, 2, NULL, 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-07', NULL, NULL, NULL, NULL, '2023-03-08 12:24:58', NULL, NULL, NULL, NULL, NULL, '64087e9a0a033', NULL, NULL, NULL, NULL, NULL, 'a:0:{}', 'a:0:{}', NULL, NULL),
(22, NULL, NULL, NULL, 10, NULL, 3, 3, NULL, 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-07', NULL, NULL, NULL, NULL, '2023-03-08 12:24:58', NULL, NULL, NULL, NULL, NULL, '64087e9a596c8', NULL, NULL, NULL, NULL, NULL, 'a:0:{}', 'a:0:{}', NULL, NULL),
(23, 14, 3, 3, 11, NULL, 2, 2, '23_V1_2023-03-08', 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2023-03-08 13:58:32', NULL, 'Virement', '60 jours', '0.80', -1, '6408948874a3d', NULL, NULL, '2.2.2.19.23', 23, NULL, 'a:1:{i:0;i:2;}', 'a:1:{i:0;i:2;}', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'),
(24, 2, 2, 2, 11, NULL, 3, 3, '24_V1_2023-03-08', 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2023-03-08 13:58:32', NULL, 'Virement', '45 jours', '0.92', 12, '64089488e3020', NULL, NULL, '3.3.1.19.24', 24, NULL, 'a:1:{i:0;i:2;}', 'a:1:{i:0;i:2;}', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'),
(41, 2, 2, 2, 11, NULL, 3, 3, '24_V2_2023-03-09', 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2023-03-08 13:58:32', '2023-03-09 13:54:02', 'Virement', '45 jours', '0.92', 13, '6409e4fad2bd6', NULL, NULL, '3.3.1.19.24', 24, NULL, 'a:1:{i:0;i:2;}', 'a:1:{i:0;i:2;}', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'),
(42, 2, NULL, NULL, 11, NULL, 1, 1, NULL, 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2023-03-09 13:54:08', '2023-03-09 13:54:08', NULL, NULL, NULL, NULL, '6409e5003a3fd', NULL, NULL, NULL, NULL, NULL, 'a:0:{}', 'a:0:{}', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'),
(43, 2, 2, 2, 11, NULL, 3, 3, '24_V2_2023-03-09', 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2023-03-08 13:58:32', '2023-03-09 13:54:57', 'Virement', '45 jours', '0.92', 13, '6409e5312f98d', NULL, NULL, '3.3.1.19.24', 24, NULL, 'a:1:{i:0;i:2;}', 'a:1:{i:0;i:2;}', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'),
(44, 2, NULL, NULL, 11, NULL, 1, 1, NULL, 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2023-03-09 13:55:02', '2023-03-09 13:55:02', NULL, NULL, NULL, NULL, '6409e536a6d25', NULL, NULL, NULL, NULL, NULL, 'a:0:{}', 'a:0:{}', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'),
(45, 2, 2, 2, 11, NULL, 3, 3, '24_V2_2023-03-09', 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2023-03-08 13:58:32', '2023-03-09 13:56:07', 'Virement', '45 jours', '0.92', 13, '6409e577716a4', NULL, NULL, '3.3.1.19.24', 24, NULL, 'a:1:{i:0;i:2;}', 'a:1:{i:0;i:2;}', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.'),
(46, 6, 1, 1, 11, NULL, 1, 1, '46_V1_2023-03-09', 'Titre bon de commande', NULL, 'parcoursclients.outsourcia@gmail.com;', '2023-03-08', NULL, NULL, NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '2023-03-09 13:56:12', '2023-03-09 13:56:12', 'Virement', '30 jours', '0.59', -1, '6409e57ce44fe', NULL, NULL, '1.1.4.19.46', 46, NULL, 'a:1:{i:0;i:2;}', 'a:1:{i:0;i:2;}', NULL, 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.');

-- --------------------------------------------------------

--
-- Structure de la table `bdc_document`
--

DROP TABLE IF EXISTS `bdc_document`;
CREATE TABLE IF NOT EXISTS `bdc_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bdc_id` int(11) NOT NULL,
  `date_signature` date DEFAULT NULL,
  `date_debut_prise_compte` date DEFAULT NULL,
  `date_fin_prise_compte` date DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_document_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_75C1B58928DF9AB0` (`bdc_id`),
  KEY `IDX_75C1B5898826AFA6` (`type_document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `bdc_operation`
--

DROP TABLE IF EXISTS `bdc_operation`;
CREATE TABLE IF NOT EXISTS `bdc_operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bdc_id` int(11) NOT NULL,
  `operation_id` int(11) NOT NULL,
  `langue_trt_id` int(11) DEFAULT NULL,
  `type_facturation_id` int(11) DEFAULT NULL,
  `famille_operation_id` int(11) DEFAULT NULL,
  `bu_id` int(11) DEFAULT NULL,
  `cout_horaire_id` int(11) DEFAULT NULL,
  `quantite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prix_unit` decimal(10,2) DEFAULT NULL,
  `irm` smallint(6) DEFAULT NULL,
  `si_renta` smallint(6) DEFAULT NULL,
  `sage` smallint(6) DEFAULT NULL,
  `tarif_horaire_cible` decimal(10,0) DEFAULT NULL,
  `objectif` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `temps_productifs` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dmt` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tarif_horaire_formation` decimal(10,0) DEFAULT NULL,
  `volume_atraite` int(11) DEFAULT NULL,
  `categorie_lead` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prod_par_heure` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tarif_id` int(11) DEFAULT NULL,
  `irm_operation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `avenant` int(11) DEFAULT NULL,
  `value_hno` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `is_hno_dimanche` int(11) DEFAULT NULL,
  `is_hno_hors_dimanche` int(11) DEFAULT NULL,
  `majorite_hno_dimanche` int(11) DEFAULT NULL,
  `majorite_hno_hors_dimanche` int(11) DEFAULT NULL,
  `offert` int(11) DEFAULT NULL,
  `duree` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ressource_former` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nb_heure_mensuel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nb_etp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_param_performed` smallint(6) DEFAULT NULL,
  `uniq_bdc_fq_operation` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `old_prix_unit` decimal(10,2) DEFAULT NULL,
  `encoded_image` longtext COLLATE utf8mb4_unicode_ci,
  `productivite_acte` decimal(10,2) DEFAULT NULL,
  `quantite_acte` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantite_heure` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prix_unitaire_acte` decimal(10,2) DEFAULT NULL,
  `prix_unitaire_heure` decimal(10,2) DEFAULT NULL,
  `applicatif_date` date DEFAULT NULL,
  `designation_acte_id` int(11) DEFAULT NULL,
  `old_prix_unit_heure` decimal(10,2) DEFAULT NULL,
  `old_prix_unit_acte` decimal(10,2) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_342EE1DF28DF9AB0` (`bdc_id`),
  KEY `IDX_342EE1DF44AC3583` (`operation_id`),
  KEY `IDX_342EE1DF800DC1FD` (`langue_trt_id`),
  KEY `IDX_342EE1DF8D06DB10` (`type_facturation_id`),
  KEY `IDX_342EE1DF9EFA7AED` (`famille_operation_id`),
  KEY `IDX_342EE1DFE0319FBC` (`bu_id`),
  KEY `IDX_342EE1DF8E47663F` (`cout_horaire_id`),
  KEY `IDX_342EE1DF357C0A59` (`tarif_id`),
  KEY `IDX_342EE1DFCB40E30D` (`designation_acte_id`)
) ENGINE=InnoDB AUTO_INCREMENT=464 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bdc_operation`
--

INSERT INTO `bdc_operation` (`id`, `bdc_id`, `operation_id`, `langue_trt_id`, `type_facturation_id`, `famille_operation_id`, `bu_id`, `cout_horaire_id`, `quantite`, `prix_unit`, `irm`, `si_renta`, `sage`, `tarif_horaire_cible`, `objectif`, `temps_productifs`, `dmt`, `tarif_horaire_formation`, `volume_atraite`, `categorie_lead`, `prod_par_heure`, `tarif_id`, `irm_operation`, `avenant`, `value_hno`, `description`, `is_hno_dimanche`, `is_hno_hors_dimanche`, `majorite_hno_dimanche`, `majorite_hno_hors_dimanche`, `offert`, `duree`, `ressource_former`, `nb_heure_mensuel`, `nb_etp`, `is_param_performed`, `uniq_bdc_fq_operation`, `old_prix_unit`, `encoded_image`, `productivite_acte`, `quantite_acte`, `quantite_heure`, `prix_unitaire_acte`, `prix_unitaire_heure`, `applicatif_date`, `designation_acte_id`, `old_prix_unit_heure`, `old_prix_unit_acte`) VALUES
(1, 1, 680, 1, 5, 19, 4, 61, '1848', '24.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, 'Oui', NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '66', '28', 1, '1674718384047', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 1, 680, 1, 5, NULL, 4, 61, NULL, '42.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 75, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(3, 1, 680, 1, 5, NULL, 4, 61, NULL, '36.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 50, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 1, 16, 1, 3, NULL, 4, 61, '585', '12.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '15', '39', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(5, 1, 17, 1, 3, NULL, 4, 61, NULL, '23.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(6, 1, 13, 1, 3, NULL, 4, 61, NULL, '10.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 1, 1, NULL, 5, NULL, NULL, NULL, NULL, '19.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 1, 12, NULL, 3, NULL, NULL, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 1, 14, NULL, 5, NULL, NULL, NULL, NULL, '15.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(11, 1, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 1, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 2, 634, 1, 3, 15, 3, 55, '1560', '17.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, '65', '24', 1, '1674718524598', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 2, 506, 2, 1, 11, 1, 50, '248', '34.90', 1, 1, 1, '54', NULL, '02:37:55', '01:42:03', NULL, 248, 'New Business', NULL, NULL, NULL, NULL, 'Oui', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1674718683355', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 2, 506, 2, 1, NULL, 1, 50, NULL, '51.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 50, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 2, 506, 2, 1, NULL, 1, 50, NULL, '56.10', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 65, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(17, 2, 16, 1, 3, NULL, 3, 55, '180', '14.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '9', '20', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(18, 2, 16, 2, 3, NULL, 3, 56, '396', '15.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '12', '33', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(19, 2, 17, 1, 3, NULL, 3, 55, NULL, '19.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(20, 2, 17, 2, 3, NULL, 3, 56, NULL, '14.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 2, 13, 1, 3, NULL, 3, 55, NULL, '11.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(22, 2, 13, 2, 3, NULL, 3, 56, NULL, '11.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(23, 2, 1, NULL, 5, NULL, NULL, NULL, NULL, '7.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 2, 12, NULL, 3, NULL, NULL, 56, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(25, 2, 14, NULL, 5, NULL, NULL, NULL, NULL, '9.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(27, 2, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 2, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(29, 3, 576, 1, 7, 12, 1, 39, NULL, NULL, 1, 1, 1, '66', NULL, NULL, NULL, NULL, 1010, 'New Business', NULL, NULL, NULL, NULL, 'Oui', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '74', '21', 1, '1674718798545', NULL, NULL, '0.65', '1010.1', '1554', '53.85', '31.00', NULL, 498, NULL, NULL),
(30, 3, 576, 1, 1, NULL, 1, 39, NULL, '92.75', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 75, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(31, 3, 576, 1, 1, NULL, 1, 39, NULL, '98.05', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 85, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 3, 576, 1, 3, NULL, 1, 39, NULL, '54.25', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 75, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(33, 3, 576, 1, 3, NULL, 1, 39, NULL, '57.35', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 85, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 3, 16, 1, 3, NULL, 1, 39, '450', '13.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '10', '45', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(35, 3, 17, 1, 3, NULL, 1, 39, NULL, '16.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 3, 13, 1, 3, NULL, 1, 39, NULL, '21.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 3, 1, NULL, 5, NULL, NULL, NULL, NULL, '15.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 3, 12, NULL, 3, NULL, NULL, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(39, 3, 14, NULL, 5, NULL, NULL, NULL, NULL, '19.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(41, 3, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(42, 3, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(43, 4, 578, 2, 3, 12, 1, 50, '1496', '29.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, 'Oui', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '68', '22', 1, '1676619485277', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(44, 4, 578, 2, 3, NULL, 1, 50, NULL, '53.65', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 85, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(45, 4, 578, 2, 3, NULL, 1, 50, NULL, '46.40', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 60, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(46, 4, 16, 2, 3, NULL, 1, 50, '630', '14.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '18', '35', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(47, 4, 17, 2, 3, NULL, 1, 50, NULL, '15.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(48, 4, 13, 2, 3, NULL, 1, 50, NULL, '11.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(49, 4, 1, NULL, 5, NULL, NULL, NULL, NULL, '13.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(50, 4, 12, NULL, 3, NULL, NULL, 50, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(51, 4, 14, NULL, 5, NULL, NULL, NULL, NULL, '13.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(53, 4, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(54, 4, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(55, 5, 382, 1, 3, 9, 2, 37, '1240', '24.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '62', '20', 1, '1677240130923', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(56, 5, 16, 1, 3, NULL, 2, 37, '630', '17.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '14', '45', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(57, 5, 17, 1, 3, NULL, 2, 37, NULL, '26.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(58, 5, 13, 1, 3, NULL, 2, 37, NULL, '13.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(59, 5, 1, NULL, 5, NULL, NULL, NULL, NULL, '16.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(60, 5, 12, NULL, 3, NULL, NULL, 37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(61, 5, 14, NULL, 5, NULL, NULL, NULL, NULL, '14.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(63, 5, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(64, 5, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(65, 6, 497, 1, 1, 11, 1, 49, '197', '63.43', 1, 1, 1, '89', NULL, '02:54:44', '02:04:32', NULL, 197, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1677240186088', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(66, 6, 16, 1, 3, NULL, 1, 49, '900', '24.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '25', '36', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(67, 6, 17, 1, 3, NULL, 1, 49, NULL, '20.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(68, 6, 13, 1, 3, NULL, 1, 49, NULL, '13.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(69, 6, 1, NULL, 5, NULL, NULL, NULL, NULL, '20.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(70, 6, 12, NULL, 3, NULL, NULL, 49, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(71, 6, 14, NULL, 5, NULL, NULL, NULL, NULL, '7.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(73, 6, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(74, 6, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(75, 7, 486, 1, 5, 10, 2, 70, '3960', '27.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, 'Oui', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '110', '36', 1, '1678187582381', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(76, 7, 486, 1, 5, NULL, 2, 70, NULL, '43.20', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, 60, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(77, 7, 486, 1, 5, NULL, 2, 70, NULL, '40.50', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', '', NULL, NULL, NULL, NULL, NULL, 1, NULL, 50, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(78, 7, 16, 1, 3, NULL, 2, 70, '2541', '16.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '33', '77', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(79, 7, 17, 1, 3, NULL, 2, 70, NULL, '11.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(80, 7, 13, 1, 3, NULL, 2, 70, NULL, '19.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(81, 7, 1, NULL, 5, NULL, NULL, NULL, NULL, '12.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(82, 7, 12, NULL, 3, NULL, NULL, 70, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(83, 7, 14, NULL, 5, NULL, NULL, NULL, NULL, '7.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(85, 7, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(86, 7, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(87, 8, 576, 1, 3, 12, 1, 39, '686', '30.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '49', '14', 1, '1678188421682', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(88, 8, 16, 1, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(89, 8, 17, 1, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(90, 8, 13, 1, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(91, 8, 1, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(92, 8, 12, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(93, 8, 14, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(94, 8, 1007, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(95, 8, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(96, 8, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(97, 9, 486, 1, 5, 10, 2, 42, '1449', '24.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '63', '23', 1, '1678188450020', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(98, 9, 16, 1, 3, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(99, 9, 17, 1, 3, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(100, 9, 13, 1, 3, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(101, 9, 1, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(102, 9, 12, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(103, 9, 14, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(104, 9, 1007, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(105, 9, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(106, 9, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(107, 10, 678, 1, 1, 17, 4, 61, '202', '30.86', 1, 1, 1, '75', NULL, '03:55:55', '01:37:04', NULL, 202, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1678188521603', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(108, 10, 16, 1, 3, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(109, 10, 17, 1, 3, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(110, 10, 13, 1, 3, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(111, 10, 1, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(112, 10, 12, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(113, 10, 14, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(114, 10, 1007, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(115, 10, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(116, 10, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(117, 11, 486, 1, 5, 10, 2, 37, '525', '24.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '35', '15', 1, '1678265731620', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(118, 11, 16, 1, 3, NULL, 2, 37, '432', '20.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '12', '36', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(119, 11, 17, 1, 3, NULL, 2, 37, NULL, '17.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(120, 11, 13, 1, 3, NULL, 2, 37, NULL, '17.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(121, 11, 1, NULL, 5, NULL, NULL, NULL, NULL, '23.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(122, 11, 12, NULL, 3, NULL, NULL, 37, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(123, 11, 14, NULL, 5, NULL, NULL, NULL, NULL, '28.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(125, 11, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(126, 11, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(127, 12, 596, 1, 5, 13, 1, 49, '1430', '24.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '65', '22', 1, '1678265755887', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(128, 12, 16, 1, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(129, 12, 17, 1, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(130, 12, 13, 1, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(131, 12, 1, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(132, 12, 12, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(133, 12, 14, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(134, 12, 1007, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(135, 12, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(136, 12, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(137, 13, 596, 1, 5, 13, 1, 63, '680', '31.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '40', '17', 1, '1678266062487', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(138, 13, 16, 1, 3, NULL, 1, 63, '945', '15.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '21', '45', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(139, 13, 17, 1, 3, NULL, 1, 63, NULL, '19.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(140, 13, 13, 1, 3, NULL, 1, 63, NULL, '24.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(141, 13, 1, NULL, 5, NULL, NULL, NULL, NULL, '20.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(142, 13, 12, NULL, 3, NULL, NULL, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(143, 13, 14, NULL, 5, NULL, NULL, NULL, NULL, '18.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(145, 13, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(146, 13, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(147, 14, 680, 1, 5, 19, 4, 41, '950', '25.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '50', '19', 1, '1678266144744', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(148, 14, 16, 1, 3, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(149, 14, 17, 1, 3, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(150, 14, 13, 1, 3, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(151, 14, 1, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(152, 14, 12, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(153, 14, 14, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(154, 14, 1007, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(155, 14, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(156, 14, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(157, 15, 596, 1, 5, 13, 1, 39, '2465', '22.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '85', '29', 1, '1678267049118', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(158, 15, 16, 1, 3, NULL, 1, 39, '630', '21.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '14', '45', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(159, 15, 17, 1, 3, NULL, 1, 39, NULL, '25.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(160, 15, 13, 1, 3, NULL, 1, 39, NULL, '32.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(161, 15, 1, NULL, 5, NULL, NULL, NULL, NULL, '13.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(162, 15, 12, NULL, 3, NULL, NULL, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(163, 15, 14, NULL, 5, NULL, NULL, NULL, NULL, '19.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(165, 15, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(166, 15, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(167, 16, 486, 1, 5, 10, 2, 62, '1750', '25.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '70', '25', 1, '1678267074800', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(168, 16, 16, 1, 3, NULL, 2, 62, '287', '60.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '7', '41', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(169, 16, 17, 1, 3, NULL, 2, 62, NULL, '26.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(170, 16, 13, 1, 3, NULL, 2, 62, NULL, '21.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(171, 16, 1, NULL, 5, NULL, NULL, NULL, NULL, '26.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(172, 16, 12, NULL, 3, NULL, NULL, 62, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(173, 16, 14, NULL, 5, NULL, NULL, NULL, NULL, '15.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(175, 16, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(176, 16, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(177, 17, 576, 1, 3, 12, 1, 39, '686', '30.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '49', '14', 1, '1678188421682', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(178, 17, 16, 1, 3, NULL, 1, 39, '756', '14.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '12', '63', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(179, 17, 17, 1, 3, NULL, 1, 39, NULL, '18.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(180, 17, 13, 1, 3, NULL, 1, 39, NULL, '11.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(181, 17, 1, NULL, 5, NULL, NULL, NULL, NULL, '13.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(182, 17, 12, NULL, 3, NULL, NULL, 39, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(183, 17, 14, NULL, 5, NULL, NULL, NULL, NULL, '14.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(185, 17, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(186, 17, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(187, 18, 486, 1, 5, 10, 2, 42, '1449', '24.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '63', '23', 1, '1678188450020', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(188, 18, 16, 1, 3, NULL, 2, 42, '288', '19.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '8', '36', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(189, 18, 17, 1, 3, NULL, 2, 42, NULL, '16.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(190, 18, 13, 1, 3, NULL, 2, 42, NULL, '17.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(191, 18, 1, NULL, 5, NULL, NULL, NULL, NULL, '23.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
INSERT INTO `bdc_operation` (`id`, `bdc_id`, `operation_id`, `langue_trt_id`, `type_facturation_id`, `famille_operation_id`, `bu_id`, `cout_horaire_id`, `quantite`, `prix_unit`, `irm`, `si_renta`, `sage`, `tarif_horaire_cible`, `objectif`, `temps_productifs`, `dmt`, `tarif_horaire_formation`, `volume_atraite`, `categorie_lead`, `prod_par_heure`, `tarif_id`, `irm_operation`, `avenant`, `value_hno`, `description`, `is_hno_dimanche`, `is_hno_hors_dimanche`, `majorite_hno_dimanche`, `majorite_hno_hors_dimanche`, `offert`, `duree`, `ressource_former`, `nb_heure_mensuel`, `nb_etp`, `is_param_performed`, `uniq_bdc_fq_operation`, `old_prix_unit`, `encoded_image`, `productivite_acte`, `quantite_acte`, `quantite_heure`, `prix_unitaire_acte`, `prix_unitaire_heure`, `applicatif_date`, `designation_acte_id`, `old_prix_unit_heure`, `old_prix_unit_acte`) VALUES
(192, 18, 12, NULL, 3, NULL, NULL, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(193, 18, 14, NULL, 5, NULL, NULL, NULL, NULL, '28.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(195, 18, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(196, 18, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(197, 19, 678, 1, 1, 17, 4, 61, '202', '30.86', 1, 1, 1, '75', NULL, '03:55:55', '01:37:04', NULL, 202, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1678188521603', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(198, 19, 16, 1, 3, NULL, 4, 61, '784', '41.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '14', '56', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(199, 19, 17, 1, 3, NULL, 4, 61, NULL, '25.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(200, 19, 13, 1, 3, NULL, 4, 61, NULL, '14.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(201, 19, 1, NULL, 5, NULL, NULL, NULL, NULL, '29.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(202, 19, 12, NULL, 3, NULL, NULL, 61, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(203, 19, 14, NULL, 5, NULL, NULL, NULL, NULL, '19.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(205, 19, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(206, 19, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(207, 20, 576, 1, 3, 12, 1, 39, '686', '30.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '49', '14', 1, '1678188421682', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(208, 20, 16, 1, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(209, 20, 17, 1, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(210, 20, 13, 1, 3, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(211, 20, 1, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(212, 20, 12, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(213, 20, 14, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(214, 20, 1007, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(215, 20, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(216, 20, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(217, 21, 486, 1, 5, 10, 2, 42, '1449', '24.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '63', '23', 1, '1678188450020', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(218, 21, 16, 1, 3, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(219, 21, 17, 1, 3, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(220, 21, 13, 1, 3, NULL, 2, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(221, 21, 1, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(222, 21, 12, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(223, 21, 14, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(224, 21, 1007, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(225, 21, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(226, 21, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(227, 22, 678, 1, 1, 17, 4, 61, '202', '30.86', 1, 1, 1, '75', NULL, '03:55:55', '01:37:04', NULL, 202, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1678188521603', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(228, 22, 16, 1, 3, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(229, 22, 17, 1, 3, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(230, 22, 13, 1, 3, NULL, 4, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(231, 22, 1, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(232, 22, 12, NULL, 3, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(233, 22, 14, NULL, 5, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(234, 22, 1007, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(235, 22, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(236, 22, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(237, 23, 382, 1, 3, 9, 2, 42, '3640', '20.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '104', '35', 1, '1678283848231', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(238, 23, 16, 1, 3, NULL, 2, 42, '804', '17.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '12', '67', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(239, 23, 17, 1, 3, NULL, 2, 42, NULL, '17.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(240, 23, 13, 1, 3, NULL, 2, 42, NULL, '14.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(241, 23, 1, NULL, 5, NULL, NULL, NULL, NULL, '18.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(242, 23, 12, NULL, 3, NULL, NULL, 42, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(243, 23, 14, NULL, 5, NULL, NULL, NULL, NULL, '14.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(245, 23, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(246, 23, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(247, 24, 596, 1, 5, 13, 1, 63, '1720', '26.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '86', '20', 1, '1678283901289', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(248, 24, 16, 1, 3, NULL, 1, 63, '780', '18.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '13', '60', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(249, 24, 17, 1, 3, NULL, 1, 63, NULL, '12.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(250, 24, 13, 1, 3, NULL, 1, 63, NULL, '20.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(251, 24, 1, NULL, 5, NULL, NULL, NULL, NULL, '9.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(252, 24, 12, NULL, 3, NULL, NULL, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(253, 24, 14, NULL, 5, NULL, NULL, NULL, NULL, '21.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(255, 24, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(256, 24, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(422, 41, 596, 1, 5, 13, 1, 63, '1720', '34.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '86', '20', 1, '1678283901289', '26.00', NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-20', NULL, NULL, NULL),
(423, 41, 16, 1, 3, NULL, 1, 63, '780', '18.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '13', '60', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(424, 41, 17, 1, 3, NULL, 1, 63, NULL, '12.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(425, 41, 13, 1, 3, NULL, 1, 63, NULL, '20.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(426, 41, 1, NULL, 5, NULL, NULL, NULL, NULL, '9.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(427, 41, 12, NULL, 3, NULL, NULL, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(428, 41, 14, NULL, 5, NULL, NULL, NULL, NULL, '21.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(429, 41, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(430, 41, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(431, 41, 676, 1, 1, 17, 4, 38, '238', '44.10', 1, 1, 1, '54', NULL, '02:32:44', '02:04:44', NULL, 238, 'New Business', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1678369699401', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(432, 42, 676, 1, 1, 17, 4, 38, '238', '44.10', 1, 1, 1, '54', NULL, '02:32:44', '02:04:44', NULL, 238, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1678369699401', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(433, 43, 596, 1, 5, 13, 1, 63, '1720', '34.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '86', '20', 1, '1678283901289', '26.00', NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-20', NULL, NULL, NULL),
(434, 43, 16, 1, 3, NULL, 1, 63, '780', '18.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '13', '60', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(435, 43, 17, 1, 3, NULL, 1, 63, NULL, '12.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(436, 43, 13, 1, 3, NULL, 1, 63, NULL, '20.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(437, 43, 1, NULL, 5, NULL, NULL, NULL, NULL, '9.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(438, 43, 12, NULL, 3, NULL, NULL, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(439, 43, 14, NULL, 5, NULL, NULL, NULL, NULL, '21.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(440, 43, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(441, 43, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(442, 43, 676, 1, 1, 17, 4, 38, '238', '44.10', 1, 1, 1, '54', NULL, '02:32:44', '02:04:44', NULL, 238, 'New Business', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1678369699401', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(443, 44, 676, 1, 1, 17, 4, 38, '238', '44.10', 1, 1, 1, '54', NULL, '02:32:44', '02:04:44', NULL, 238, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1678369699401', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(444, 45, 596, 1, 5, 13, 1, 63, '1720', '34.00', 1, 1, 1, NULL, NULL, NULL, NULL, NULL, NULL, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '86', '20', 1, '1678283901289', '26.00', NULL, NULL, NULL, NULL, NULL, NULL, '2023-03-20', NULL, NULL, NULL),
(445, 45, 16, 1, 3, NULL, 1, 63, '780', '18.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '13', '60', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(446, 45, 17, 1, 3, NULL, 1, 63, NULL, '12.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(447, 45, 13, 1, 3, NULL, 1, 63, NULL, '20.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(448, 45, 1, NULL, 5, NULL, NULL, NULL, NULL, '9.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(449, 45, 12, NULL, 3, NULL, NULL, 63, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(450, 45, 14, NULL, 5, NULL, NULL, NULL, NULL, '21.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(451, 45, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(452, 45, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(453, 45, 676, 1, 1, 17, 4, 38, '238', '44.10', 1, 1, 1, '54', NULL, '02:32:44', '02:04:44', NULL, 238, 'New Business', NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1678369699401', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(454, 46, 676, 1, 1, 17, 4, 38, '238', '44.10', 1, 1, 1, '54', NULL, '02:32:44', '02:04:44', NULL, 238, 'New Business', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, '1678369699401', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(455, 46, 16, 1, 3, NULL, 4, 38, '784', '35.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, '14', '56', NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(456, 46, 17, 1, 3, NULL, 4, 38, NULL, '36.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(457, 46, 13, 1, 3, NULL, 4, 38, NULL, '28.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(458, 46, 1, NULL, 5, NULL, NULL, NULL, NULL, '22.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(459, 46, 12, NULL, 3, NULL, NULL, 38, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(460, 46, 14, NULL, 5, NULL, NULL, NULL, NULL, '20.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(462, 46, 15, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(463, 46, 1004, NULL, 1, NULL, NULL, NULL, NULL, '1.00', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `bdc_operation_objectif_qualitatif`
--

DROP TABLE IF EXISTS `bdc_operation_objectif_qualitatif`;
CREATE TABLE IF NOT EXISTS `bdc_operation_objectif_qualitatif` (
  `bdc_operation_id` int(11) NOT NULL,
  `objectif_qualitatif_id` int(11) NOT NULL,
  PRIMARY KEY (`bdc_operation_id`,`objectif_qualitatif_id`),
  KEY `IDX_9115D9877B27786B` (`bdc_operation_id`),
  KEY `IDX_9115D98784C558C9` (`objectif_qualitatif_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bdc_operation_objectif_qualitatif`
--

INSERT INTO `bdc_operation_objectif_qualitatif` (`bdc_operation_id`, `objectif_qualitatif_id`) VALUES
(1, 2),
(13, 3),
(14, 3),
(29, 2),
(43, 2),
(75, 4),
(237, 2);

-- --------------------------------------------------------

--
-- Structure de la table `bdc_operation_objectif_quantitatif`
--

DROP TABLE IF EXISTS `bdc_operation_objectif_quantitatif`;
CREATE TABLE IF NOT EXISTS `bdc_operation_objectif_quantitatif` (
  `bdc_operation_id` int(11) NOT NULL,
  `objectif_quantitatif_id` int(11) NOT NULL,
  PRIMARY KEY (`bdc_operation_id`,`objectif_quantitatif_id`),
  KEY `IDX_A25B503F7B27786B` (`bdc_operation_id`),
  KEY `IDX_A25B503F899CB9C9` (`objectif_quantitatif_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bdc_operation_objectif_quantitatif`
--

INSERT INTO `bdc_operation_objectif_quantitatif` (`bdc_operation_id`, `objectif_quantitatif_id`) VALUES
(1, 7),
(13, 3),
(14, 1),
(29, 4),
(43, 3),
(75, 1),
(237, 1);

-- --------------------------------------------------------

--
-- Structure de la table `bu`
--

DROP TABLE IF EXISTS `bu`;
CREATE TABLE IF NOT EXISTS `bu` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `bu`
--

INSERT INTO `bu` (`id`, `libelle`) VALUES
(1, 'OUTBOUND'),
(2, 'INBOUND'),
(3, 'HELP DESK'),
(4, 'BPO'),
(5, 'REDACTION'),
(6, 'DIGITAL'),
(7, 'DEV INFO'),
(8, 'ETUDES'),
(9, 'AUTRES'),
(10, 'DATA'),
(11, 'SANTE RDV'),
(12, 'SANTE SAISIE');

-- --------------------------------------------------------

--
-- Structure de la table `budget_annuel`
--

DROP TABLE IF EXISTS `budget_annuel`;
CREATE TABLE IF NOT EXISTS `budget_annuel` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `annee` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `bu` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_annuel_nplus_un` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_janvier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_fevrier` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_mars` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_avril` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_mai` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_juin` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_juillet` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_aout` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_septembre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_octobre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_novembre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_decembre` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `categorie_client`
--

DROP TABLE IF EXISTS `categorie_client`;
CREATE TABLE IF NOT EXISTS `categorie_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `categorie_client`
--

INSERT INTO `categorie_client` (`id`, `libelle`) VALUES
(1, 'Prospect'),
(2, 'Client'),
(3, 'Client perdu');

-- --------------------------------------------------------

--
-- Structure de la table `client_document`
--

DROP TABLE IF EXISTS `client_document`;
CREATE TABLE IF NOT EXISTS `client_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `date_signature` date DEFAULT NULL,
  `date_debut_prise_compte` date DEFAULT NULL,
  `date_fin_prise_compte` date DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `type_document_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F68FBAB39395C3F3` (`customer_id`),
  KEY `IDX_F68FBAB38826AFA6` (`type_document_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `contact`
--

DROP TABLE IF EXISTS `contact`;
CREATE TABLE IF NOT EXISTS `contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `civilite` varchar(45) COLLATE utf8mb4_unicode_ci NOT NULL,
  `nom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prenom` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fonction` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `skype` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_copie_facture` int(11) DEFAULT NULL,
  `status` tinyint(1) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_4C62E6389395C3F3` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contact`
--

INSERT INTO `contact` (`id`, `customer_id`, `civilite`, `nom`, `prenom`, `fonction`, `tel`, `email`, `skype`, `is_copie_facture`, `status`) VALUES
(1, 1, 'Monsieur', 'Philibert', 'Dani', 'Comptable', '420545494894', 'juliodimbinirina@gmail.com', 'Dani Phil', 1, 0),
(2, 2, 'Monsieur', 'Jones', 'Phil', 'Photographe', '5554084845106', 'parcoursclients.outsourcia@gmail.com', 'Phil Jones', 1, 0);

-- --------------------------------------------------------

--
-- Structure de la table `contact_has_profil_contact`
--

DROP TABLE IF EXISTS `contact_has_profil_contact`;
CREATE TABLE IF NOT EXISTS `contact_has_profil_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) NOT NULL,
  `profil_contact_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_A248224CE7A1254A` (`contact_id`),
  KEY `IDX_A248224CDC677EB4` (`profil_contact_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `contact_has_profil_contact`
--

INSERT INTO `contact_has_profil_contact` (`id`, `contact_id`, `profil_contact_id`) VALUES
(1, 1, 4),
(2, 2, 4);

-- --------------------------------------------------------

--
-- Structure de la table `contrat`
--

DROP TABLE IF EXISTS `contrat`;
CREATE TABLE IF NOT EXISTS `contrat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_contrat` datetime NOT NULL,
  `id_customer` int(11) NOT NULL,
  `texte` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `status_contrat` int(11) DEFAULT NULL,
  `date_signature` datetime DEFAULT NULL,
  `signature_pack_contrat_customer` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `coordone_bancaire`
--

DROP TABLE IF EXISTS `coordone_bancaire`;
CREATE TABLE IF NOT EXISTS `coordone_bancaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `societe_facturation_id` int(11) NOT NULL,
  `titulaire` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `domiciliation` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `rib` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_F8A9344AE7D306A2` (`societe_facturation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=15 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `coordone_bancaire`
--

INSERT INTO `coordone_bancaire` (`id`, `societe_facturation_id`, `titulaire`, `domiciliation`, `rib`) VALUES
(1, 1, 'Outsourcia SAS', 'CENTRE AFFAIRES LOUVRE', 'FR76 1751 5900 0008 0022 4638 284'),
(2, 2, 'Outsourcia SAS', 'Banque Malgache de l\'Océan Indien', '00004 00001 017374 01139 32'),
(3, 3, 'Outsourcia SAS', 'Societe General', '022 780 000 196 00 050230 47 74'),
(4, 4, 'Outsourcia SAS', 'Attijariwafa Bank', 'NE 168 00001 036000110201 68'),
(5, 6, 'Outsourcia SAS', 'CAISSE D EPARGNE', 'FR76 1751 5900 008 0024 9959 261'),
(6, 7, 'Outsourcia SAS', 'BRED', 'FR76 1010 7002 7800 1230 2108 864'),
(7, 8, 'Outsourcia SAS', 'BNP', '3300040023000060000000'),
(8, 9, 'Outsourcia SAS', 'Societe General', 'FR76 3000 3039 5200 0204 6163 235'),
(9, 10, 'Outsourcia SAS', 'Banque Malgache de l\'Océan Indien', '00004 00004 01542901186 32'),
(10, 11, 'Outsourcia SAS', 'Banque Malgache de l\'Océan Indien', '00004 00012 01744420201 23'),
(11, 13, 'Outsourcia SAS', 'BNP Paribas', '30004 00849 00010142324 14'),
(12, 14, 'Outsourcia SAS', 'BMCI', '013 780 01007 003080 001 32 48'),
(13, 15, 'Outsourcia SAS', 'Attijariwafa Bank', '007 780 00 00287000002827 41'),
(14, 16, 'Outsourcia SAS', 'Societe General', '022 780 000 196 00 050246 96 74');

-- --------------------------------------------------------

--
-- Structure de la table `cout_horaire`
--

DROP TABLE IF EXISTS `cout_horaire`;
CREATE TABLE IF NOT EXISTS `cout_horaire` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `pays` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `niveau` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `langue_specialite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cout_horaire` decimal(10,2) NOT NULL,
  `cout_formation` decimal(10,2) NOT NULL,
  `bu` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=107 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `cout_horaire`
--

INSERT INTO `cout_horaire` (`id`, `date_debut`, `date_fin`, `pays`, `niveau`, `langue_specialite`, `cout_horaire`, `cout_formation`, `bu`) VALUES
(1, '2022-01-01', '2022-12-31', 'France', 'A', 'FR', '15.70', '15.70', 'Inbound'),
(2, '2022-01-01', '2022-12-31', 'France', 'A', 'FR', '15.70', '15.70', 'BPO'),
(3, '2022-01-01', '2022-12-31', 'France', 'A', 'FR', '16.30', '16.30', 'Outbound'),
(4, '2022-01-01', '2022-12-31', 'France', 'A', 'FR', '20.18', '20.18', 'Assurances'),
(5, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'FR', '3.97', '3.97', 'BPO'),
(6, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'FR', '3.97', '3.97', 'Inbound'),
(7, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'UK', '5.33', '5.33', 'Inbound'),
(8, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'ES', '5.33', '5.33', 'Inbound'),
(9, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'IT', '5.33', '5.33', 'Inbound'),
(10, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'NL', '7.58', '7.58', 'Inbound'),
(11, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'DE', '7.58', '7.58', 'Inbound'),
(12, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'PT', '7.58', '7.58', 'Inbound'),
(13, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'FR', '5.08', '5.08', 'Outbound'),
(14, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'UK', '5.43', '5.43', 'Outbound'),
(15, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'ES', '5.43', '5.43', 'Outbound'),
(16, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'IT', '5.43', '5.43', 'Outbound'),
(17, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'NL', '9.69', '9.69', 'Outbound'),
(18, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'DE', '9.69', '9.69', 'Outbound'),
(19, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'FR', '6.08', '6.08', 'Help Desk'),
(20, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'UK', '8.16', '8.16', 'Help Desk'),
(21, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'ES', '8.16', '8.16', 'Help Desk'),
(22, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'IT', '11.60', '11.60', 'Help Desk'),
(23, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'NL', '11.60', '11.60', 'Help Desk'),
(24, '2022-01-01', '2022-12-31', 'Maroc', 'A', 'DE', '11.60', '11.60', 'Help Desk'),
(25, '2022-01-01', '2022-12-31', 'Madagascar', 'A', 'FR', '1.51', '1.51', 'BPO'),
(26, '2022-01-01', '2022-12-31', 'Madagascar', 'A', 'FR', '1.75', '1.75', 'INBOUND'),
(27, '2022-01-01', '2022-12-31', 'Madagascar', 'A', 'FR', '1.75', '1.75', 'OUTBOUND'),
(28, '2022-01-01', '2022-12-31', 'Madagascar', 'A', 'FR', '0.81', '0.81', 'SAISIE'),
(29, '2022-01-01', '2022-12-31', 'France', 'A', 'FR', '1.80', '1.80', 'REDACTION'),
(30, '2022-01-01', '2022-12-31', 'Madagascar', 'A', 'FR', '1.00', '1.00', 'DATA'),
(31, '2022-01-01', '2022-12-31', 'Madagascar', 'A', 'FR', '1.00', '1.00', 'ETUDES'),
(32, '2022-01-01', '2022-12-31', 'Madagascar', 'A', 'Developpeur', '5.17', '5.17', 'DIGITAL'),
(33, '2022-01-01', '2022-12-31', 'Madagascar', 'A', 'FR', '2.91', '2.91', 'DIGITAL'),
(34, '2022-01-01', '2022-12-31', 'Niger', 'A', 'FR', '1.80', '1.80', 'INBOUND'),
(35, '2022-01-01', '2022-12-31', 'Niger', 'A', 'FR', '1.89', '1.89', 'TELECOM'),
(36, '2022-01-01', '2022-12-31', 'Madagascar', NULL, 'FR', '1.80', '1.80', 'REDACTION'),
(37, '2023-01-01', '2023-12-31', 'France', NULL, 'FR', '16.70', '16.70', 'Inbound'),
(38, '2023-01-01', '2023-12-31', 'France', NULL, 'FR', '15.70', '15.70', 'BPO'),
(39, '2023-01-01', '2023-12-31', 'France', NULL, 'FR', '16.30', '16.30', 'Outbound'),
(40, '2023-01-01', '2023-12-31', 'France', NULL, 'FR', '20.18', '20.18', 'Assurances'),
(41, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'FR', '3.97', '3.97', 'BPO'),
(42, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'FR', '3.97', '3.97', 'Inbound'),
(43, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'UK', '5.33', '5.33', 'Inbound'),
(44, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'ES', '5.33', '5.33', 'Inbound'),
(45, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'IT', '5.33', '5.33', 'Inbound'),
(46, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'NL', '7.58', '7.58', 'Inbound'),
(47, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'DE', '7.58', '7.58', 'Inbound'),
(48, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'PT', '7.58', '7.58', 'Inbound'),
(49, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'FR', '5.08', '5.08', 'Outbound'),
(50, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'UK', '5.43', '5.43', 'Outbound'),
(51, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'ES', '5.43', '5.43', 'Outbound'),
(52, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'IT', '5.43', '5.43', 'Outbound'),
(53, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'NL', '9.69', '9.69', 'Outbound'),
(54, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'DE', '9.69', '9.69', 'Outbound'),
(55, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'FR', '6.08', '6.08', 'Help Desk'),
(56, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'UK', '8.16', '8.16', 'Help Desk'),
(57, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'ES', '8.16', '8.16', 'Help Desk'),
(58, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'IT', '11.60', '11.60', 'Help Desk'),
(59, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'NL', '11.60', '11.60', 'Help Desk'),
(60, '2023-01-01', '2023-12-31', 'Maroc', NULL, 'DE', '11.60', '11.60', 'Help Desk'),
(61, '2023-01-01', '2023-12-31', 'Madagascar', NULL, 'FR', '1.51', '1.51', 'BPO'),
(62, '2023-01-01', '2023-12-31', 'Madagascar', NULL, 'FR', '1.75', '1.75', 'INBOUND'),
(63, '2023-01-01', '2023-12-31', 'Madagascar', NULL, 'FR', '1.75', '1.75', 'OUTBOUND'),
(64, '2023-01-01', '2023-12-31', 'Madagascar', NULL, 'FR', '0.81', '0.81', 'SAISIE'),
(65, '2023-01-01', '2023-12-31', 'Madagascar', NULL, 'FR', '2.80', '2.80', 'REDACTION'),
(66, '2023-01-01', '2023-12-31', 'Madagascar', NULL, 'FR', '1.00', '1.00', 'DATA'),
(67, '2023-01-01', '2023-12-31', 'Madagascar', NULL, 'FR', '1.00', '1.00', 'ETUDES'),
(68, '2023-01-01', '2023-12-31', 'Madagascar', NULL, 'Developpeur', '5.17', '5.17', 'DIGITAL'),
(69, '2023-01-01', '2023-12-31', 'Madagascar', NULL, 'FR', '2.91', '2.91', 'DIGITAL'),
(70, '2023-01-01', '2023-12-31', 'Niger', NULL, 'FR', '1.80', '1.80', 'INBOUND'),
(71, '2023-01-01', '2023-12-31', 'Niger', NULL, 'FR', '1.89', '1.89', 'TELECOM'),
(72, '2024-01-01', '2024-12-31', 'France', 'A', 'FR', '25.70', '25.70', 'Inbound'),
(73, '2024-01-01', '2024-12-31', 'France', 'A', 'FR', '25.70', '25.70', 'BPO'),
(74, '2024-01-01', '2024-12-31', 'France', 'A', 'FR', '26.30', '26.30', 'Outbound'),
(75, '2024-01-01', '2024-12-31', 'France', 'A', 'FR', '30.18', '30.18', 'Assurances'),
(76, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'FR', '4.97', '4.97', 'BPO'),
(77, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'FR', '4.97', '4.97', 'Inbound'),
(78, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'UK', '6.33', '6.33', 'Inbound'),
(79, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'ES', '5.33', '5.33', 'Inbound'),
(80, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'IT', '5.33', '5.33', 'Inbound'),
(81, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'NL', '7.58', '7.58', 'Inbound'),
(82, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'DE', '7.58', '7.58', 'Inbound'),
(83, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'PT', '7.58', '7.58', 'Inbound'),
(84, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'FR', '5.08', '5.08', 'Outbound'),
(85, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'UK', '5.43', '5.43', 'Outbound'),
(86, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'ES', '5.43', '5.43', 'Outbound'),
(87, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'IT', '5.43', '5.43', 'Outbound'),
(88, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'NL', '9.69', '9.69', 'Outbound'),
(89, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'DE', '9.69', '9.69', 'Outbound'),
(90, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'FR', '6.08', '6.08', 'Help Desk'),
(91, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'UK', '8.16', '8.16', 'Help Desk'),
(92, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'ES', '8.16', '8.16', 'Help Desk'),
(93, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'IT', '11.60', '11.60', 'Help Desk'),
(94, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'NL', '11.60', '11.60', 'Help Desk'),
(95, '2024-01-01', '2024-12-31', 'Maroc', 'A', 'DE', '11.60', '11.60', 'Help Desk'),
(96, '2024-01-01', '2024-12-31', 'Madagascar', 'A', 'FR', '1.51', '1.51', 'BPO'),
(97, '2024-01-01', '2024-12-31', 'Madagascar', 'A', 'FR', '1.75', '1.75', 'INBOUND'),
(98, '2024-01-01', '2024-12-31', 'Madagascar', 'A', 'FR', '1.75', '1.75', 'OUTBOUND'),
(99, '2024-01-01', '2024-12-31', 'Madagascar', 'A', 'FR', '0.81', '0.81', 'SAISIE'),
(100, '2024-01-01', '2024-12-31', 'Madagascar', 'A', 'FR', '1.80', '1.80', 'REDACTION'),
(101, '2024-01-01', '2024-12-31', 'Madagascar', 'A', 'FR', '1.00', '1.00', 'DATA'),
(102, '2024-01-01', '2024-12-31', 'Madagascar', 'A', 'FR', '1.00', '1.00', 'ETUDES'),
(103, '2024-01-01', '2024-12-31', 'Madagascar', 'A', 'Developpeur', '5.17', '5.17', 'DIGITAL'),
(104, '2024-01-01', '2024-12-31', 'Madagascar', 'A', 'FR', '2.91', '2.91', 'DIGITAL'),
(105, '2024-01-01', '2024-12-31', 'Niger', 'A', 'FR', '1.80', '1.80', 'INBOUND'),
(106, '2024-01-01', '2024-12-31', 'Niger', 'A', 'FR', '1.89', '1.89', 'TELECOM');

-- --------------------------------------------------------

--
-- Structure de la table `customer`
--

DROP TABLE IF EXISTS `customer`;
CREATE TABLE IF NOT EXISTS `customer` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `categorie_client_id` int(11) NOT NULL,
  `mapping_client_id` int(11) DEFAULT NULL,
  `adresse_facturation_id` int(11) DEFAULT NULL,
  `raison_social` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `marque_commercial` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cp` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `site_web` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_adress_fact_diff` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `user_id` int(11) NOT NULL,
  `irm` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sage_compte_tiers` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sage_compte_collectif` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `sage_categorie_comptable` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `num_client` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_has_contract` tinyint(1) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_81398E095BBD1224` (`adresse_facturation_id`),
  KEY `IDX_81398E09B4B46626` (`categorie_client_id`),
  KEY `IDX_81398E0955B93C0F` (`mapping_client_id`),
  KEY `IDX_81398E09A76ED395` (`user_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `customer`
--

INSERT INTO `customer` (`id`, `categorie_client_id`, `mapping_client_id`, `adresse_facturation_id`, `raison_social`, `marque_commercial`, `adresse`, `cp`, `ville`, `site_web`, `tel`, `is_adress_fact_diff`, `pays`, `user_id`, `irm`, `sage_compte_tiers`, `sage_compte_collectif`, `sage_categorie_comptable`, `num_client`, `is_has_contract`) VALUES
(1, 1, 3, NULL, 'ADIDAS', 'Adidas equipement', 'Moramanga', 'balance 503', 'Tamatave', 'www.adidas-equipement.com', '14992066596', '0', 'Canada', 19, NULL, NULL, NULL, NULL, '1', NULL),
(2, 1, 3, NULL, 'KODAK', 'Societe specialisé en photographisme', 'Maromamy', '1560', 'Brickaville', 'www.kodak-photo.com', '855605166262659', '0', 'Cambodge', 19, NULL, NULL, NULL, NULL, '2', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `devise`
--

DROP TABLE IF EXISTS `devise`;
CREATE TABLE IF NOT EXISTS `devise` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pays_facturation_id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_43EDA4DF899CF741` (`pays_facturation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `devise`
--

INSERT INTO `devise` (`id`, `pays_facturation_id`, `libelle`) VALUES
(1, 1, 'EURO'),
(2, 3, 'MGA'),
(3, 2, 'DIRHAM'),
(4, 4, 'CFA');

-- --------------------------------------------------------

--
-- Structure de la table `doctrine_migration_versions`
--

DROP TABLE IF EXISTS `doctrine_migration_versions`;
CREATE TABLE IF NOT EXISTS `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL,
  PRIMARY KEY (`version`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Déchargement des données de la table `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20210727071908', '2021-07-28 09:14:56', 1829),
('DoctrineMigrations\\Version20210727145054', '2021-07-27 18:40:44', 7948),
('DoctrineMigrations\\Version20210728071445', '2021-07-28 09:14:58', 2628),
('DoctrineMigrations\\Version20210805165620', '2021-08-05 18:56:37', 14167),
('DoctrineMigrations\\Version20210806121940', '2021-08-06 14:19:56', 2061),
('DoctrineMigrations\\Version20210812122812', '2021-08-12 14:28:29', 1013),
('DoctrineMigrations\\Version20210920150113', '2021-09-20 17:01:26', 7205),
('DoctrineMigrations\\Version20210921102039', '2021-09-21 12:20:56', 765),
('DoctrineMigrations\\Version20211115081246', '2021-11-17 15:26:27', 324),
('DoctrineMigrations\\Version20211216121324', '2021-12-16 13:15:40', 5633),
('DoctrineMigrations\\Version20220217061525', '2022-02-17 07:47:54', 1011),
('DoctrineMigrations\\Version20220225120840', '2022-02-25 15:28:42', 1124),
('DoctrineMigrations\\Version20220310183832', '2022-03-10 19:50:57', 795),
('DoctrineMigrations\\Version20220311122041', '2022-03-11 16:28:13', 737),
('DoctrineMigrations\\Version20220317133739', '2022-03-17 16:28:51', 2904),
('DoctrineMigrations\\Version20220409071603', '2022-04-09 09:22:52', 2937),
('DoctrineMigrations\\Version20220427143644', '2022-04-29 09:23:20', 2185),
('DoctrineMigrations\\Version20220504114442', '2022-05-05 16:39:06', 1221),
('DoctrineMigrations\\Version20220517130202', '2022-05-17 15:20:26', 3844),
('DoctrineMigrations\\Version20221006063844', '2022-11-30 11:40:35', 14),
('DoctrineMigrations\\Version20221129074350', '2022-11-30 11:46:38', 16);

-- --------------------------------------------------------

--
-- Structure de la table `duree_trt`
--

DROP TABLE IF EXISTS `duree_trt`;
CREATE TABLE IF NOT EXISTS `duree_trt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `duree_trt`
--

INSERT INTO `duree_trt` (`id`, `libelle`) VALUES
(1, 'Ponctuelle'),
(2, 'récurrente');

-- --------------------------------------------------------

--
-- Structure de la table `famille_operation`
--

DROP TABLE IF EXISTS `famille_operation`;
CREATE TABLE IF NOT EXISTS `famille_operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_irm` int(11) DEFAULT NULL,
  `is_si_renta` int(11) DEFAULT NULL,
  `is_sage` int(11) DEFAULT NULL,
  `code_famille` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=67 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `famille_operation`
--

INSERT INTO `famille_operation` (`id`, `libelle`, `is_irm`, `is_si_renta`, `is_sage`, `code_famille`) VALUES
(1, 'Frais de mise en place', 1, 1, 1, 'FMP'),
(2, 'BONUS ', 1, 1, 1, 'BON'),
(3, 'MALUS', 0, 0, 0, 'MAL'),
(4, 'PANNE', 1, 1, 1, 'PAN'),
(5, 'PILOTAGE', 1, 1, 1, 'PIL'),
(6, 'TELECOM', 0, 0, 0, 'TEL'),
(7, 'FORMATION', 1, 1, 1, 'FOR'),
(8, 'INBOUND PRODUCTION ACTE ', 1, 1, 1, 'INBA'),
(9, 'INBOUND PRODUCTION HEURE', 1, 1, 1, 'INBH'),
(10, 'INBOUND FORFAIT', 1, 1, 1, 'INBF'),
(11, 'OUTBOUND PRODUCTION ACTE ', 1, 1, 1, 'OUTA'),
(12, 'OUTBOUND PRODUCTION HEURE', 1, 1, 1, 'OUTH'),
(13, 'OUTBOUND FORFAIT', 1, 1, 1, 'OUTF'),
(14, 'HELPDESK PRODUCTION ACTE ', 1, 1, 1, 'HELA'),
(15, 'HELPDESK PRODUCTION HEURE', 1, 1, 1, 'HELH'),
(16, 'HELPDESK FORFAIT', 1, 1, 1, 'HELF'),
(17, 'BPO PRODUCTION ACTE', 1, 1, 1, 'BPOA'),
(18, 'BPO PRODUCTION HEURES', 1, 1, 1, 'BPOH'),
(19, 'BPO PRODUCTION FORFAIT', 1, 1, 1, 'BPOF'),
(20, 'REDACTION PRODUCTION ACTE', 1, 1, 1, 'REDA'),
(21, 'REDACTION  PRODUCTION HEURES', 1, 1, 1, 'REDH'),
(22, 'REDACTION PRODUCTION FORFAIT', 1, 1, 1, 'REDF'),
(23, 'DIGITAL PRODUCTION HEURE', 1, 1, 1, 'DIGH'),
(24, 'DIGITAL FORFAIT', 1, 1, 1, 'DIGF'),
(25, 'ETUDE PRODUCTION ACTE ', 1, 1, 1, 'ETUA '),
(26, 'ETUDE PRODUCTION FORFAIT', 1, 1, 1, 'ETUF'),
(27, 'ETUDE PRODUCTION HEURE', 1, 1, 1, 'ETUF'),
(28, 'DATA PRODUCTION ACTE ', 1, 1, 1, 'DATA'),
(29, 'DATA PRODUCTION FORFAIT', 1, 1, 1, 'DATF'),
(30, 'DATA PRODUCTION HEURE', 1, 1, 1, 'DATH'),
(31, 'SOUSTRAITANCE INBOUND PRODUCTION ACTE', 1, 1, 1, 'STI'),
(32, 'SOUSTRAITANCE INBOUND PRODUCTION HEURES', 1, 1, 1, 'STI'),
(33, 'SOUSTRAITANCE INBOUND PRODUCTION FORFAIT', 1, 1, 1, 'STI'),
(34, 'SOUSTRAITANCE OUTBOUND PRODUCTION ACTE ', 1, 1, 1, 'STO'),
(35, 'SOUSTRAITANCE OUTBOUND PRODUCTION HEURES', 1, 1, 1, 'STO'),
(36, 'SOUSTRAITANCE OUTBOUND PRODUCTION FORFAIT', 1, 1, 1, 'STO'),
(37, 'SOUS TRAITANCE BPO PRODUCTION ACTE', 1, 1, 1, 'STB'),
(38, 'SOUS TRAITANCE BPO PRODUCTION HEURES', 1, 1, 1, 'STB'),
(39, 'SOUS TRAITANCE BPO PRODUCTION FORFAIT', 1, 1, 1, 'STB'),
(40, 'SOUS TRAITANCE REDACTION PRODUCTION ACTE ', 1, 1, 1, 'STR'),
(41, 'SOUS TRAITANCE REDACTION PRODUCTION HEURES', 1, 1, 1, 'STR'),
(42, 'SOUS TRAITANCE REDACTION PRODUCTION FORFAIT', 1, 1, 1, 'STR'),
(44, 'SOUSTRAITANCE DATA PRODUCTION FORFAIT', 1, 1, 1, 'STF'),
(45, 'SOUS TRAITANCE DATA PRODUCTION HEURE', 1, 1, 1, 'STH'),
(47, 'BPO FORFAIT', 1, 1, 1, 'BPOF'),
(48, 'REDACTION PRODUCTION HEURE', 1, 1, 1, 'REDH'),
(49, 'REDACTION FORFAIT', 1, 1, 1, 'REDF'),
(50, 'DIGITAL PRODUCTION ACTE ', 1, 1, 1, 'DIGIA'),
(53, 'HEBERGEMENT PRODUCTION ACTE ', 1, 1, 1, 'HEBA'),
(54, 'HEBERGEMENT PRODUCTION HEURE', 1, 1, 1, 'HEBH'),
(55, 'HEBERGEMENT FORFAIT', 1, 1, 1, 'HEBF'),
(57, 'ETUDE FORFAIT', 1, 1, 1, 'ETUF'),
(59, 'DATA FORFAIT', 1, 1, 1, 'DATF'),
(60, 'SANTE PRISE DE RDV', 1, 1, 1, ''),
(61, 'SANTE SAISIE DE CR', 1, 1, 1, ''),
(62, 'CONSEIL', 1, 1, 1, ''),
(63, 'REGULE', 1, 1, 1, 'REG'),
(64, 'DEVELOPPEMENT', 1, 1, 1, 'DEV'),
(65, 'CONSEIL/ACCOMPAGNEMENT', 1, 1, 1, 'COAC'),
(66, 'BONUS / MALUS', 0, 0, 0, 'MALBON');

-- --------------------------------------------------------

--
-- Structure de la table `fiche_client`
--

DROP TABLE IF EXISTS `fiche_client`;
CREATE TABLE IF NOT EXISTS `fiche_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `nature_prestation_id` int(11) DEFAULT NULL,
  `rc` longtext COLLATE utf8mb4_unicode_ci,
  `activite_contexte` longtext COLLATE utf8mb4_unicode_ci,
  `gestionnaire_de_compte` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `rapport_activite` longtext COLLATE utf8mb4_unicode_ci,
  `statut_contrat` varchar(25) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `tacite_reconduction` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `versement_acompte` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `type_profil` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `niveau` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `langue_trt` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `formation` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_demarrage_partenariat` date DEFAULT NULL,
  `dimensionnement` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `budget_de_mise_en_place` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `budget_formation` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `budget_annuel` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `budget_moyen_mensuel` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `budget_m1` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `moyenne_qualite` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `moyenne_satcli` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `conf_specifique` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `specificite_contractuelles` longtext COLLATE utf8mb4_unicode_ci,
  `forfait_pilotage` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `chiffre_affaire_realise` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ca_m1` varchar(80) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `duree_anciennete` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registre_traitement` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `annexe_contrat` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `outils` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commercial` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_7158A9829395C3F3` (`customer_id`),
  KEY `IDX_7158A9821E4CCA8D` (`nature_prestation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `fiche_client`
--

INSERT INTO `fiche_client` (`id`, `customer_id`, `nature_prestation_id`, `rc`, `activite_contexte`, `gestionnaire_de_compte`, `rapport_activite`, `statut_contrat`, `tacite_reconduction`, `versement_acompte`, `type_profil`, `niveau`, `langue_trt`, `formation`, `date_demarrage_partenariat`, `dimensionnement`, `budget_de_mise_en_place`, `budget_formation`, `budget_annuel`, `budget_moyen_mensuel`, `budget_m1`, `moyenne_qualite`, `moyenne_satcli`, `conf_specifique`, `specificite_contractuelles`, `forfait_pilotage`, `chiffre_affaire_realise`, `ca_m1`, `duree_anciennete`, `registre_traitement`, `annexe_contrat`, `outils`, `commercial`) VALUES
(1, 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'en cours', 'ok', NULL, NULL),
(2, 2, NULL, NULL, NULL, 'telmestour@outsourcia-group.com', NULL, NULL, NULL, '5000', 'Maroc/INBOUD/Appel traité/Niveau A/FR', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Bureau à distance', NULL, NULL, NULL, NULL, NULL, NULL, NULL, 'Maroc/INBOUD/Appel traité/Outils A', NULL);

-- --------------------------------------------------------

--
-- Structure de la table `hause_indice_lignefacturation`
--

DROP TABLE IF EXISTS `hause_indice_lignefacturation`;
CREATE TABLE IF NOT EXISTS `hause_indice_lignefacturation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_operation` int(11) DEFAULT NULL,
  `ancien_prix` decimal(50,2) DEFAULT NULL,
  `nouveau_prix` decimal(50,2) DEFAULT NULL,
  `hausse_indece_client_id` int(11) DEFAULT NULL,
  `date_aplicatif` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ancien_prix_acte` decimal(10,0) DEFAULT NULL,
  `nouveau_prix_acte` decimal(10,0) DEFAULT NULL,
  `ancien_prix_heure` decimal(10,0) DEFAULT NULL,
  `nouveau_prix_heure` decimal(10,0) DEFAULT NULL,
  `commentaire_mod_manuel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `hausse_indice_bdco`
--

DROP TABLE IF EXISTS `hausse_indice_bdco`;
CREATE TABLE IF NOT EXISTS `hausse_indice_bdco` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `hausse_indice_syntec_client`
--

DROP TABLE IF EXISTS `hausse_indice_syntec_client`;
CREATE TABLE IF NOT EXISTS `hausse_indice_syntec_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `id_customer` int(11) NOT NULL,
  `is_type` int(11) DEFAULT NULL,
  `status` int(11) DEFAULT NULL,
  `date_contrat` date DEFAULT NULL,
  `clause` int(11) NOT NULL,
  `initial` decimal(40,3) DEFAULT NULL,
  `actuel` decimal(40,5) DEFAULT NULL,
  `taux_evolution` int(11) DEFAULT NULL,
  `date_years` date DEFAULT NULL,
  `date_aplicatif` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nouveau_prix_heure` decimal(10,0) DEFAULT NULL,
  `type_pdf` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `commentaire` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique`
--

DROP TABLE IF EXISTS `historique`;
CREATE TABLE IF NOT EXISTS `historique` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `contact_id` int(11) DEFAULT NULL,
  `date` datetime NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EDBFD5ECE7A1254A` (`contact_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `historique_contrat`
--

DROP TABLE IF EXISTS `historique_contrat`;
CREATE TABLE IF NOT EXISTS `historique_contrat` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `date` datetime DEFAULT NULL,
  `status_contrat` int(11) DEFAULT NULL,
  `id_contrat` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `horaire_production`
--

DROP TABLE IF EXISTS `horaire_production`;
CREATE TABLE IF NOT EXISTS `horaire_production` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=3 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `horaire_production`
--

INSERT INTO `horaire_production` (`id`, `libelle`) VALUES
(1, 'Heures du pays de production'),
(2, 'Heures des clients finaux');

-- --------------------------------------------------------

--
-- Structure de la table `indicator_qualitatif`
--

DROP TABLE IF EXISTS `indicator_qualitatif`;
CREATE TABLE IF NOT EXISTS `indicator_qualitatif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bdc_operation_id` int(11) DEFAULT NULL,
  `objectif_qualitatif_id` int(11) DEFAULT NULL,
  `indicator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lead_detail_operation_id` int(11) DEFAULT NULL,
  `uniq_bdc_fq_operation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_DB5AD6F57B27786B` (`bdc_operation_id`),
  KEY `IDX_DB5AD6F584C558C9` (`objectif_qualitatif_id`),
  KEY `IDX_DB5AD6F553EF6646` (`lead_detail_operation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `indicator_qualitatif`
--

INSERT INTO `indicator_qualitatif` (`id`, `bdc_operation_id`, `objectif_qualitatif_id`, `indicator`, `lead_detail_operation_id`, `uniq_bdc_fq_operation`) VALUES
(3, 14, 3, '70', NULL, NULL),
(4, 29, 2, '60', NULL, NULL),
(5, 13, 3, '55', 2, '1674718524598'),
(6, 43, 2, '90', NULL, NULL),
(7, 1, 2, '95', 1, '1674718384047'),
(8, 75, 4, '35', NULL, NULL),
(9, 237, 2, '50', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `indicator_quantitatif`
--

DROP TABLE IF EXISTS `indicator_quantitatif`;
CREATE TABLE IF NOT EXISTS `indicator_quantitatif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bdc_operation_id` int(11) DEFAULT NULL,
  `objectif_quantitatif_id` int(11) DEFAULT NULL,
  `indicator` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `lead_detail_operation_id` int(11) DEFAULT NULL,
  `uniq_bdc_fq_operation` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1C1A0F207B27786B` (`bdc_operation_id`),
  KEY `IDX_1C1A0F20899CB9C9` (`objectif_quantitatif_id`),
  KEY `IDX_1C1A0F2053EF6646` (`lead_detail_operation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `indicator_quantitatif`
--

INSERT INTO `indicator_quantitatif` (`id`, `bdc_operation_id`, `objectif_quantitatif_id`, `indicator`, `lead_detail_operation_id`, `uniq_bdc_fq_operation`) VALUES
(3, 14, 1, '80', NULL, NULL),
(4, 29, 4, '60', NULL, NULL),
(5, 13, 3, '45', 2, '1674718524598'),
(6, 43, 3, '75', NULL, NULL),
(7, 1, 7, '70', 1, '1674718384047'),
(8, 75, 1, '80', NULL, NULL),
(9, 237, 1, '75', NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `langue_trt`
--

DROP TABLE IF EXISTS `langue_trt`;
CREATE TABLE IF NOT EXISTS `langue_trt` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `langue_trt`
--

INSERT INTO `langue_trt` (`id`, `libelle`) VALUES
(1, 'FR'),
(2, 'UK'),
(3, 'ES'),
(4, 'IT'),
(5, 'DE'),
(6, 'NL'),
(7, 'PT'),
(8, 'BR'),
(9, 'MA'),
(10, 'MG');

-- --------------------------------------------------------

--
-- Structure de la table `lead_detail_operation`
--

DROP TABLE IF EXISTS `lead_detail_operation`;
CREATE TABLE IF NOT EXISTS `lead_detail_operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `type_facturation_id` int(11) NOT NULL,
  `langue_trt_id` int(11) NOT NULL,
  `bu_id` int(11) NOT NULL,
  `operation_id` int(11) NOT NULL,
  `horaire_production_id` int(11) DEFAULT NULL,
  `resume_lead_id` int(11) NOT NULL,
  `heure_jour_ouvrable` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `categorie_lead` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `date_debut_cross` date DEFAULT NULL,
  `pays_facturation_id` int(11) NOT NULL,
  `pays_production_id` int(11) NOT NULL,
  `heure_week_end` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `famille_operation_id` int(11) NOT NULL,
  `hno` varchar(10) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `cout_horaire_id` int(11) DEFAULT NULL,
  `tarif_horaire_cible` decimal(10,2) DEFAULT NULL,
  `temps_productifs` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `dmt` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prix_unit` decimal(10,2) DEFAULT NULL,
  `volume_atraite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nb_heure_mensuel` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `uniq_bdc_fq_operation` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `nb_etp` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `productivite_acte` decimal(10,2) DEFAULT NULL,
  `quantite_acte` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `quantite_heure` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `prix_unitaire_acte` decimal(10,2) DEFAULT NULL,
  `prix_unitaire_heure` decimal(10,2) DEFAULT NULL,
  `designation_acte_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_29FD728E8D06DB10` (`type_facturation_id`),
  KEY `IDX_29FD728E800DC1FD` (`langue_trt_id`),
  KEY `IDX_29FD728EE0319FBC` (`bu_id`),
  KEY `IDX_29FD728E44AC3583` (`operation_id`),
  KEY `IDX_29FD728EF9A66EC9` (`horaire_production_id`),
  KEY `IDX_29FD728E3615FA65` (`resume_lead_id`),
  KEY `IDX_29FD728E9EFA7AED` (`famille_operation_id`),
  KEY `IDX_29FD728E899CF741` (`pays_facturation_id`),
  KEY `IDX_29FD728EDD21E7CC` (`pays_production_id`),
  KEY `IDX_29FD728E8E47663F` (`cout_horaire_id`),
  KEY `IDX_29FD728ECB40E30D` (`designation_acte_id`)
) ENGINE=InnoDB AUTO_INCREMENT=39 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `lead_detail_operation`
--

INSERT INTO `lead_detail_operation` (`id`, `type_facturation_id`, `langue_trt_id`, `bu_id`, `operation_id`, `horaire_production_id`, `resume_lead_id`, `heure_jour_ouvrable`, `categorie_lead`, `date_debut_cross`, `pays_facturation_id`, `pays_production_id`, `heure_week_end`, `famille_operation_id`, `hno`, `cout_horaire_id`, `tarif_horaire_cible`, `temps_productifs`, `dmt`, `prix_unit`, `volume_atraite`, `nb_heure_mensuel`, `uniq_bdc_fq_operation`, `nb_etp`, `productivite_acte`, `quantite_acte`, `quantite_heure`, `prix_unitaire_acte`, `prix_unitaire_heure`, `designation_acte_id`) VALUES
(1, 5, 1, 4, 680, 1, 1, NULL, 'New Business', NULL, 3, 3, NULL, 19, NULL, 61, NULL, NULL, NULL, '23.00', NULL, '66', '1674718384047', '28', NULL, NULL, NULL, NULL, NULL, NULL),
(2, 3, 1, 3, 634, 1, 1, NULL, 'New Business', NULL, 2, 2, NULL, 15, NULL, 55, NULL, NULL, NULL, '17.00', NULL, '65', '1674718524598', '21', NULL, NULL, NULL, NULL, NULL, NULL),
(3, 1, 2, 1, 506, 2, 1, NULL, 'New Business', NULL, 2, 2, NULL, 11, NULL, 50, '54.00', '02:37:55', '01:42:03', '34.90', '248', NULL, '1674718683355', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 7, 1, 1, 576, 1, 1, NULL, 'New Business', NULL, 1, 1, NULL, 12, NULL, 39, '66.00', NULL, NULL, NULL, '1010.10', '74', '1674718798545', '21', '0.65', NULL, NULL, '53.85', '31.00', 498),
(5, 3, 2, 1, 578, 1, 2, NULL, 'New Business', NULL, 2, 2, NULL, 12, NULL, 50, NULL, NULL, NULL, '29.00', NULL, '68', '1676619485277', '22', NULL, NULL, NULL, NULL, NULL, NULL),
(6, 3, 1, 2, 382, 2, 3, NULL, 'New Business', NULL, 1, 1, NULL, 9, NULL, 37, NULL, NULL, NULL, '24.00', NULL, '62', '1677240130923', '20', NULL, NULL, NULL, NULL, NULL, NULL),
(7, 1, 1, 1, 497, 1, 3, NULL, 'New Business', NULL, 2, 2, NULL, 11, NULL, 49, '89.00', '02:54:44', '02:04:32', '63.43', '197', NULL, '1677240186088', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 5, 1, 2, 486, 2, 4, NULL, 'New Business', NULL, 4, 4, NULL, 10, NULL, 70, NULL, NULL, NULL, '27.00', NULL, '110', '1678187582381', '36', NULL, NULL, NULL, NULL, NULL, NULL),
(9, 3, 1, 1, 576, 2, 5, NULL, 'New Business', NULL, 1, 1, NULL, 12, NULL, 39, NULL, NULL, NULL, '30.00', NULL, '49', '1678188421682', '14', NULL, NULL, NULL, NULL, NULL, NULL),
(10, 5, 1, 2, 486, 1, 5, NULL, 'New Business', NULL, 2, 2, NULL, 10, NULL, 42, NULL, NULL, NULL, '24.00', NULL, '63', '1678188450020', '23', NULL, NULL, NULL, NULL, NULL, NULL),
(11, 1, 1, 4, 678, 2, 5, NULL, 'New Business', NULL, 3, 3, NULL, 17, NULL, 61, '75.00', '03:55:55', '01:37:04', '30.86', '202', NULL, '1678188521603', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(12, 5, 1, 2, 486, 1, 6, NULL, 'New Business', NULL, 1, 1, NULL, 10, NULL, 37, NULL, NULL, NULL, '24.00', NULL, '35', '1678265731620', '15', NULL, NULL, NULL, NULL, NULL, NULL),
(13, 5, 1, 1, 596, 1, 6, NULL, 'New Business', NULL, 2, 2, NULL, 13, NULL, 49, NULL, NULL, NULL, '24.00', NULL, '65', '1678265755887', '22', NULL, NULL, NULL, NULL, NULL, NULL),
(14, 5, 1, 1, 596, 1, 7, NULL, 'New Business', NULL, 3, 3, NULL, 13, NULL, 63, NULL, NULL, NULL, '31.00', NULL, '40', '1678266062487', '17', NULL, NULL, NULL, NULL, NULL, NULL),
(15, 5, 1, 4, 680, 1, 7, NULL, 'New Business', NULL, 2, 2, NULL, 19, NULL, 41, NULL, NULL, NULL, '25.00', NULL, '50', '1678266144744', '19', NULL, NULL, NULL, NULL, NULL, NULL),
(16, 5, 1, 1, 596, 1, 8, NULL, 'New Business', NULL, 1, 1, NULL, 13, NULL, 39, NULL, NULL, NULL, '22.00', NULL, '85', '1678267049118', '29', NULL, NULL, NULL, NULL, NULL, NULL),
(17, 5, 1, 2, 486, 1, 8, NULL, 'New Business', NULL, 3, 3, NULL, 10, NULL, 62, NULL, NULL, NULL, '25.00', NULL, '70', '1678267074800', '25', NULL, NULL, NULL, NULL, NULL, NULL),
(18, 3, 1, 1, 576, 2, 9, NULL, 'New Business', NULL, 1, 1, NULL, 12, NULL, 39, NULL, NULL, NULL, '30.00', NULL, '49', '1678188421682', '14', NULL, NULL, NULL, NULL, NULL, NULL),
(19, 5, 1, 2, 486, 1, 9, NULL, 'New Business', NULL, 2, 2, NULL, 10, NULL, 42, NULL, NULL, NULL, '24.00', NULL, '63', '1678188450020', '23', NULL, NULL, NULL, NULL, NULL, NULL),
(20, 1, 1, 4, 678, 2, 9, NULL, 'New Business', NULL, 3, 3, NULL, 17, NULL, 61, '75.00', '03:55:55', '01:37:04', '30.86', '202', NULL, '1678188521603', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(21, 3, 1, 1, 576, 2, 10, NULL, 'New Business', NULL, 1, 1, NULL, 12, NULL, 39, NULL, NULL, NULL, '30.00', NULL, '49', '1678188421682', '14', NULL, NULL, NULL, NULL, NULL, NULL),
(22, 5, 1, 2, 486, 1, 10, NULL, 'New Business', NULL, 2, 2, NULL, 10, NULL, 42, NULL, NULL, NULL, '24.00', NULL, '63', '1678188450020', '23', NULL, NULL, NULL, NULL, NULL, NULL),
(23, 1, 1, 4, 678, 2, 10, NULL, 'New Business', NULL, 3, 3, NULL, 17, NULL, 61, '75.00', '03:55:55', '01:37:04', '30.86', '202', NULL, '1678188521603', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(24, 3, 1, 2, 382, 2, 11, NULL, 'New Business', NULL, 2, 2, NULL, 9, NULL, 42, NULL, NULL, NULL, '20.00', NULL, '104', '1678283848231', '35', NULL, NULL, NULL, NULL, NULL, NULL),
(25, 5, 1, 1, 596, 1, 11, NULL, 'New Business', NULL, 3, 3, NULL, 13, NULL, 63, NULL, NULL, NULL, '26.00', NULL, '86', '1678283901289', '20', NULL, NULL, NULL, NULL, NULL, NULL),
(26, 5, 1, 2, 486, 1, 11, NULL, 'New Business', NULL, 2, 2, NULL, 10, NULL, 42, NULL, NULL, NULL, '15.00', NULL, '51', '1678364372463', '15', NULL, NULL, NULL, NULL, NULL, NULL),
(27, 1, 1, 4, 676, 1, 11, NULL, 'New Business', NULL, 1, 1, NULL, 17, NULL, 38, '74.00', '03:55:52', '02:31:02', '47.38', '490', NULL, '1678364454113', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(28, 5, 1, 2, 486, 1, 11, NULL, 'New Business', NULL, 2, 2, NULL, 10, NULL, 42, NULL, NULL, NULL, '22.00', NULL, '76', '1678365040457', '18', NULL, NULL, NULL, NULL, NULL, NULL),
(29, 1, 1, 4, 676, 1, 11, NULL, 'New Business', NULL, 1, 1, NULL, 17, NULL, 38, '79.00', '02:55:55', '02:03:26', '55.43', '444', NULL, '1678365088842', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(30, 5, 1, 2, 486, 1, 11, NULL, 'New Business', NULL, 2, 2, NULL, 10, NULL, 42, NULL, NULL, NULL, '33.00', NULL, '69', '1678365425466', '19', NULL, NULL, NULL, NULL, NULL, NULL),
(31, 1, 1, 4, 676, 1, 11, NULL, 'New Business', NULL, 1, 1, NULL, 17, NULL, 38, '80.00', '02:55:37', '02:03:03', '56.05', '418', NULL, '1678365473492', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(32, 5, 1, 2, 486, 1, 11, NULL, 'New Business', NULL, 2, 2, NULL, 10, NULL, 42, NULL, NULL, NULL, '23.00', NULL, '77', '1678365803669', '19', NULL, NULL, NULL, NULL, NULL, NULL),
(33, 1, 1, 4, 676, 1, 11, NULL, 'New Business', NULL, 1, 1, NULL, 17, NULL, 38, '70.00', '02:55:48', '02:03:03', '49.00', '388', NULL, '1678366230286', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(34, 5, 1, 2, 486, 1, 11, NULL, 'New Business', NULL, 2, 2, NULL, 10, NULL, 42, NULL, NULL, NULL, '36.00', NULL, '98', '1678366629703', '25', NULL, NULL, NULL, NULL, NULL, NULL),
(35, 1, 1, 4, 676, 1, 11, NULL, 'New Business', NULL, 1, 1, NULL, 17, NULL, 38, '82.00', '02:54:56', '02:03:04', '57.69', '338', NULL, '1678366672751', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(36, 1, 1, 4, 676, 1, 11, NULL, 'New Business', NULL, 1, 1, NULL, 17, NULL, 38, '54.00', '02:32:44', '02:04:44', '44.10', '238', NULL, '1678369699401', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(37, 1, 1, 4, 676, 1, 11, NULL, 'New Business', NULL, 1, 1, NULL, 17, NULL, 38, '54.00', '02:32:44', '02:04:44', '44.10', '238', NULL, '1678369699401', NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(38, 1, 1, 4, 676, 1, 11, NULL, 'New Business', NULL, 1, 1, NULL, 17, NULL, 38, '54.00', '02:32:44', '02:04:44', '44.10', '238', NULL, '1678369699401', NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `lead_detail_operation_objectif_qualitatif`
--

DROP TABLE IF EXISTS `lead_detail_operation_objectif_qualitatif`;
CREATE TABLE IF NOT EXISTS `lead_detail_operation_objectif_qualitatif` (
  `lead_detail_operation_id` int(11) NOT NULL,
  `objectif_qualitatif_id` int(11) NOT NULL,
  PRIMARY KEY (`lead_detail_operation_id`,`objectif_qualitatif_id`),
  KEY `IDX_DE65A1F553EF6646` (`lead_detail_operation_id`),
  KEY `IDX_DE65A1F584C558C9` (`objectif_qualitatif_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `lead_detail_operation_objectif_qualitatif`
--

INSERT INTO `lead_detail_operation_objectif_qualitatif` (`lead_detail_operation_id`, `objectif_qualitatif_id`) VALUES
(1, 2),
(2, 3),
(3, 3),
(4, 2),
(5, 2),
(8, 4),
(24, 2);

-- --------------------------------------------------------

--
-- Structure de la table `lead_detail_operation_objectif_quantitatif`
--

DROP TABLE IF EXISTS `lead_detail_operation_objectif_quantitatif`;
CREATE TABLE IF NOT EXISTS `lead_detail_operation_objectif_quantitatif` (
  `lead_detail_operation_id` int(11) NOT NULL,
  `objectif_quantitatif_id` int(11) NOT NULL,
  PRIMARY KEY (`lead_detail_operation_id`,`objectif_quantitatif_id`),
  KEY `IDX_1C1F305753EF6646` (`lead_detail_operation_id`),
  KEY `IDX_1C1F3057899CB9C9` (`objectif_quantitatif_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `mapping_client`
--

DROP TABLE IF EXISTS `mapping_client`;
CREATE TABLE IF NOT EXISTS `mapping_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `mapping_client`
--

INSERT INTO `mapping_client` (`id`, `libelle`) VALUES
(1, 'Ordinaire'),
(2, 'Régulier Bas'),
(3, 'Ambassadeur'),
(4, 'Promotteur');

-- --------------------------------------------------------

--
-- Structure de la table `nature_prestation`
--

DROP TABLE IF EXISTS `nature_prestation`;
CREATE TABLE IF NOT EXISTS `nature_prestation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=90 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `nature_prestation`
--

INSERT INTO `nature_prestation` (`id`, `libelle`) VALUES
(1, 'Analyse'),
(2, 'Mise en page'),
(3, 'Helpdesk'),
(4, 'Appels entrants'),
(5, 'Appels sortants'),
(6, 'Etude satisfaction'),
(7, 'Indexation'),
(8, 'Mailing'),
(9, 'Gestion mail'),
(10, 'Catégorisation/Classification'),
(11, 'Codification'),
(12, 'Cawi'),
(13, 'Comunity management'),
(14, 'Constitution, enrichissement et/ou mise à jour de base de donnée'),
(15, 'Contenus SEO'),
(16, 'Création de contenus SEO'),
(17, 'Création de pages thématiques'),
(18, 'Création graphique'),
(19, 'Création/rédaction d\'articles'),
(20, 'Création/rédaction de contenus'),
(21, 'Détourage de photos'),
(22, 'Détourage et redimensionnement d\'images'),
(23, 'Développement informatique'),
(24, 'Enquête téléphonique'),
(25, 'Enquête téléphonique/Constitution, enrichissement et/ou mise à jour de base de données'),
(26, 'Enrichissement/Qualification et/ou mise à jour de base de données'),
(27, 'Intégration de données sur back office'),
(28, 'Intégration de contenu sur site internet'),
(29, 'Intégration de données sur BO'),
(30, 'Matching'),
(31, 'Modération'),
(32, 'Montage vidéo'),
(33, 'Nettoyage de base de données'),
(34, 'Normalisation d\'adresses'),
(35, 'Océrisation de documents'),
(36, 'Qualification'),
(37, 'Qualification de base de données'),
(38, 'Qualification de base de données/ Catégorisation/ Classification'),
(39, 'Qualification de données via les dites web'),
(40, 'Qualification des données'),
(41, 'Qualification, nettoyage BDD'),
(42, 'Recherche et intégration d\'images'),
(43, 'Rédaction d\'articles'),
(44, 'Rédaction d\'articles/textes en offline'),
(45, 'Rédaction d\'articles/textes sur interface'),
(46, 'Rédaction de fiches produits sur interface'),
(47, 'Rédaction de fiches produits sur interface/Rédaction de fiche produits en offline'),
(48, 'Rédaction de fiches produits en offline'),
(49, 'Rédaction de fiches produits'),
(50, 'Rédaction de fiches produits en offline/Traduction'),
(51, 'Rédaction de contenus'),
(52, 'Rédaction de guide d\'achat'),
(53, 'Rédaction d\'articles'),
(54, 'Réécriture de textes'),
(55, 'Retouches images'),
(56, 'Retranscription audio'),
(57, 'Retro-conversion de fichier'),
(58, 'Saisie d\'adresses mail'),
(59, 'Saisie d\'annonces'),
(60, 'Saisie d\'annuaires/catalogues'),
(61, 'Saisie d\'enquêtes'),
(62, 'Saisie de bon de commande'),
(63, 'Saisie de cartes de visite'),
(64, 'Saisie de catalogues'),
(65, 'Saisie de catalogues en ligne'),
(66, 'Saisie de commandes'),
(67, 'Saisie de coupons'),
(68, 'Saisie de données'),
(69, 'Saisie de données à partir d\'un site internet'),
(70, 'Saisie de factures'),
(71, 'Saisie de fiches'),
(72, 'Saisie de fiches, coupons, formulaires'),
(73, 'Saisie de formulaires'),
(74, 'Saisie de questionnaires'),
(75, 'Saisie de questionnaires/Saisie kilométrique '),
(76, 'Saisie de questions'),
(77, 'Saisie des caractéristiques des produits'),
(78, 'Saisie et indexation'),
(79, 'Saisie kilométrique'),
(80, 'Saisie structurée (SGML, HTML, XML)'),
(81, 'Service client'),
(82, 'Simple saisie d\'ouvrages'),
(83, 'Télémarketing'),
(84, 'Télévente'),
(85, 'Traduction'),
(86, 'Traitement de Rejets'),
(87, 'Traitement/Topage NPAI'),
(88, 'Traitement d\'images'),
(89, 'Veille');

-- --------------------------------------------------------

--
-- Structure de la table `objectif_qualitatif`
--

DROP TABLE IF EXISTS `objectif_qualitatif`;
CREATE TABLE IF NOT EXISTS `objectif_qualitatif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=209 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `objectif_qualitatif`
--

INSERT INTO `objectif_qualitatif` (`id`, `libelle`) VALUES
(1, 'Note qualité'),
(2, 'Satisfaction client'),
(3, 'Taux de conformité'),
(4, 'Taux d\'erreurs'),
(5, NULL),
(6, NULL),
(7, NULL),
(8, NULL),
(9, NULL),
(10, NULL),
(11, NULL),
(12, NULL),
(13, NULL),
(14, NULL),
(15, NULL),
(16, NULL),
(17, NULL),
(18, NULL),
(19, NULL),
(20, NULL),
(21, NULL),
(22, NULL),
(23, NULL),
(24, NULL),
(25, NULL),
(26, NULL),
(27, NULL),
(28, NULL),
(29, NULL),
(30, NULL),
(31, NULL),
(32, NULL),
(33, NULL),
(34, NULL),
(35, NULL),
(36, NULL),
(37, NULL),
(38, NULL),
(39, NULL),
(40, NULL),
(41, NULL),
(42, NULL),
(43, NULL),
(44, NULL),
(45, NULL),
(46, NULL),
(47, NULL),
(48, NULL),
(49, NULL),
(50, NULL),
(51, NULL),
(52, NULL),
(53, NULL),
(54, NULL),
(55, NULL),
(56, NULL),
(57, NULL),
(58, NULL),
(59, NULL),
(60, NULL),
(61, NULL),
(62, NULL),
(63, NULL),
(64, NULL),
(65, NULL),
(66, NULL),
(67, NULL),
(68, NULL),
(69, NULL),
(70, NULL),
(71, NULL),
(72, NULL),
(73, NULL),
(74, NULL),
(75, NULL),
(76, NULL),
(77, NULL),
(78, NULL),
(79, NULL),
(80, NULL),
(81, NULL),
(82, NULL),
(83, NULL),
(84, NULL),
(85, NULL),
(86, NULL),
(87, NULL),
(88, NULL),
(89, NULL),
(90, NULL),
(91, NULL),
(92, NULL),
(93, NULL),
(94, NULL),
(95, NULL),
(96, NULL),
(97, NULL),
(98, NULL),
(99, NULL),
(100, NULL),
(101, NULL),
(102, NULL),
(103, NULL),
(104, NULL),
(105, NULL),
(106, NULL),
(107, NULL),
(108, NULL),
(109, NULL),
(110, NULL),
(111, NULL),
(112, NULL),
(113, NULL),
(114, NULL),
(115, NULL),
(116, NULL),
(117, NULL),
(118, NULL),
(119, NULL),
(120, NULL),
(121, NULL),
(122, NULL),
(123, NULL),
(124, NULL),
(125, NULL),
(126, NULL),
(127, NULL),
(128, NULL),
(129, NULL),
(130, NULL),
(131, NULL),
(132, NULL),
(133, NULL),
(134, NULL),
(135, NULL),
(136, NULL),
(137, NULL),
(138, NULL),
(139, NULL),
(140, NULL),
(141, NULL),
(142, NULL),
(143, NULL),
(144, NULL),
(145, NULL),
(146, NULL),
(147, NULL),
(148, NULL),
(149, NULL),
(150, NULL),
(151, NULL),
(152, NULL),
(153, NULL),
(154, NULL),
(155, NULL),
(156, NULL),
(157, NULL),
(158, NULL),
(159, NULL),
(160, NULL),
(161, NULL),
(162, NULL),
(163, NULL),
(164, NULL),
(165, NULL),
(166, NULL),
(167, NULL),
(168, NULL),
(169, NULL),
(170, NULL),
(171, NULL),
(172, NULL),
(173, NULL),
(174, NULL),
(175, NULL),
(176, NULL),
(177, NULL),
(178, NULL),
(179, NULL),
(180, NULL),
(181, NULL),
(182, NULL),
(183, NULL),
(184, NULL),
(185, NULL),
(186, NULL),
(187, NULL),
(188, NULL),
(189, NULL),
(190, NULL),
(191, NULL),
(192, NULL),
(193, NULL),
(194, NULL),
(195, NULL),
(196, NULL),
(197, NULL),
(198, NULL),
(199, NULL),
(200, NULL),
(201, NULL),
(202, NULL),
(203, NULL),
(204, NULL),
(205, NULL),
(206, NULL),
(207, NULL),
(208, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `objectif_quantitatif`
--

DROP TABLE IF EXISTS `objectif_quantitatif`;
CREATE TABLE IF NOT EXISTS `objectif_quantitatif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=208 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `objectif_quantitatif`
--

INSERT INTO `objectif_quantitatif` (`id`, `libelle`) VALUES
(1, 'QS'),
(2, 'Productivité'),
(3, 'DMT'),
(4, 'Service Level'),
(5, 'Taux de de transformation'),
(6, 'Chiffre d\'affaires'),
(7, 'Panier Moyen'),
(8, NULL),
(9, NULL),
(10, NULL),
(11, NULL),
(12, NULL),
(13, NULL),
(14, NULL),
(15, NULL),
(16, NULL),
(17, NULL),
(18, NULL),
(19, NULL),
(20, NULL),
(21, NULL),
(22, NULL),
(23, NULL),
(24, NULL),
(25, NULL),
(26, NULL),
(27, NULL),
(28, NULL),
(29, NULL),
(30, NULL),
(31, NULL),
(32, NULL),
(33, NULL),
(34, NULL),
(35, NULL),
(36, NULL),
(37, NULL),
(38, NULL),
(39, NULL),
(40, NULL),
(41, NULL),
(42, NULL),
(43, NULL),
(44, NULL),
(45, NULL),
(46, NULL),
(47, NULL),
(48, NULL),
(49, NULL),
(50, NULL),
(51, NULL),
(52, NULL),
(53, NULL),
(54, NULL),
(55, NULL),
(56, NULL),
(57, NULL),
(58, NULL),
(59, NULL),
(60, NULL),
(61, NULL),
(62, NULL),
(63, NULL),
(64, NULL),
(65, NULL),
(66, NULL),
(67, NULL),
(68, NULL),
(69, NULL),
(70, NULL),
(71, NULL),
(72, NULL),
(73, NULL),
(74, NULL),
(75, NULL),
(76, NULL),
(77, NULL),
(78, NULL),
(79, NULL),
(80, NULL),
(81, NULL),
(82, NULL),
(83, NULL),
(84, NULL),
(85, NULL),
(86, NULL),
(87, NULL),
(88, NULL),
(89, NULL),
(90, NULL),
(91, NULL),
(92, NULL),
(93, NULL),
(94, NULL),
(95, NULL),
(96, NULL),
(97, NULL),
(98, NULL),
(99, NULL),
(100, NULL),
(101, NULL),
(102, NULL),
(103, NULL),
(104, NULL),
(105, NULL),
(106, NULL),
(107, NULL),
(108, NULL),
(109, NULL),
(110, NULL),
(111, NULL),
(112, NULL),
(113, NULL),
(114, NULL),
(115, NULL),
(116, NULL),
(117, NULL),
(118, NULL),
(119, NULL),
(120, NULL),
(121, NULL),
(122, NULL),
(123, NULL),
(124, NULL),
(125, NULL),
(126, NULL),
(127, NULL),
(128, NULL),
(129, NULL),
(130, NULL),
(131, NULL),
(132, NULL),
(133, NULL),
(134, NULL),
(135, NULL),
(136, NULL),
(137, NULL),
(138, NULL),
(139, NULL),
(140, NULL),
(141, NULL),
(142, NULL),
(143, NULL),
(144, NULL),
(145, NULL),
(146, NULL),
(147, NULL),
(148, NULL),
(149, NULL),
(150, NULL),
(151, NULL),
(152, NULL),
(153, NULL),
(154, NULL),
(155, NULL),
(156, NULL),
(157, NULL),
(158, NULL),
(159, NULL),
(160, NULL),
(161, NULL),
(162, NULL),
(163, NULL),
(164, NULL),
(165, NULL),
(166, NULL),
(167, NULL),
(168, NULL),
(169, NULL),
(170, NULL),
(171, NULL),
(172, NULL),
(173, NULL),
(174, NULL),
(175, NULL),
(176, NULL),
(177, NULL),
(178, NULL),
(179, NULL),
(180, NULL),
(181, NULL),
(182, NULL),
(183, NULL),
(184, NULL),
(185, NULL),
(186, NULL),
(187, NULL),
(188, NULL),
(189, NULL),
(190, NULL),
(191, NULL),
(192, NULL),
(193, NULL),
(194, NULL),
(195, NULL),
(196, NULL),
(197, NULL),
(198, NULL),
(199, NULL),
(200, NULL),
(201, NULL),
(202, NULL),
(203, NULL),
(204, NULL),
(205, NULL),
(206, NULL),
(207, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `operation`
--

DROP TABLE IF EXISTS `operation`;
CREATE TABLE IF NOT EXISTS `operation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `famille_operation_id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` longtext COLLATE utf8mb4_unicode_ci,
  `reference_article` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1981A66D9EFA7AED` (`famille_operation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=1008 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `operation`
--

INSERT INTO `operation` (`id`, `famille_operation_id`, `libelle`, `description`, `reference_article`) VALUES
(1, 1, 'Forfait de Frais de mise en place', NULL, 'FMP'),
(2, 2, 'Bonus', NULL, 'BON'),
(3, 3, 'Malus', NULL, 'MAL'),
(4, 2, 'Bonus QS', NULL, 'BONQS'),
(5, 3, 'Malus QS', NULL, 'MALQS'),
(6, 2, 'Bonus Qualité', NULL, 'BONQ'),
(7, 3, 'Malus Qualité', NULL, 'MALQ'),
(8, 2, 'Bonus SL', NULL, 'BONSL'),
(9, 3, 'Malus SL', NULL, 'MALSL'),
(10, 2, 'Bonus SAT', NULL, 'BONSAT'),
(11, 3, 'Malus SAT', NULL, 'MALSAT'),
(12, 4, 'Panne Technique Outsourcia', NULL, 'PTO'),
(13, 4, 'Panne Technique CLIENT', NULL, 'PTC'),
(14, 5, 'Forfait Mensuel de Pilotage', NULL, 'PIL'),
(15, 6, 'Frais Téléphonique Mensuel', NULL, 'TEL'),
(16, 7, 'Formation Initiale', NULL, 'FORI'),
(17, 7, 'Formation Continue', NULL, 'FORC'),
(18, 8, 'Appels Traités FR', NULL, 'INAFR'),
(19, 8, 'Appels Traités FR HNO', NULL, 'INAFRH'),
(20, 8, 'Appels Traités FR Avant Vente', NULL, 'INAFRAV'),
(21, 8, 'Appels Traités FR Commandes', NULL, 'INAFRC'),
(22, 8, 'Appels Traités FR Après Vente', NULL, 'INAFRSAV'),
(23, 8, 'Appels Traités FR Avant Vente HNO', NULL, 'INAFRAVH'),
(24, 8, 'Appels Traités FR Commandes HNO', NULL, 'INAFRCH'),
(25, 8, 'Appels Traités FR Après Vente HNO', NULL, 'INAFRSAVH'),
(26, 8, 'Appels Traités FR Niveau 2 ', NULL, 'INAFRN2'),
(27, 8, 'Appels Traités FR Niveau 2  HNO', NULL, 'INAFRN2H'),
(28, 8, 'Appels Traités FR Niveau 3 ', NULL, 'INAFRN3'),
(29, 8, 'Appels Traités FR Niveau 3  HNO', NULL, 'INAFRN3H'),
(30, 8, 'Emails Traités FR ', NULL, 'INEFR'),
(31, 8, 'Emails Traités FR  HNO', NULL, 'INEFRH'),
(32, 8, 'Emails Traités FR Avant Vente', NULL, 'INEFRAV'),
(33, 8, 'Emails Traités FR Commande', NULL, 'INEFRC'),
(34, 8, 'Emails Traités FR Aprés Vente', NULL, 'INEFRSAV'),
(35, 8, 'Emails Traités FR Avant Vente HNO', NULL, 'INEFRAVH'),
(36, 8, 'Emails Traités FR Commande HNO', NULL, 'INEFRCH'),
(37, 8, 'Emails Traités FR Après Vente HNO', NULL, 'INEFRSAVH'),
(38, 8, 'Emails Traités FR Niveau 2 ', NULL, 'INEFRN2'),
(39, 8, 'Emails Traités FR Niveau 2  HNO', NULL, 'INEFRN2H'),
(40, 8, 'Emails Traités FR Niveau 3 ', NULL, 'INEFRN3'),
(41, 8, 'Emails Traités FR Niveau 3  HNO', NULL, 'INEFRN3H'),
(42, 8, 'Chats Traités FR', NULL, 'INCFR'),
(43, 8, 'Chats Traités FR HNO', NULL, 'INCFRH'),
(44, 8, 'Chats Traités FR Avant Vente', NULL, 'INCFRAV'),
(45, 8, 'Chats Traités FR Commande', NULL, 'INCFRC'),
(46, 8, 'Chats Traités FR Après Vente', NULL, 'INCFRSAV'),
(47, 8, 'Chats Traités FR Avant Vente HNO', NULL, 'INCFRAVH'),
(48, 8, 'Chats Traités FR Commande HNO', NULL, 'INCFRCH'),
(49, 8, 'Chats Traités FR Après Vente HNO', NULL, 'INCFRSAVH'),
(50, 8, 'Chats Traités FR Niveau 2 ', NULL, 'INCFRN2'),
(51, 8, 'Chats Traités FR Niveau 2  HNO', NULL, 'INCFRN2H'),
(52, 8, 'Chats Traités FR Niveau 3 ', NULL, 'INCFRN3'),
(53, 8, 'Chats Traités FR Niveau 3  HNO', NULL, 'INCFRN3H'),
(54, 8, 'Appels Traités UK', NULL, 'INAUK'),
(55, 8, 'Appels Traités UK HNO', NULL, 'INAUKH'),
(56, 8, 'Appels Traités UK Avant Vente', NULL, 'INAUKAV'),
(57, 8, 'Appels Traités UK Commandes', NULL, 'INAUKC'),
(58, 8, 'Appels Traités UK Après Vente', NULL, 'INAUKSAV'),
(59, 8, 'Appels Traités UK Avant Vente HNO', NULL, 'INAUKAVH'),
(60, 8, 'Appels Traités UK Commandes HNO', NULL, 'INAUKCH'),
(61, 8, 'Appels Traités UK Après Vente HNO', NULL, 'INAUKSAVH'),
(62, 8, 'Appels Traités UK Niveau 2 ', NULL, 'INAUKN2'),
(63, 8, 'Appels Traités UK Niveau 2  HNO', NULL, 'INAUKN2H'),
(64, 8, 'Appels Traités UK Niveau 3 ', NULL, 'INAUKN3'),
(65, 8, 'Appels Traités UK Niveau 3  HNO', NULL, 'INAUKN3H'),
(66, 8, 'Emails Traités UK ', NULL, 'INEUK'),
(67, 8, 'Emails Traités UK  HNO', NULL, 'INEUKH'),
(68, 8, 'Emails Traités UK Avant Vente', NULL, 'INEUKAV'),
(69, 8, 'Emails Traités UK Commande', NULL, 'INEUKC'),
(70, 8, 'Emails Traités UK Aprés Vente', NULL, 'INEUKSAV'),
(71, 8, 'Emails Traités UK Avant Vente HNO', NULL, 'INEUKAVH'),
(72, 8, 'Emails Traités UK Commande HNO', NULL, 'INEUKCH'),
(73, 8, 'Emails Traités UK Après Vente HNO', NULL, 'INEUKSAVH'),
(74, 8, 'Emails Traités UK Niveau 2 ', NULL, 'INEUKN2'),
(75, 8, 'Emails Traités UK Niveau 2  HNO', NULL, 'INEUKN2H'),
(76, 8, 'Emails Traités UK Niveau 3 ', NULL, 'INEUKN3'),
(77, 8, 'Emails Traités UK Niveau 3  HNO', NULL, 'INEUKN3H'),
(78, 8, 'Chats Traités UK', NULL, 'INCUK'),
(79, 8, 'Chats Traités UK HNO', NULL, 'INCUKH'),
(80, 8, 'Chats Traités UK Avant Vente', NULL, 'INCUKAV'),
(81, 8, 'Chats Traités UK Commande', NULL, 'INCUKC'),
(82, 8, 'Chats Traités UK Après Vente', NULL, 'INCUKSAV'),
(83, 8, 'Chats Traités UK Avant Vente HNO', NULL, 'INCUKAVH'),
(84, 8, 'Chats Traités UK Commande HNO', NULL, 'INCUKCH'),
(85, 8, 'Chats Traités UK Après Vente HNO', NULL, 'INCUKSAVH'),
(86, 8, 'Chats Traités UK Niveau 2 ', NULL, 'INCUKN2'),
(87, 8, 'Chats Traités UK Niveau 2  HNO', NULL, 'INCUKN2H'),
(88, 8, 'Chats Traités UK Niveau 3 ', NULL, 'INCUKN3'),
(89, 8, 'Chats Traités UK Niveau 3  HNO', NULL, 'INCUKN3H'),
(90, 8, 'Appels Traités IT', NULL, 'INAIT'),
(91, 8, 'Appels Traités IT HNO', NULL, 'INAITH'),
(92, 8, 'Appels Traités IT Avant Vente', NULL, 'INAITAV'),
(93, 8, 'Appels Traités IT Commandes', NULL, 'INAITC'),
(94, 8, 'Appels Traités IT Après Vente', NULL, 'INAITSAV'),
(95, 8, 'Appels Traités IT Avant Vente HNO', NULL, 'INAITAVH'),
(96, 8, 'Appels Traités IT Commandes HNO', NULL, 'INAITCH'),
(97, 8, 'Appels Traités IT Après Vente HNO', NULL, 'INAITSAVH'),
(98, 8, 'Appels Traités IT Niveau 2 ', NULL, 'INAITN2'),
(99, 8, 'Appels Traités IT Niveau 2  HNO', NULL, 'INAITN2H'),
(100, 8, 'Appels Traités IT Niveau 3 ', NULL, 'INAITN3'),
(101, 8, 'Appels Traités IT Niveau 3  HNO', NULL, 'INAITN3H'),
(102, 8, 'Emails Traités IT ', NULL, 'INEIT'),
(103, 8, 'Emails Traités IT  HNO', NULL, 'INEITH'),
(104, 8, 'Emails Traités IT Avant Vente', NULL, 'INEITAV'),
(105, 8, 'Emails Traités IT Commande', NULL, 'INEITC'),
(106, 8, 'Emails Traités IT Aprés Vente', NULL, 'INEITSAV'),
(107, 8, 'Emails Traités IT Avant Vente HNO', NULL, 'INEITAVH'),
(108, 8, 'Emails Traités IT Commande HNO', NULL, 'INEITCH'),
(109, 8, 'Emails Traités IT Après Vente HNO', NULL, 'INEITSAVH'),
(110, 8, 'Emails Traités IT Niveau 2 ', NULL, 'INEITN2'),
(111, 8, 'Emails Traités IT Niveau 2  HNO', NULL, 'INEITN2H'),
(112, 8, 'Emails Traités IT Niveau 3 ', NULL, 'INEITN3'),
(113, 8, 'Emails Traités IT Niveau 3  HNO', NULL, 'INEITN3H'),
(114, 8, 'Chats Traités IT', NULL, 'INCIT'),
(115, 8, 'Chats Traités IT HNO', NULL, 'INCITH'),
(116, 8, 'Chats Traités IT Avant Vente', NULL, 'INCITAV'),
(117, 8, 'Chats Traités IT Commande', NULL, 'INCITC'),
(118, 8, 'Chats Traités IT Après Vente', NULL, 'INCITSAV'),
(119, 8, 'Chats Traités IT Avant Vente HNO', NULL, 'INCITAVH'),
(120, 8, 'Chats Traités IT Commande HNO', NULL, 'INCITCH'),
(121, 8, 'Chats Traités IT Après Vente HNO', NULL, 'INCITSAVH'),
(122, 8, 'Chats Traités IT Niveau 2 ', NULL, 'INCITN2'),
(123, 8, 'Chats Traités IT Niveau 2  HNO', NULL, 'INCITN2H'),
(124, 8, 'Chats Traités IT Niveau 3 ', NULL, 'INCITN3'),
(125, 8, 'Chats Traités IT Niveau 3  HNO', NULL, 'INCITN3H'),
(126, 8, 'Appels Traités ES', NULL, 'INAES'),
(127, 8, 'Appels Traités ES HNO', NULL, 'INAESH'),
(128, 8, 'Appels Traités ES Avant Vente', NULL, 'INAESAV'),
(129, 8, 'Appels Traités ES Commandes', NULL, 'INAESC'),
(130, 8, 'Appels Traités ES Après Vente', NULL, 'INAESSAV'),
(131, 8, 'Appels Traités ES Avant Vente HNO', NULL, 'INAESAVH'),
(132, 8, 'Appels Traités ES Commandes HNO', NULL, 'INAESCH'),
(133, 8, 'Appels Traités ES Après Vente HNO', NULL, 'INAESSAVH'),
(134, 8, 'Appels Traités ES Niveau 2 ', NULL, 'INAESN2'),
(135, 8, 'Appels Traités ES Niveau 2  HNO', NULL, 'INAESN2H'),
(136, 8, 'Appels Traités ES Niveau 3 ', NULL, 'INAESN3'),
(137, 8, 'Appels Traités ES Niveau 3  HNO', NULL, 'INAESN3H'),
(138, 8, 'Emails Traités ES ', NULL, 'INEES'),
(139, 8, 'Emails Traités ES  HNO', NULL, 'INEESH'),
(140, 8, 'Emails Traités ES Avant Vente', NULL, 'INEESAV'),
(141, 8, 'Emails Traités ES Commande', NULL, 'INEESC'),
(142, 8, 'Emails Traités ES Aprés Vente', NULL, 'INEESSAV'),
(143, 8, 'Emails Traités ES Avant Vente HNO', NULL, 'INEESAVH'),
(144, 8, 'Emails Traités ES Commande HNO', NULL, 'INEESCH'),
(145, 8, 'Emails Traités ES Après Vente HNO', NULL, 'INEESSAVH'),
(146, 8, 'Emails Traités ES Niveau 2 ', NULL, 'INEESN2'),
(147, 8, 'Emails Traités ES Niveau 2  HNO', NULL, 'INEESN2H'),
(148, 8, 'Emails Traités ES Niveau 3 ', NULL, 'INEESN3'),
(149, 8, 'Emails Traités ES Niveau 3  HNO', NULL, 'INEESN3H'),
(150, 8, 'Chats Traités ES', NULL, 'INCES'),
(151, 8, 'Chats Traités ES HNO', NULL, 'INCESH'),
(152, 8, 'Chats Traités ES Avant Vente', NULL, 'INCESAV'),
(153, 8, 'Chats Traités ES Commande', NULL, 'INCESC'),
(154, 8, 'Chats Traités ES Après Vente', NULL, 'INCESSAV'),
(155, 8, 'Chats Traités ES Avant Vente HNO', NULL, 'INCESAVH'),
(156, 8, 'Chats Traités ES Commande HNO', NULL, 'INCESCH'),
(157, 8, 'Chats Traités ES Après Vente HNO', NULL, 'INCESSAVH'),
(158, 8, 'Chats Traités ES Niveau 2 ', NULL, 'INCESN2'),
(159, 8, 'Chats Traités ES Niveau 2  HNO', NULL, 'INCESN2H'),
(160, 8, 'Chats Traités ES Niveau 3 ', NULL, 'INCESN3'),
(161, 8, 'Chats Traités ES Niveau 3  HNO', NULL, 'INCESN3H'),
(162, 8, 'Appels Traités US', NULL, 'INAUS'),
(163, 8, 'Appels Traités US HNO', NULL, 'INAUSH'),
(164, 8, 'Appels Traités US Avant Vente', NULL, 'INAUSAV'),
(165, 8, 'Appels Traités US Commandes', NULL, 'INAUSC'),
(166, 8, 'Appels Traités US Après Vente', NULL, 'INAUSSAV'),
(167, 8, 'Appels Traités US Avant Vente HNO', NULL, 'INAUSAVH'),
(168, 8, 'Appels Traités US Commandes HNO', NULL, 'INAUSCH'),
(169, 8, 'Appels Traités US Après Vente HNO', NULL, 'INAUSSAVH'),
(170, 8, 'Appels Traités US Niveau 2 ', NULL, 'INAUSN2'),
(171, 8, 'Appels Traités US Niveau 2  HNO', NULL, 'INAUSN2H'),
(172, 8, 'Appels Traités US Niveau 3 ', NULL, 'INAUSN3'),
(173, 8, 'Appels Traités US Niveau 3  HNO', NULL, 'INAUSN3H'),
(174, 8, 'Emails Traités US ', NULL, 'INEUS'),
(175, 8, 'Emails Traités US  HNO', NULL, 'INEUSH'),
(176, 8, 'Emails Traités US Avant Vente', NULL, 'INEUSAV'),
(177, 8, 'Emails Traités US Commande', NULL, 'INEUSC'),
(178, 8, 'Emails Traités US Aprés Vente', NULL, 'INEUSSAV'),
(179, 8, 'Emails Traités US Avant Vente HNO', NULL, 'INEUSAVH'),
(180, 8, 'Emails Traités US Commande HNO', NULL, 'INEUSCH'),
(181, 8, 'Emails Traités US Après Vente HNO', NULL, 'INEUSSAVH'),
(182, 8, 'Emails Traités US Niveau 2 ', NULL, 'INEUSN2'),
(183, 8, 'Emails Traités US Niveau 2  HNO', NULL, 'INEUSN2H'),
(184, 8, 'Emails Traités US Niveau 3 ', NULL, 'INEUSN3'),
(185, 8, 'Emails Traités US Niveau 3  HNO', NULL, 'INEUSN3H'),
(186, 8, 'Chats Traités US', NULL, 'INCUS'),
(187, 8, 'Chats Traités US HNO', NULL, 'INCUSH'),
(188, 8, 'Chats Traités US Avant Vente', NULL, 'INCUSAV'),
(189, 8, 'Chats Traités US Commande', NULL, 'INCUSC'),
(190, 8, 'Chats Traités US Après Vente', NULL, 'INCUSSAV'),
(191, 8, 'Chats Traités US Avant Vente HNO', NULL, 'INCUSAVH'),
(192, 8, 'Chats Traités US Commande HNO', NULL, 'INCUSCH'),
(193, 8, 'Chats Traités US Après Vente HNO', NULL, 'INCUSSAVH'),
(194, 8, 'Chats Traités US Niveau 2 ', NULL, 'INCUSN2'),
(195, 8, 'Chats Traités US Niveau 2  HNO', NULL, 'INCUSN2H'),
(196, 8, 'Chats Traités US Niveau 3 ', NULL, 'INCUSN3'),
(197, 8, 'Chats Traités US Niveau 3  HNO', NULL, 'INCUSN3H'),
(198, 8, 'Appels Traités DE', NULL, 'INADE'),
(199, 8, 'Appels Traités DE HNO', NULL, 'INADEH'),
(200, 8, 'Appels Traités DE Avant Vente', NULL, 'INADEAV'),
(201, 8, 'Appels Traités DE Commandes', NULL, 'INADEC'),
(202, 8, 'Appels Traités DE Après Vente', NULL, 'INADESAV'),
(203, 8, 'Appels Traités DE Avant Vente HNO', NULL, 'INADEAVH'),
(204, 8, 'Appels Traités DE Commandes HNO', NULL, 'INADECH'),
(205, 8, 'Appels Traités DE Après Vente HNO', NULL, 'INADESAVH'),
(206, 8, 'Appels Traités DE Niveau 2 ', NULL, 'INADEN2'),
(207, 8, 'Appels Traités DE Niveau 2  HNO', NULL, 'INADEN2H'),
(208, 8, 'Appels Traités DE Niveau 3 ', NULL, 'INADEN3'),
(209, 8, 'Appels Traités DE Niveau 3  HNO', NULL, 'INADEN3H'),
(210, 8, 'Emails Traités DE ', NULL, 'INEDE'),
(211, 8, 'Emails Traités DE  HNO', NULL, 'INEDEH'),
(212, 8, 'Emails Traités DE Avant Vente', NULL, 'INEDEAV'),
(213, 8, 'Emails Traités DE Commande', NULL, 'INEDEC'),
(214, 8, 'Emails Traités DE Aprés Vente', NULL, 'INEDESAV'),
(215, 8, 'Emails Traités DE Avant Vente HNO', NULL, 'INEDEAVH'),
(216, 8, 'Emails Traités DE Commande HNO', NULL, 'INEDECH'),
(217, 8, 'Emails Traités DE Après Vente HNO', NULL, 'INEDESAVH'),
(218, 8, 'Emails Traités DE Niveau 2 ', NULL, 'INEDEN2'),
(219, 8, 'Emails Traités DE Niveau 2 HNO', NULL, 'INEDEN2H'),
(220, 8, 'Emails Traités DE Niveau 3 ', NULL, 'INEDEN3'),
(221, 8, 'Emails Traités DE Niveau 3 HNO', NULL, 'INEDEN3H'),
(222, 8, 'Chats Traités DE', NULL, 'INCDE'),
(223, 8, 'Chats Traités DE HNO', NULL, 'INCDEH'),
(224, 8, 'Chats Traités DE Avant Vente', NULL, 'INCDEAV'),
(225, 8, 'Chats Traités DE Commande', NULL, 'INCDEC'),
(226, 8, 'Chats Traités DE Après Vente', NULL, 'INCDESAV'),
(227, 8, 'Chats Traités DE Avant Vente HNO', NULL, 'INCDEAVH'),
(228, 8, 'Chats Traités DE Commande HNO', NULL, 'INCDECH'),
(229, 8, 'Chats Traités DE Après Vente HNO', NULL, 'INCDESAVH'),
(230, 8, 'Chats Traités DE Niveau 2 ', NULL, 'INCDEN2'),
(231, 8, 'Chats Traités DE Niveau 2  HNO', NULL, 'INCDEN2H'),
(232, 8, 'Chats Traités DE Niveau 3 ', NULL, 'INCDEN3'),
(233, 8, 'Chats Traités DE Niveau 3  HNO', NULL, 'INCDEN3H'),
(234, 8, 'Appels Traités NL', NULL, 'INANL'),
(235, 8, 'Appels Traités NL HNO', NULL, 'INANLH'),
(236, 8, 'Appels Traités NL Avant Vente', NULL, 'INANLAV'),
(237, 8, 'Appels Traités NL Commandes', NULL, 'INANLC'),
(238, 8, 'Appels Traités NL Après Vente', NULL, 'INANLSAV'),
(239, 8, 'Appels Traités NL Avant Vente HNO', NULL, 'INANLAVH'),
(240, 8, 'Appels Traités NL Commandes HNO', NULL, 'INANLCH'),
(241, 8, 'Appels Traités NL Après Vente HNO', NULL, 'INANLSAVH'),
(242, 8, 'Appels Traités NL Niveau 2 ', NULL, 'INANLN2'),
(243, 8, 'Appels Traités NL Niveau 2  HNO', NULL, 'INANLN2H'),
(244, 8, 'Appels Traités NL Niveau 3 ', NULL, 'INANLN3'),
(245, 8, 'Appels Traités NL Niveau 3  HNO', NULL, 'INANLN3H'),
(246, 8, 'Emails Traités NL ', NULL, 'INENL'),
(247, 8, 'Emails Traités NL  HNO', NULL, 'INENLH'),
(248, 8, 'Emails Traités NL Avant Vente', NULL, 'INENLAV'),
(249, 8, 'Emails Traités NL Commande', NULL, 'INENLC'),
(250, 8, 'Emails Traités NL Aprés Vente', NULL, 'INENLSAV'),
(251, 8, 'Emails Traités NL Avant Vente HNO', NULL, 'INENLAVH'),
(252, 8, 'Emails Traités NL Commande HNO', NULL, 'INENLCH'),
(253, 8, 'Emails Traités NL Après Vente HNO', NULL, 'INENLSAVH'),
(254, 8, 'Emails Traités NL Niveau 2 ', NULL, 'INENLN2'),
(255, 8, 'Emails Traités NL Niveau 2  HNO', NULL, 'INENLN2H'),
(256, 8, 'Emails Traités NL Niveau 3 ', NULL, 'INENLN3'),
(257, 8, 'Emails Traités NL Niveau 3  HNO', NULL, 'INENLN3H'),
(258, 8, 'Chats Traités NL', NULL, 'INCNL'),
(259, 8, 'Chats Traités NL HNO', NULL, 'INCNLH'),
(260, 8, 'Chats Traités NL Avant Vente', NULL, 'INCNLAV'),
(261, 8, 'Chats Traités NL Commande', NULL, 'INCNLC'),
(262, 8, 'Chats Traités NL Après Vente', NULL, 'INCNLSAV'),
(263, 8, 'Chats Traités NL Avant Vente HNO', NULL, 'INCNLAVH'),
(264, 8, 'Chats Traités NL Commande HNO', NULL, 'INCNLCH'),
(265, 8, 'Chats Traités NL Après Vente HNO', NULL, 'INCNLSAVH'),
(266, 8, 'Chats Traités NL Niveau 2 ', NULL, 'INCNLN2'),
(267, 8, 'Chats Traités NL Niveau 2  HNO', NULL, 'INCNLN2H'),
(268, 8, 'Chats Traités NL Niveau 3 ', NULL, 'INCNLN3'),
(269, 8, 'Chats Traités NL Niveau 3  HNO', NULL, 'INCNLN3H'),
(270, 8, 'Appels Traités PT', NULL, 'INAPT'),
(271, 8, 'Appels Traités PT HNO', NULL, 'INAPTH'),
(272, 8, 'Appels Traités PT Avant Vente', NULL, 'INAPTAV'),
(273, 8, 'Appels Traités PT Commandes', NULL, 'INAPTC'),
(274, 8, 'Appels Traités PT Après Vente', NULL, 'INAPTSAV'),
(275, 8, 'Appels Traités PT Avant Vente HNO', NULL, 'INAPTAVH'),
(276, 8, 'Appels Traités PT Commandes HNO', NULL, 'INAPTCH'),
(277, 8, 'Appels Traités PT Après Vente HNO', NULL, 'INAPTSAVH'),
(278, 8, 'Appels Traités PT Niveau 2 ', NULL, 'INAPTN2'),
(279, 8, 'Appels Traités PT Niveau 2  HNO', NULL, 'INAPTN2H'),
(280, 8, 'Appels Traités PT Niveau 3 ', NULL, 'INAPTN3'),
(281, 8, 'Appels Traités PT Niveau 3  HNO', NULL, 'INAPTN3H'),
(282, 8, 'Emails Traités PT ', NULL, 'INEPT'),
(283, 8, 'Emails Traités PT  HNO', NULL, 'INEPTH'),
(284, 8, 'Emails Traités PT Avant Vente', NULL, 'INEPTAV'),
(285, 8, 'Emails Traités PT Commande', NULL, 'INEPTC'),
(286, 8, 'Emails Traités PT Aprés Vente', NULL, 'INEPTSAV'),
(287, 8, 'Emails Traités PT Avant Vente HNO', NULL, 'INEPTAVH'),
(288, 8, 'Emails Traités PT Commande HNO', NULL, 'INEPTCH'),
(289, 8, 'Emails Traités PT Après Vente HNO', NULL, 'INEPTSAVH'),
(290, 8, 'Emails Traités PT Niveau 2 ', NULL, 'INEPTN2'),
(291, 8, 'Emails Traités PT Niveau 2  HNO', NULL, 'INEPTN2H'),
(292, 8, 'Emails Traités PT Niveau 3 ', NULL, 'INEPTN3'),
(293, 8, 'Emails Traités PT Niveau 3  HNO', NULL, 'INEPTN3H'),
(294, 8, 'Chats Traités PT', NULL, 'INCPT'),
(295, 8, 'Chats Traités PT HNO', NULL, 'INCPTH'),
(296, 8, 'Chats Traités PT Avant Vente', NULL, 'INCPTAV'),
(297, 8, 'Chats Traités PT Commande', NULL, 'INCPTC'),
(298, 8, 'Chats Traités PT Après Vente', NULL, 'INCPTSAV'),
(299, 8, 'Chats Traités PT Avant Vente HNO', NULL, 'INCPTAVH'),
(300, 8, 'Chats Traités PT Commande HNO', NULL, 'INCPTCH'),
(301, 8, 'Chats Traités PT Après Vente HNO', NULL, 'INCPTSAVH'),
(302, 8, 'Chats Traités PT Niveau 2 ', NULL, 'INCPTN2'),
(303, 8, 'Chats Traités PT Niveau 2  HNO', NULL, 'INCPTN2H'),
(304, 8, 'Chats Traités PT Niveau 3 ', NULL, 'INCPTN3'),
(305, 8, 'Chats Traités PT Niveau 3  HNO', NULL, 'INCPTN3H'),
(306, 8, 'Appels Traités BR', NULL, 'INABR'),
(307, 8, 'Appels Traités BR HNO', NULL, 'INABRH'),
(308, 8, 'Appels Traités BR Avant Vente', NULL, 'INABRAV'),
(309, 8, 'Appels Traités BR Commandes', NULL, 'INABRC'),
(310, 8, 'Appels Traités BR Après Vente', NULL, 'INABRSAV'),
(311, 8, 'Appels Traités BR Avant Vente HNO', NULL, 'INABRAVH'),
(312, 8, 'Appels Traités BR Commandes HNO', NULL, 'INABRCH'),
(313, 8, 'Appels Traités BR Après Vente HNO', NULL, 'INABRSAVH'),
(314, 8, 'Appels Traités BR Niveau 2 ', NULL, 'INABRN2'),
(315, 8, 'Appels Traités BR Niveau 2  HNO', NULL, 'INABRN2H'),
(316, 8, 'Appels Traités BR Niveau 3 ', NULL, 'INABRN3'),
(317, 8, 'Appels Traités BR Niveau 3  HNO', NULL, 'INABRN3H'),
(318, 8, 'Emails Traités BR ', NULL, 'INEBR'),
(319, 8, 'Emails Traités BR  HNO', NULL, 'INEBRH'),
(320, 8, 'Emails Traités BR Avant Vente', NULL, 'INEBRAV'),
(321, 8, 'Emails Traités BR Commande', NULL, 'INEBRC'),
(322, 8, 'Emails Traités BR Aprés Vente', NULL, 'INEBRSAV'),
(323, 8, 'Emails Traités BR Avant Vente HNO', NULL, 'INEBRAVH'),
(324, 8, 'Emails Traités BR Commande HNO', NULL, 'INEBRCH'),
(325, 8, 'Emails Traités BR Après Vente HNO', NULL, 'INEBRSAVH'),
(326, 8, 'Emails Traités BR Niveau 2 ', NULL, 'INEBRN2'),
(327, 8, 'Emails Traités BR Niveau 2  HNO', NULL, 'INEBRN2H'),
(328, 8, 'Emails Traités BR Niveau 3 ', NULL, 'INEBRN3'),
(329, 8, 'Emails Traités BR Niveau 3  HNO', NULL, 'INEBRN3H'),
(330, 8, 'Chats Traités BR', NULL, 'INCBR'),
(331, 8, 'Chats Traités BR HNO', NULL, 'INCBRH'),
(332, 8, 'Chats Traités BR Avant Vente', NULL, 'INCBRAV'),
(333, 8, 'Chats Traités BR Commande', NULL, 'INCBRC'),
(334, 8, 'Chats Traités BR Après Vente', NULL, 'INCBRSAV'),
(335, 8, 'Chats Traités BR Avant Vente HNO', NULL, 'INCBRAVH'),
(336, 8, 'Chats Traités BR Commande HNO', NULL, 'INCBRCH'),
(337, 8, 'Chats Traités BR Après Vente HNO', NULL, 'INCBRSAVH'),
(338, 8, 'Chats Traités BR Niveau 2 ', NULL, 'INCBRN2'),
(339, 8, 'Chats Traités BR Niveau 2  HNO', NULL, 'INCBRN2H'),
(340, 8, 'Chats Traités BR Niveau 3 ', NULL, 'INCBRN3'),
(341, 8, 'Chats Traités BR Niveau 3  HNO', NULL, 'INCBRN3H'),
(342, 8, 'Appels Traités AR', NULL, 'INAAR'),
(343, 8, 'Appels Traités AR HNO', NULL, 'INAARH'),
(344, 8, 'Appels Traités AR Avant Vente', NULL, 'INAARAV'),
(345, 8, 'Appels Traités AR Commandes', NULL, 'INAARC'),
(346, 8, 'Appels Traités AR Après Vente', NULL, 'INAARSAV'),
(347, 8, 'Appels Traités AR Avant Vente HNO', NULL, 'INAARAVH'),
(348, 8, 'Appels Traités AR Commandes HNO', NULL, 'INAARCH'),
(349, 8, 'Appels Traités AR Après Vente HNO', NULL, 'INAARSAVH'),
(350, 8, 'Appels Traités AR Niveau 2 ', NULL, 'INAARN2'),
(351, 8, 'Appels Traités AR Niveau 2  HNO', NULL, 'INAARN2H'),
(352, 8, 'Appels Traités AR Niveau 3 ', NULL, 'INAARN3'),
(353, 8, 'Appels Traités AR Niveau 3  HNO', NULL, 'INAARN3H'),
(354, 8, 'Emails Traités AR ', NULL, 'INEAR'),
(355, 8, 'Emails Traités AR  HNO', NULL, 'INEARH'),
(356, 8, 'Emails Traités AR Avant Vente', NULL, 'INEARAV'),
(357, 8, 'Emails Traités AR Commande', NULL, 'INEARC'),
(358, 8, 'Emails Traités AR Aprés Vente', NULL, 'INEARSAV'),
(359, 8, 'Emails Traités AR Avant Vente HNO', NULL, 'INEARAVH'),
(360, 8, 'Emails Traités AR Commande HNO', NULL, 'INEARCH'),
(361, 8, 'Emails Traités AR Après Vente HNO', NULL, 'INEARSAVH'),
(362, 8, 'Emails Traités AR Niveau 2 ', NULL, 'INEARN2'),
(363, 8, 'Emails Traités AR Niveau 2  HNO', NULL, 'INEARN2H'),
(364, 8, 'Emails Traités AR Niveau 3 ', NULL, 'INEARN3'),
(365, 8, 'Emails Traités AR Niveau 3  HNO', NULL, 'INEARN3H'),
(366, 8, 'Chats Traités AR', NULL, 'INCAR'),
(367, 8, 'Chats Traités AR HNO', NULL, 'INCARH'),
(368, 8, 'Chats Traités AR Avant Vente', NULL, 'INCARAV'),
(369, 8, 'Chats Traités AR Commande', NULL, 'INCARC'),
(370, 8, 'Chats Traités AR Après Vente', NULL, 'INCARSAV'),
(371, 8, 'Chats Traités AR Avant Vente HNO', NULL, 'INCARAVH'),
(372, 8, 'Chats Traités AR Commande HNO', NULL, 'INCARCH'),
(373, 8, 'Chats Traités AR Après Vente HNO', NULL, 'INCARSAVH'),
(374, 8, 'Chats Traités AR Niveau 2 ', NULL, 'INCARN2'),
(375, 8, 'Chats Traités AR Niveau 2  HNO', NULL, 'INCARN2H'),
(376, 8, 'Chats Traités AR Niveau 3 ', NULL, 'INCARN3'),
(377, 8, 'Chats Traités AR Niveau 3  HNO', NULL, 'INCARN3H'),
(378, 9, 'Heures de production FR', NULL, 'INBHFR'),
(379, 9, 'Heures de production FR HNO', NULL, 'INBHFRH'),
(380, 9, 'Heures de production FR Niveau 2 ', NULL, 'INBHFRN2'),
(381, 9, 'Heures de production FR Niveau 2 HNO', NULL, 'INBHFRN2H'),
(382, 9, 'Heures de production FR Niveau 3', NULL, 'INBHFRN3'),
(383, 9, 'Heures de production FR Niveau 3 HNO', NULL, 'INBHFRN3H'),
(384, 9, 'Heures appels FR', NULL, 'INBHAFR'),
(385, 9, 'Heures appels FR HNO', NULL, 'INBHAFRH'),
(386, 9, 'Heures mails FR', NULL, 'INBHMFR'),
(387, 9, 'Heures mail FR HNO', NULL, 'INBHMFRH'),
(388, 9, 'Heures chat FR', NULL, 'INBHCFR'),
(389, 9, 'Heures chat FR HNO', NULL, 'INBHCFRH'),
(390, 9, 'Heures de production UK', NULL, 'INBHUK'),
(391, 9, 'Heures de production UK HNO', NULL, 'INBHUKH'),
(392, 9, 'Heures de production UK Niveau 2 ', NULL, 'INBHUKN2'),
(393, 9, 'Heures de production UK Niveau 2 HNO', NULL, 'INBHUKN2H'),
(394, 9, 'Heures de production UK Niveau 3', NULL, 'INBHUKN3'),
(395, 9, 'Heures de production UK Niveau 3 HNO', NULL, 'INBHUKN3H'),
(396, 9, 'Heures appels UK', NULL, 'INBHAUK'),
(397, 9, 'Heures appels UK HNO', NULL, 'INBHAUKH'),
(398, 9, 'Heures mails UK', NULL, 'INBHMUK'),
(399, 9, 'Heures mail UK HNO', NULL, 'INBHMUKH'),
(400, 9, 'Heures chat UK', NULL, 'INBHCUK'),
(401, 9, 'Heures chat UK HNO', NULL, 'INBHCUKH'),
(402, 9, 'Heures de production IT', NULL, 'INBHIT'),
(403, 9, 'Heures de production IT HNO', NULL, 'INBHITH'),
(404, 9, 'Heures de production IT Niveau 2 ', NULL, 'INBHITN2'),
(405, 9, 'Heures de production IT Niveau 2 HNO', NULL, 'INBHITN2H'),
(406, 9, 'Heures de production IT Niveau 3', NULL, 'INBHITN3'),
(407, 9, 'Heures de production IT Niveau 3 HNO', NULL, 'INBHITN3H'),
(408, 9, 'Heures appels IT', NULL, 'INBHAIT'),
(409, 9, 'Heures appels IT HNO', NULL, 'INBHAITH'),
(410, 9, 'Heures mails IT', NULL, 'INBHMIT'),
(411, 9, 'Heures mail IT HNO', NULL, 'INBHMITH'),
(412, 9, 'Heures chat IT', NULL, 'INBHCIT'),
(413, 9, 'Heures chat IT HNO', NULL, 'INBHCITH'),
(414, 9, 'Heures de production ES', NULL, 'INBHES'),
(415, 9, 'Heures de production ES HNO', NULL, 'INBHESH'),
(416, 9, 'Heures de production ES Niveau 2 ', NULL, 'INBHESN2'),
(417, 9, 'Heures de production ES Niveau 2 HNO', NULL, 'INBHESN2H'),
(418, 9, 'Heures de production ES Niveau 3', NULL, 'INBHESN3'),
(419, 9, 'Heures de production ES Niveau 3 HNO', NULL, 'INBHESN3H'),
(420, 9, 'Heures appels ES', NULL, 'INBHAES'),
(421, 9, 'Heures appels ES HNO', NULL, 'INBHAESH'),
(422, 9, 'Heures mails ES', NULL, 'INBHMES'),
(423, 9, 'Heures mail ES HNO', NULL, 'INBHMESH'),
(424, 9, 'Heures chat ES', NULL, 'INBHCES'),
(425, 9, 'Heures chat ES HNO', NULL, 'INBHCESH'),
(426, 9, 'Heures de production DE', NULL, 'INBHDE'),
(427, 9, 'Heures de production DE HNO', NULL, 'INBHDEH'),
(428, 9, 'Heures de production DE Niveau 2 ', NULL, 'INBHDEN2'),
(429, 9, 'Heures de production DE Niveau 2 HNO', NULL, 'INBHDEN2H'),
(430, 9, 'Heures de production DE Niveau 3', NULL, 'INBHDEN3'),
(431, 9, 'Heures de production DE Niveau 3 HNO', NULL, 'INBHDEN3H'),
(432, 9, 'Heures appels DE', NULL, 'INBHADE'),
(433, 9, 'Heures appels DE HNO', NULL, 'INBHADEH'),
(434, 9, 'Heures mails DE', NULL, 'INBHMDE'),
(435, 9, 'Heures mail DE HNO', NULL, 'INBHMDEH'),
(436, 9, 'Heures chat DE', NULL, 'INBHCDE'),
(437, 9, 'Heures chat DE HNO', NULL, 'INBHCDEH'),
(438, 9, 'Heures de production NL', NULL, 'INBHNL'),
(439, 9, 'Heures de production NL HNO', NULL, 'INBHNLH'),
(440, 9, 'Heures de production NL Niveau 2 ', NULL, 'INBHNLN2'),
(441, 9, 'Heures de production NL Niveau 2 HNO', NULL, 'INBHNLN2H'),
(442, 9, 'Heures de production NL Niveau 3', NULL, 'INBHNLN3'),
(443, 9, 'Heures de production NL Niveau 3 HNO', NULL, 'INBHNLN3H'),
(444, 9, 'Heures appels NL', NULL, 'INBHANL'),
(445, 9, 'Heures appels NL HNO', NULL, 'INBHANLH'),
(446, 9, 'Heures mails NL', NULL, 'INBHMNL'),
(447, 9, 'Heures mail NL HNO', NULL, 'INBHMNLH'),
(448, 9, 'Heures chat NL', NULL, 'INBHCNL'),
(449, 9, 'Heures chat NL HNO', NULL, 'INBHCNLH'),
(450, 9, 'Heures de production PT', NULL, 'INBHPT'),
(451, 9, 'Heures de production PT HNO', NULL, 'INBHPTH'),
(452, 9, 'Heures de production PT Niveau 2 ', NULL, 'INBHPTN2'),
(453, 9, 'Heures de production PT Niveau 2 HNO', NULL, 'INBHPTN2H'),
(454, 9, 'Heures de production PT Niveau 3', NULL, 'INBHPTN3'),
(455, 9, 'Heures de production PT Niveau 3 HNO', NULL, 'INBHPTN3H'),
(456, 9, 'Heures appels PT', NULL, 'INBHAPT'),
(457, 9, 'Heures appels PT HNO', NULL, 'INBHAPTH'),
(458, 9, 'Heures mails PT', NULL, 'INBHMPT'),
(459, 9, 'Heures mail PT HNO', NULL, 'INBHMPTH'),
(460, 9, 'Heures chat PT', NULL, 'INBHCPT'),
(461, 9, 'Heures chat PT HNO', NULL, 'INBHCPTH'),
(462, 9, 'Heures de production BR', NULL, 'INBHBR'),
(463, 9, 'Heures de production BR HNO', NULL, 'INBHBRH'),
(464, 9, 'Heures de production BR Niveau 2 ', NULL, 'INBHBRN2'),
(465, 9, 'Heures de production BR Niveau 2 HNO', NULL, 'INBHBRN2H'),
(466, 9, 'Heures de production BR Niveau 3', NULL, 'INBHBRN3'),
(467, 9, 'Heures de production BR Niveau 3 HNO', NULL, 'INBHBRN3H'),
(468, 9, 'Heures appels BR', NULL, 'INBHABR'),
(469, 9, 'Heures appels BR HNO', NULL, 'INBHABRH'),
(470, 9, 'Heures mails BR', NULL, 'INBHMBR'),
(471, 9, 'Heures mail BR HNO', NULL, 'INBHMBRH'),
(472, 9, 'Heures chat BR', NULL, 'INBHCBR'),
(473, 9, 'Heures chat BR HNO', NULL, 'INBHCBRH'),
(474, 9, 'Heures de production AR', NULL, 'INBHAR'),
(475, 9, 'Heures de production AR HNO', NULL, 'INBHARH'),
(476, 9, 'Heures de production AR Niveau 2 ', NULL, 'INBHARN2'),
(477, 9, 'Heures de production AR Niveau 2 HNO', NULL, 'INBHARN2H'),
(478, 9, 'Heures de production AR Niveau 3', NULL, 'INBHARN3'),
(479, 9, 'Heures de production AR Niveau 3 HNO', NULL, 'INBHARN3H'),
(480, 9, 'Heures appels AR', NULL, 'INBHAAR'),
(481, 9, 'Heures appels AR HNO', NULL, 'INBHAARH'),
(482, 9, 'Heures mails AR', NULL, 'INBHMAR'),
(483, 9, 'Heures mail AR HNO', NULL, 'INBHMARH'),
(484, 9, 'Heures chat AR', NULL, 'INBHCAR'),
(485, 9, 'Heures chat AR HNO', NULL, 'INBHCARH'),
(486, 10, 'Forfait mensuel de Production FR', NULL, 'INBFOFR'),
(487, 10, 'Forfait mensuel de Production UK', NULL, 'INBFOUK'),
(488, 10, 'Forfait mensuel de Production IT', NULL, 'INBFOIT'),
(489, 10, 'Forfait mensuel de Production ES', NULL, 'INBFOES'),
(490, 10, 'Forfait mensuel de Production US', NULL, 'INBFOUS'),
(491, 10, 'Forfait mensuel de Production DE', NULL, 'INBFODE'),
(492, 10, 'Forfait mensuel de Production NL', NULL, 'INBFONL'),
(493, 10, 'Forfait mensuel de Production PT', NULL, 'INBFOPT'),
(494, 10, 'Forfait mensuel de Production BR', NULL, 'INBFOBR'),
(495, 10, 'Forfait mensuel de Production AR', NULL, 'INBFOAR'),
(496, 11, 'Nombre de Contacts Argumentés FR', NULL, 'OUTCAFR'),
(497, 11, 'Nombre de Ventes Nettes FR', NULL, 'OUTVNFR'),
(498, 11, 'Nombre de Ventes Brutes FR', NULL, 'OUTVBFR'),
(499, 11, 'Commissions sur Ventes FR', NULL, 'OUTCOMFR'),
(500, 11, 'Nombre de Fiches Qualifiées FR', NULL, 'OUTFQFR'),
(501, 11, 'Nombre de Rendez-vous FR', NULL, 'OUTRDVFR'),
(502, 11, 'Enquêtes traitées FR', NULL, 'OUTEFR'),
(503, 11, 'Nombre de Fiches Traitées FR', NULL, 'OUTFTFR'),
(504, 11, 'Nombre de Contacts Argumentés UK', NULL, 'OUTCAUK'),
(505, 11, 'Nombre de Ventes Nettes UK', NULL, 'OUTVNUK'),
(506, 11, 'Nombre de Ventes Brutes UK', NULL, 'OUTVBUK'),
(507, 11, 'Commissions sur Ventes UK', NULL, 'OUTCOMUK'),
(508, 11, 'Nombre de Fiches Qualifiées UK', NULL, 'OUTFQUK'),
(509, 11, 'Nombre de Rendez-vous UK', NULL, 'OUTRDVUK'),
(510, 11, 'Enquêtes traitées UK', NULL, 'OUTEUK'),
(511, 11, 'Nombre de Fiches Traitées UK', NULL, 'OUTFTUK'),
(512, 11, 'Nombre de Contacts Argumentés IT', NULL, 'OUTCAIT'),
(513, 11, 'Nombre de Ventes Nettes IT', NULL, 'OUTVNIT'),
(514, 11, 'Nombre de Ventes Brutes IT', NULL, 'OUTVBIT'),
(515, 11, 'Commissions sur Ventes IT', NULL, 'OUTCOMIT'),
(516, 11, 'Nombre de Fiches Qualifiées IT', NULL, 'OUTFQIT'),
(517, 11, 'Nombre de Rendez-vous IT', NULL, 'OUTRDVIT'),
(518, 11, 'Enquêtes traitées IT', NULL, 'OUTEIT'),
(519, 11, 'Nombre de Fiches Traitées IT', NULL, 'OUTFTIT'),
(520, 11, 'Nombre de Contacts Argumentés ES', NULL, 'OUTCAES'),
(521, 11, 'Nombre de Ventes Nettes ES', NULL, 'OUTVNES'),
(522, 11, 'Nombre de Ventes Brutes ES', NULL, 'OUTVBES'),
(523, 11, 'Commissions sur Ventes ES', NULL, 'OUTCOMES'),
(524, 11, 'Nombre de Fiches Qualifiées ES', NULL, 'OUTFQES'),
(525, 11, 'Nombre de Rendez-vous ES', NULL, 'OUTRDVES'),
(526, 11, 'Enquêtes traitées ES', NULL, 'OUTEES'),
(527, 11, 'Nombre de Fiches Traitées ES', NULL, 'OUTFTES'),
(528, 11, 'Nombre de Contacts Argumentés US', NULL, 'OUTCAUS'),
(529, 11, 'Nombre de Ventes Nettes US', NULL, 'OUTVNUS'),
(530, 11, 'Nombre de Ventes Brutes US', NULL, 'OUTVBUS'),
(531, 11, 'Commissions sur Ventes US', NULL, 'OUTCOMUS'),
(532, 11, 'Nombre de Fiches Qualifiées US', NULL, 'OUTFQUS'),
(533, 11, 'Nombre de Rendez-vous US', NULL, 'OUTRDVUS'),
(534, 11, 'Enquêtes traitées US', NULL, 'OUTEUS'),
(535, 11, 'Nombre de Fiches Traitées US', NULL, 'OUTFTUS'),
(536, 11, 'Nombre de Contacts Argumentés DE', NULL, 'OUTCADE'),
(537, 11, 'Nombre de Ventes Nettes DE', NULL, 'OUTVNDE'),
(538, 11, 'Nombre de Ventes Brutes DE', NULL, 'OUTVBDE'),
(539, 11, 'Commissions sur Ventes DE', NULL, 'OUTCOMDE'),
(540, 11, 'Nombre de Fiches Qualifiées DE', NULL, 'OUTFQDE'),
(541, 11, 'Nombre de Rendez-vous DE', NULL, 'OUTRDVDE'),
(542, 11, 'Enquêtes traitées DE', NULL, 'OUTEDE'),
(543, 11, 'Nombre de Fiches Traitées DE', NULL, 'OUTFTDE'),
(544, 11, 'Nombre de Contacts Argumentés NL', NULL, 'OUTCANL'),
(545, 11, 'Nombre de Ventes Nettes NL', NULL, 'OUTVNNL'),
(546, 11, 'Nombre de Ventes Brutes NL', NULL, 'OUTVBNL'),
(547, 11, 'Commissions sur Ventes NL', NULL, 'OUTCOMNL'),
(548, 11, 'Nombre de Fiches Qualifiées NL', NULL, 'OUTFQNL'),
(549, 11, 'Nombre de Rendez-vous NL', NULL, 'OUTRDVNL'),
(550, 11, 'Enquêtes traitées NL', NULL, 'OUTENL'),
(551, 11, 'Nombre de Fiches Traitées NL', NULL, 'OUTFTNL'),
(552, 11, 'Nombre de Contacts Argumentés PT', NULL, 'OUTCAPT'),
(553, 11, 'Nombre de Ventes Nettes PT', NULL, 'OUTVNPT'),
(554, 11, 'Nombre de Ventes Brutes PT', NULL, 'OUTVBPT'),
(555, 11, 'Commissions sur Ventes PT', NULL, 'OUTCOMPT'),
(556, 11, 'Nombre de Fiches Qualifiées PT', NULL, 'OUTFQPT'),
(557, 11, 'Nombre de Rendez-vous PT', NULL, 'OUTRDVPT'),
(558, 11, 'Enquêtes traitées PT', NULL, 'OUTEPT'),
(559, 11, 'Nombre de Fiches Traitées PT', NULL, 'OUTFTPT'),
(560, 11, 'Nombre de Contacts Argumentés BR', NULL, 'OUTCABR'),
(561, 11, 'Nombre de Ventes Nettes BR', NULL, 'OUTVNBR'),
(562, 11, 'Nombre de Ventes Brutes BR', NULL, 'OUTVBBR'),
(563, 11, 'Commissions sur Ventes BR', NULL, 'OUTCOMBR'),
(564, 11, 'Nombre de Fiches Qualifiées BR', NULL, 'OUTFQBR'),
(565, 11, 'Nombre de Rendez-vous BR', NULL, 'OUTRDVBR'),
(566, 11, 'Enquêtes traitées BR', NULL, 'OUTEBR'),
(567, 11, 'Nombre de Fiches Traitées BR', NULL, 'OUTFTBR'),
(568, 11, 'Nombre de Contacts Argumentés AR', NULL, 'OUTCAAR'),
(569, 11, 'Nombre de Ventes Nettes AR', NULL, 'OUTVNAR'),
(570, 11, 'Nombre de Ventes Brutes AR', NULL, 'OUTVBAR'),
(571, 11, 'Commissions sur Ventes AR', NULL, 'OUTCOMAR'),
(572, 11, 'Nombre de Fiches Qualifiées AR', NULL, 'OUTFQAR'),
(573, 11, 'Nombre de Rendez-vous AR', NULL, 'OUTRDVAR'),
(574, 11, 'Enquêtes traitées AR', NULL, 'OUTEAR'),
(575, 11, 'Nombre de Fiches Traitées AR', NULL, 'OUTFTAR'),
(576, 12, 'Heures de Production FR', NULL, 'OUTHFR'),
(577, 12, 'Heures de Production FR HNO', NULL, 'OUTHFH'),
(578, 12, 'Heures de Production UK', NULL, 'OUTHUK'),
(579, 12, 'Heures de Production UK HNO', NULL, 'OUTHUKH'),
(580, 12, 'Heures de Production IT', NULL, 'OUTHIT'),
(581, 12, 'Heures de Production IT HNO', NULL, 'OUTHITH'),
(582, 12, 'Heures de Production ES', NULL, 'OUTHES'),
(583, 12, 'Heures de Production ES HNO', NULL, 'OUTHESH'),
(584, 12, 'Heures de Production US', NULL, 'OUTHUS'),
(585, 12, 'Heures de Production US HNO', NULL, 'OUTHUSH'),
(586, 12, 'Heures de Production DE', NULL, 'OUTHDE'),
(587, 12, 'Heures de Production DE HNO', NULL, 'OUTHDEH'),
(588, 12, 'Heures de Production NL', NULL, 'OUTHNL'),
(589, 12, 'Heures de Production NL HNO', NULL, 'OUTHNLH'),
(590, 12, 'Heures de Production PT', NULL, 'OUTHPT'),
(591, 12, 'Heures de Production PT HNO', NULL, 'OUTHPTH'),
(592, 12, 'Heures de Production BR', NULL, 'OUTHBR'),
(593, 12, 'Heures de Production BR HNO', NULL, 'OUTHBRH'),
(594, 12, 'Heures de Production AR', NULL, 'OUTHAR'),
(595, 12, 'Heures de Production AR HNO', NULL, 'OUTHARH'),
(596, 13, 'Forfait mensuel de Production FR', NULL, 'OUTFOFR'),
(597, 13, 'Forfait mensuel de Production UK', NULL, 'OUTFOUK'),
(598, 13, 'Forfait mensuel de Production IT', NULL, 'OUTFOIT'),
(599, 13, 'Forfait mensuel de Production ES', NULL, 'OUTFOES'),
(600, 13, 'Forfait mensuel de Production US', NULL, 'OUTFOUS'),
(601, 13, 'Forfait mensuel de Production DE', NULL, 'OUTFODE'),
(602, 13, 'Forfait mensuel de Production NL', NULL, 'OUTFONL'),
(603, 13, 'Forfait mensuel de Production PT', NULL, 'OUTFOPT'),
(604, 13, 'Forfait mensuel de Production BR', NULL, 'OUTFOBR'),
(605, 13, 'Forfait mensuel de Production AR', NULL, 'OUTFOAR'),
(606, 14, 'Appels Traités FR', NULL, 'HELAFR'),
(607, 14, 'Appels Traités FR HNO', NULL, 'HELAFRH'),
(608, 14, 'Appels Traités FR Niveau 2 ', NULL, 'HELAFRN2'),
(609, 14, 'Appels Traités FR Niveau 2  HNO', NULL, 'HELAFRN2H'),
(610, 14, 'Appels Traités FR Niveau 3 ', NULL, 'HELAFRN3'),
(611, 14, 'Appels Traités FR Niveau 3  HNO', NULL, 'HELAFRN3H'),
(612, 14, 'Emails Traités FR ', NULL, 'HELEFR'),
(613, 14, 'Emails Traités FR  HNO', NULL, 'HELEFRH'),
(614, 14, 'Emails Traités FR Niveau 2 ', NULL, 'HELEFRN2'),
(615, 14, 'Emails Traités FR Niveau 2  HNO', NULL, 'HELEFRN2H'),
(616, 14, 'Emails Traités FR Niveau 3 ', NULL, 'HELEFRN3'),
(617, 14, 'Emails Traités FR Niveau 3  HNO', NULL, 'HELEFRN3H'),
(618, 14, 'Chats Traités FR', NULL, 'HELCFR'),
(619, 14, 'Chats Traités FR HNO', NULL, 'HELCFRH'),
(620, 14, 'Chats Traités FR Niveau 2 ', NULL, 'HELCFN2'),
(621, 14, 'Chats Traités FR Niveau 2  HNO', NULL, 'HELCFN2H'),
(622, 14, 'Chats Traités FR Niveau 3 ', NULL, 'HELCFN3'),
(623, 14, 'Chats Traités FR Niveau 3  HNO', NULL, 'HELCFN3H'),
(624, 15, 'Heures de production FR', NULL, 'HELHFR'),
(625, 15, 'Heures de production FR HNO', NULL, 'HELHFRH'),
(626, 15, 'Heures de production FR Niveau 2 ', NULL, 'HELHFRN2'),
(627, 15, 'Heures de production FR Niveau 2 HNO', NULL, 'HELHFRN2H'),
(628, 15, 'Heures de production FR Niveau 3', NULL, 'HELHFRN3'),
(629, 15, 'Heures de production FR Niveau 3 HNO', NULL, 'HELHFRN3H'),
(630, 15, 'Heures appels FR', NULL, 'HELHAFR'),
(631, 15, 'Heures appels FR HNO', NULL, 'HELHAFRH'),
(632, 15, 'Heures mails FR', NULL, 'HELHMFR'),
(633, 15, 'Heures mail FR HNO', NULL, 'HELHMFRH'),
(634, 15, 'Heures chat FR', NULL, 'HELHCFR'),
(635, 15, 'Heures chat FR HNO', NULL, 'HELHCFRH'),
(636, 16, 'Forfait mensuel de Production FR', NULL, 'HELFOFR'),
(637, 16, 'Forfait mensuel de Production UK', NULL, 'HELFOUK'),
(638, 16, 'Forfait mensuel de Production IT', NULL, 'HELFOIT'),
(639, 16, 'Forfait mensuel de Production ES', NULL, 'HELFOES'),
(640, 16, 'Forfait mensuel de Production US', NULL, 'HELFOUS'),
(641, 16, 'Forfait mensuel de Production DE', NULL, 'HELFODE'),
(642, 16, 'Forfait mensuel de Production NL', NULL, 'HELFONL'),
(643, 16, 'Forfait mensuel de Production PT', NULL, 'HELFOPT'),
(644, 16, 'Forfait mensuel de Production BR', NULL, 'HELFOBR'),
(645, 16, 'Forfait mensuel de Production AR', NULL, 'HELFOAR'),
(646, 14, 'Appels Traités NL', NULL, 'HELANL'),
(647, 14, 'Appels Traités NL HNO', NULL, 'HELANLH'),
(648, 14, 'Appels Traités NL Niveau 2 ', NULL, 'HELANLN2'),
(649, 14, 'Appels Traités NL Niveau 2  HNO', NULL, 'HELANLN2H'),
(650, 14, 'Appels Traités NL Niveau 3 ', NULL, 'HELANLN3'),
(651, 14, 'Appels Traités NL Niveau 3  HNO', NULL, 'HELANLN3H'),
(652, 14, 'Emails Traités NL', NULL, 'HELENL'),
(653, 14, 'Emails Traités NL  HNO', NULL, 'HELENLH'),
(654, 14, 'Emails Traités NL Niveau 2 ', NULL, 'HELENLN2'),
(655, 14, 'Emails Traités NL Niveau 2  HNO', NULL, 'HELENLN2H'),
(656, 14, 'Emails Traités NL Niveau 3 ', NULL, 'HELENLN3'),
(657, 14, 'Emails Traités NL Niveau 3  HNO', NULL, 'HELENLN3H'),
(658, 14, 'Chats Traités NL', NULL, 'HELCNL'),
(659, 14, 'Chats Traités NL HNO', NULL, 'HELCNLH'),
(660, 14, 'Chats Traités NL Niveau 2 ', NULL, 'HELCNLN2'),
(661, 14, 'Chats Traités NL Niveau 2  HNO', NULL, 'HELCNLN2H'),
(662, 14, 'Chats Traités NL Niveau 3 ', NULL, 'HELCNLN3'),
(663, 14, 'Chats Traités NL Niveau 3  HNO', NULL, 'HELCFN3H'),
(664, 15, 'Heures de production NL', NULL, 'HELHNL'),
(665, 15, 'Heures de production NL HNO', NULL, 'HELHNLH'),
(666, 15, 'Heures de production NL Niveau 2 ', NULL, 'HELHNLN2'),
(667, 15, 'Heures de production NL Niveau 2 HNO', NULL, 'HELHNLN2H'),
(668, 15, 'Heures de production NL Niveau 3', NULL, 'HELHNLN3'),
(669, 15, 'Heures de production NL Niveau 3 HNO', NULL, 'HELHNLN3H'),
(670, 15, 'Heures appels NL', NULL, 'HELHANL'),
(671, 15, 'Heures appels NL HNO', NULL, 'HELHANLH'),
(672, 15, 'Heures mails NL', NULL, 'HELHMNL'),
(673, 15, 'Heures mail NL HNO', NULL, 'HELHMNLH'),
(674, 15, 'Heures chat NL', NULL, 'HELHCNL'),
(675, 15, 'Heures chat NL HNO', NULL, 'HELHCNLH'),
(676, 17, 'Saisies traitées', NULL, 'BPOS'),
(677, 17, 'Images traitées', NULL, 'BPOI'),
(678, 17, 'Matching traités', NULL, 'BPOM'),
(679, 18, 'Heures de production', NULL, 'BPOH'),
(680, 19, 'Forfait mensuel de production', NULL, 'BPOF'),
(681, 20, 'REDACTIONS ARTICLES 250 MOTS', NULL, 'REDA1'),
(682, 20, 'REDACTIONS ARTICLES 500 MOTS', NULL, 'REDA2'),
(683, 20, 'REDACTIONS ARTICLES 700 MOTS', NULL, 'REDA3'),
(684, 20, 'REDACTIONS ARTICLES 1000 MOTS', NULL, 'REDA4'),
(685, 20, 'REDACTIONS ARTICLES 1500 MOTS', NULL, 'REDA5'),
(686, 20, 'REDACTIONS ARTICLES 3500 MOTS', NULL, 'REDA6'),
(687, 20, 'Intégration - modification contenu', NULL, 'REDAIMC'),
(688, 20, 'Retranscription', NULL, 'REDAR'),
(689, 20, 'Relevé de prix', NULL, 'REDARP'),
(690, 20, 'Traitement de visuel', NULL, 'REDATV'),
(691, 20, 'FICHES PRODUIT', NULL, 'REDAFP'),
(692, 21, 'Heures de production Rédaction', NULL, 'REDAH'),
(693, 22, 'Forfait mensuel de production', NULL, 'REDAF'),
(694, 23, 'Heure de production', NULL, 'DIGH'),
(695, 24, 'Forfait mensuel de production', NULL, 'DIGF'),
(696, 25, 'Nombre de Contacts Argumentés', NULL, 'ETUCA'),
(697, 25, 'Nombre de Ventes Nettes ', NULL, 'ETUVN'),
(698, 25, 'Nombre de Ventes Brutes', NULL, 'ETUVB'),
(699, 25, 'Commissions sur Ventes ', NULL, 'ETUCOM'),
(700, 25, 'Nombre de Fiches Qualifiées', NULL, 'ETUFQ'),
(701, 25, 'Nombre de Rendez-vous', NULL, 'ETURDV'),
(702, 25, 'Nombre de Fiches Traitées', NULL, 'ETUFT'),
(703, 25, 'Enquêtes traitées', NULL, 'ETUET'),
(704, 26, 'Forfait mensuel de production', NULL, 'ETUF'),
(705, 27, 'Heures de production ', NULL, 'ETUH'),
(706, 28, 'Nombre enrichissements traités', NULL, 'DATET'),
(707, 28, 'Nombre de qualifications traitées', NULL, 'DATEQT'),
(708, 28, 'Nombre de mise au format', NULL, 'DATMF'),
(709, 28, 'Nombre de signalements traités', NULL, 'DATST'),
(710, 28, 'Nombre de modérations traitées', NULL, 'DATMT'),
(711, 28, 'Nombre de catégorisations traitées', NULL, 'DATCT'),
(712, 29, 'Forfait mensuel de production', NULL, 'DATF'),
(713, 30, 'Heures de production ', NULL, 'DATH'),
(714, 31, 'Appels Traités FR', NULL, 'STIAFR'),
(715, 31, 'Appels Traités FR HNO', NULL, 'STIAFRH'),
(716, 31, 'Emails Traités FR ', NULL, 'STIEFR'),
(717, 31, 'Emails Traités FR  HNO', NULL, 'STIEFRH'),
(718, 31, 'Chats Traités FR', NULL, 'STICFR'),
(719, 31, 'Chats Traités FR HNO', NULL, 'STICFRH'),
(720, 31, 'Appels Traités UK', NULL, 'STIAUK'),
(721, 31, 'Appels Traités UK HNO', NULL, 'STIAUKH'),
(722, 31, 'Emails Traités UK', NULL, 'STIEUK'),
(723, 31, 'Emails Traités UK HNO', NULL, 'STIEUKH'),
(724, 31, 'Chats Traités UK', NULL, 'STICUK'),
(725, 31, 'Chats Traités UK HNO', NULL, 'STICUKH'),
(726, 31, 'Appels Traités IT', NULL, 'STIAIT'),
(727, 31, 'Appels Traités IT HNO', NULL, 'STIAITH'),
(728, 31, 'Emails Traités IT', NULL, 'STIEIT'),
(729, 31, 'Emails Traités IT HNO', NULL, 'STIEITH'),
(730, 31, 'Chats Traités IT', NULL, 'STICIT'),
(731, 31, 'Chats Traités IT HNO', NULL, 'STICITH'),
(732, 31, 'Appels Traités ES', NULL, 'STIAES'),
(733, 31, 'Appels Traités IT HNO', NULL, 'STIAESH'),
(734, 31, 'Emails Traités IT', NULL, 'STIEES'),
(735, 31, 'Emails Traités IT HNO', NULL, 'STIEESH'),
(736, 31, 'Chats Traités IT', NULL, 'STICES'),
(737, 31, 'Chats Traités IT HNO', NULL, 'STICESH'),
(738, 32, 'Heures de production', NULL, 'STIH'),
(739, 33, 'Forfait mensuel de production', NULL, 'STIF'),
(740, 34, 'Nombre de Contacts Argumentés', NULL, 'STOCA'),
(741, 34, 'Nombre de Ventes Nettes ', NULL, 'STOVN'),
(742, 34, 'Nombre de Ventes Brutes', NULL, 'STOVB'),
(743, 34, 'Commissions sur Ventes ', NULL, 'STOCOM'),
(744, 34, 'Nombre de Fiches Qualifiées', NULL, 'STOFQ'),
(745, 34, 'Nombre de Rendez-vous', NULL, 'STORDV'),
(746, 34, 'Nombre de Fiches Traitées', NULL, 'STOFT'),
(747, 34, 'Enquêtes traitées', NULL, 'STOET'),
(748, 35, 'Heures de production', NULL, 'STOH'),
(749, 36, 'Forfait mensuel de production', NULL, 'STOF'),
(750, 37, 'Saisies traitées', NULL, 'STBS'),
(751, 37, 'Images traitées', NULL, 'STBI'),
(752, 37, 'Matching traités', NULL, 'STBM'),
(753, 38, 'Heures de production BPO', NULL, 'STBH'),
(754, 39, 'Forfait mensuel de production', NULL, 'STBF'),
(755, 40, 'REDACTIONS ARTICLES 250 MOTS', NULL, 'STRA1'),
(756, 40, 'REDACTIONS ARTICLES 500 MOTS', NULL, 'STRA2'),
(757, 40, 'REDACTIONS ARTICLES 700 MOTS', NULL, 'STRA3'),
(758, 40, 'REDACTIONS ARTICLES 1000 MOTS', NULL, 'STRA4'),
(759, 40, 'REDACTIONS ARTICLES 1500 MOTS', NULL, 'STRA5'),
(760, 40, 'REDACTIONS ARTICLES 3500 MOTS', NULL, 'STRA6'),
(761, 40, 'Intégration - modification contenu', NULL, 'STRIMC'),
(762, 40, 'Retranscription', NULL, 'STRR'),
(763, 40, 'Relevé de prix', NULL, 'STRRP'),
(764, 40, 'Traitement de visuel', NULL, 'STRTV'),
(765, 40, 'FICHES PRODUIT', NULL, 'STRFP'),
(766, 41, 'Heures de production ', NULL, 'STRH'),
(767, 42, 'Forfait mensuel de production', NULL, 'STRF'),
(774, 44, 'Forfait mensuel de production', NULL, 'STDF'),
(775, 45, 'Heures de production ', NULL, 'STDH'),
(776, 8, 'Appels Traités FR', NULL, 'INAF'),
(777, 8, 'Appels Traités FR HNO', NULL, 'INAFH'),
(778, 8, 'Appels Traités FR Avant Vente', NULL, 'INAFAV'),
(779, 8, 'Appels Traités FR Commandes', NULL, 'INAFC'),
(780, 8, 'Appels Traités FR Après Vente', NULL, 'INAFSAV'),
(781, 8, 'Appels Traités FR Avant Vente HNO', NULL, 'INAFAVH'),
(782, 8, 'Appels Traités FR Commandes HNO', NULL, 'INAFCH'),
(783, 8, 'Appels Traités FR Après Vente HNO', NULL, 'INAFSAVH'),
(784, 8, 'Appels Traités FR Niveau 2 ', NULL, 'INAFN2'),
(785, 8, 'Appels Traités FR Niveau 2  HNO', NULL, 'INAFN2H'),
(786, 8, 'Appels Traités FR Niveau 3 ', NULL, 'INAFN3'),
(787, 8, 'Appels Traités FR Niveau 3  HNO', NULL, 'INAFN3H'),
(788, 8, 'Emails Traités FR ', NULL, 'INEF'),
(789, 8, 'Emails Traités FR  HNO', NULL, 'INEFH'),
(790, 8, 'Emails Traités FR Avant Vente', NULL, 'INEFAV'),
(791, 8, 'Emails Traités FR Commande', NULL, 'INEFC'),
(792, 8, 'Emails Traités FR Aprés Vente', NULL, 'INEFSAV'),
(793, 8, 'Emails Traités FR Avant Vente HNO', NULL, 'INEFAVH'),
(794, 8, 'Emails Traités FR Commande HNO', NULL, 'INEFCH'),
(795, 8, 'Emails Traités FR Après Vente HNO', NULL, 'INEFSAVH'),
(796, 8, 'Emails Traités FR Niveau 2 ', NULL, 'INEFN2'),
(797, 8, 'Emails Traités FR Niveau 2  HNO', NULL, 'INEFN2H'),
(798, 8, 'Emails Traités FR Niveau 3 ', NULL, 'INEFN3'),
(799, 8, 'Emails Traités FR Niveau 3  HNO', NULL, 'INEFN3H'),
(800, 8, 'Chats Traités FR', NULL, 'INCF'),
(801, 8, 'Chats Traités FR HNO', NULL, 'INCFH'),
(802, 8, 'Chats Traités FR Avant Vente', NULL, 'INCFAV'),
(803, 8, 'Chats Traités FR Commande', NULL, 'INCFC'),
(804, 8, 'Chats Traités FR Après Vente', NULL, 'INCFSAV'),
(805, 8, 'Chats Traités FR Avant Vente HNO', NULL, 'INCFAVH'),
(806, 8, 'Chats Traités FR Commande HNO', NULL, 'INCFCH'),
(807, 8, 'Chats Traités FR Après Vente HNO', NULL, 'INCFSAVH'),
(808, 8, 'Chats Traités FR Niveau 2 ', NULL, 'INCFN2'),
(809, 8, 'Chats Traités FR Niveau 2  HNO', NULL, 'INCFN2H'),
(810, 8, 'Chats Traités FR Niveau 3 ', NULL, 'INCFN3'),
(811, 8, 'Chats Traités FR Niveau 3  HNO', NULL, 'INCFN3H'),
(812, 9, 'Heures Produites pour les appels FR', NULL, 'INBHAF'),
(813, 9, 'Heures Produites pour les appels FR HNO', NULL, 'INBHAFH'),
(814, 9, 'Appels Traités FR Niveau 2 ', NULL, 'INBHFN2'),
(815, 9, 'Appels Traités FR Niveau 2  HNO', NULL, 'INBHFN2H'),
(816, 9, 'Appels Traités FR Niveau 3 ', NULL, 'INBHFN3'),
(817, 9, 'Appels Traités FR Niveau 3  HNO', NULL, 'INBHFN3H'),
(818, 9, 'Heures Produites pour les e-mails FR', NULL, 'INBHEF'),
(819, 9, 'Heures Produites pour les e-mails FR HNO', NULL, 'INBHEFH'),
(820, 9, 'Emails Traités FR Niveau 2 ', NULL, 'INBHEFN2'),
(821, 9, 'Emails Traités FR Niveau 2  HNO', NULL, 'INBHEFN2H'),
(822, 9, 'Emails Traités FR Niveau 3 ', NULL, 'INBHEFN3'),
(823, 9, 'Emails Traités FR Niveau 3  HNO', NULL, 'INBHEFN3H'),
(824, 9, 'Heures Produites pour les Chats FR', NULL, 'INBHCF'),
(825, 9, 'Heures Produites pour les Chat FR HNO', NULL, 'INBHCFH'),
(826, 9, 'Chats Traités FR Niveau 2 ', NULL, 'INBHCFN2'),
(827, 9, 'Chats Traités FR Niveau 2  HNO', NULL, 'INBHCFN2H'),
(828, 9, 'Chats Traités FR Niveau 3 ', NULL, 'INBHCFN3'),
(829, 9, 'Chats Traités FR Niveau 3  HNO', NULL, 'INBHCFN3H'),
(830, 10, 'Forfait mensuel de Production FR', NULL, 'INBFOF'),
(831, 11, 'Nombre de Contacts Argumentés FR', NULL, 'OUTCAF'),
(832, 11, 'Nombre de Ventes Nettes FR', NULL, 'OUTVNF'),
(833, 11, 'Nombre de Ventes Brutes FR', NULL, 'OUTVBF'),
(834, 11, 'Commissions sur Ventes FR', NULL, 'OUTCOMF'),
(835, 11, 'Nombre de Fiches Qualifiées FR', NULL, 'OUTFQF'),
(836, 11, 'Nombre de Rendez-vous FR', NULL, 'OUTRDVF'),
(837, 11, 'Nombre de Fiches Traitées FR', NULL, 'OUTFTF'),
(838, 12, 'Heures de Production FR', NULL, 'OUTHF'),
(839, 13, 'Forfait mensuel de Production FR', NULL, 'OUTFOF'),
(840, 9, 'Heures Produites pour les appels UK', NULL, 'INBHAUK'),
(841, 9, 'Heures Produites pour les appels UK HNO', NULL, 'INBHAUKH'),
(842, 9, 'Appels Traités UK Niveau 2 ', NULL, 'INBHUKN2'),
(843, 9, 'Appels Traités UK Niveau 2  HNO', NULL, 'INBHUKN2H'),
(844, 9, 'Appels Traités UK Niveau 3 ', NULL, 'INBHUKN3'),
(845, 9, 'Appels Traités UK Niveau 3  HNO', NULL, 'INBHUKN3H'),
(846, 9, 'Heures Produites pour les e-mails UK', NULL, 'INBHEUK'),
(847, 9, 'Heures Produites pour les e-mails UK HNO', NULL, 'INBHEUKH'),
(848, 9, 'Emails Traités UK Niveau 2 ', NULL, 'INBHEUKN2'),
(849, 9, 'Emails Traités UK Niveau 2  HNO', NULL, 'INBHEUKN2H'),
(850, 9, 'Emails Traités UK Niveau 3 ', NULL, 'INBHEUKN3'),
(851, 9, 'Emails Traités UK Niveau 3  HNO', NULL, 'INBHEUKN3H'),
(852, 9, 'Heures Produites pour les Chats UK', NULL, 'INBHCUK'),
(853, 9, 'Heures Produites pour les Chat UK HNO', NULL, 'INBHCUKH'),
(854, 9, 'Chats Traités UK Niveau 2 ', NULL, 'INBHCUKN2'),
(855, 9, 'Chats Traités UK Niveau 2  HNO', NULL, 'INBHCUKN2H'),
(856, 9, 'Chats Traités UK Niveau 3 ', NULL, 'INBHCUKN3'),
(857, 9, 'Chats Traités UK Niveau 3  HNO', NULL, 'INBHCUKN3H'),
(858, 9, 'Heures Produites pour les appels IT', NULL, 'INBHAIT'),
(859, 9, 'Heures Produites pour les appels IT HNO', NULL, 'INBHAITH'),
(860, 9, 'Appels Traités IT Niveau 2 ', NULL, 'INBHITN2'),
(861, 9, 'Appels Traités IT Niveau 2  HNO', NULL, 'INBHITN2H'),
(862, 9, 'Appels Traités IT Niveau 3 ', NULL, 'INBHITN3'),
(863, 9, 'Appels Traités IT Niveau 3  HNO', NULL, 'INBHITN3H'),
(864, 9, 'Heures Produites pour les e-mails IT', NULL, 'INBHEIT'),
(865, 9, 'Heures Produites pour les e-mails IT HNO', NULL, 'INBHEITH'),
(866, 9, 'Emails Traités IT Niveau 2 ', NULL, 'INBHEITN2'),
(867, 9, 'Emails Traités IT Niveau 2  HNO', NULL, 'INBHEITN2H'),
(868, 9, 'Emails Traités IT Niveau 3 ', NULL, 'INBHEITN3'),
(869, 9, 'Emails Traités IT Niveau 3  HNO', NULL, 'INBHEITN3H'),
(870, 9, 'Heures Produites pour les Chats IT', NULL, 'INBHCIT'),
(871, 9, 'Heures Produites pour les Chat IT HNO', NULL, 'INBHCITH'),
(872, 9, 'Chats Traités IT Niveau 2 ', NULL, 'INBHCITN2'),
(873, 9, 'Chats Traités IT Niveau 2  HNO', NULL, 'INBHCITN2H'),
(874, 9, 'Chats Traités IT Niveau 3 ', NULL, 'INBHCITN3'),
(875, 9, 'Chats Traités IT Niveau 3  HNO', NULL, 'INBHCITN3H'),
(876, 9, 'Heures Produites pour les appels ES', NULL, 'INBHAES'),
(877, 9, 'Heures Produites pour les appels ES HNO', NULL, 'INBHAESH'),
(878, 9, 'Appels Traités ES Niveau 2 ', NULL, 'INBHESN2'),
(879, 9, 'Appels Traités ES Niveau 2  HNO', NULL, 'INBHESN2H'),
(880, 9, 'Appels Traités ES Niveau 3 ', NULL, 'INBHESN3'),
(881, 9, 'Appels Traités ES Niveau 3  HNO', NULL, 'INBHESN3H'),
(882, 9, 'Heures Produites pour les e-mails ES', NULL, 'INBHEES'),
(883, 9, 'Heures Produites pour les e-mails ES HNO', NULL, 'INBHEESH'),
(884, 9, 'Emails Traités ES Niveau 2 ', NULL, 'INBHEESN2'),
(885, 9, 'Emails Traités ES Niveau 2  HNO', NULL, 'INBHEESN2H'),
(886, 9, 'Emails Traités ES Niveau 3 ', NULL, 'INBHEESN3'),
(887, 9, 'Emails Traités ES Niveau 3  HNO', NULL, 'INBHEESN3H'),
(888, 9, 'Heures Produites pour les Chats ES', NULL, 'INBHCES'),
(889, 9, 'Heures Produites pour les Chat ES HNO', NULL, 'INBHCESH'),
(890, 9, 'Chats Traités ES Niveau 2 ', NULL, 'INBHCESN2'),
(891, 9, 'Chats Traités ES Niveau 2  HNO', NULL, 'INBHCESN2H'),
(892, 9, 'Chats Traités ES Niveau 3 ', NULL, 'INBHCESN3'),
(893, 9, 'Chats Traités ES Niveau 3  HNO', NULL, 'INBHCESN3H'),
(894, 9, 'Heures Produites pour les appels US', NULL, 'INBHAUS'),
(895, 9, 'Heures Produites pour les appels US HNO', NULL, 'INBHAUSH'),
(896, 9, 'Appels Traités US Niveau 2 ', NULL, 'INBHUSN2'),
(897, 9, 'Appels Traités US Niveau 2  HNO', NULL, 'INBHUSN2H'),
(898, 9, 'Appels Traités US Niveau 3 ', NULL, 'INBHUSN3'),
(899, 9, 'Appels Traités US Niveau 3  HNO', NULL, 'INBHUSN3H'),
(900, 9, 'Heures Produites pour les e-mails US', NULL, 'INBHEUS');
INSERT INTO `operation` (`id`, `famille_operation_id`, `libelle`, `description`, `reference_article`) VALUES
(901, 9, 'Heures Produites pour les e-mails US HNO', NULL, 'INBHEUSH'),
(902, 9, 'Emails Traités US Niveau 2 ', NULL, 'INBHEUSN2'),
(903, 9, 'Emails Traités US Niveau 2  HNO', NULL, 'INBHEUSN2H'),
(904, 9, 'Emails Traités US Niveau 3 ', NULL, 'INBHEUSN3'),
(905, 9, 'Emails Traités US Niveau 3  HNO', NULL, 'INBHEUSN3H'),
(906, 9, 'Heures Produites pour les Chats US', NULL, 'INBHCUS'),
(907, 9, 'Heures Produites pour les Chat US HNO', NULL, 'INBHCUSH'),
(908, 9, 'Chats Traités US Niveau 2 ', NULL, 'INBHCUSN2'),
(909, 9, 'Chats Traités US Niveau 2  HNO', NULL, 'INBHCUSN2H'),
(910, 9, 'Chats Traités US Niveau 3 ', NULL, 'INBHCUSN3'),
(911, 9, 'Chats Traités US Niveau 3  HNO', NULL, 'INBHCUSN3H'),
(912, 8, 'Emails Traités DE Niveau 2  HNO', NULL, 'INEDEN2H'),
(913, 8, 'Emails Traités DE Niveau 3  HNO', NULL, 'INEDEN3H'),
(914, 9, 'Heures Produites pour les appels DE', NULL, 'INBHADE'),
(915, 9, 'Heures Produites pour les appels DE HNO', NULL, 'INBHADEH'),
(916, 9, 'Appels Traités DE Niveau 2 ', NULL, 'INBHDEN2'),
(917, 9, 'Appels Traités DE Niveau 2  HNO', NULL, 'INBHDEN2H'),
(918, 9, 'Appels Traités DE Niveau 3 ', NULL, 'INBHDEN3'),
(919, 9, 'Appels Traités DE Niveau 3  HNO', NULL, 'INBHDEN3H'),
(920, 9, 'Heures Produites pour les e-mails DE', NULL, 'INBHEDE'),
(921, 9, 'Heures Produites pour les e-mails DE HNO', NULL, 'INBHEDEH'),
(922, 9, 'Emails Traités DE Niveau 2 ', NULL, 'INBHEDEN2'),
(923, 9, 'Emails Traités DE Niveau 2  HNO', NULL, 'INBHEDEN2H'),
(924, 9, 'Emails Traités DE Niveau 3 ', NULL, 'INBHEDEN3'),
(925, 9, 'Emails Traités DE Niveau 3  HNO', NULL, 'INBHEDEN3H'),
(926, 9, 'Heures Produites pour les Chats DE', NULL, 'INBHCDE'),
(927, 9, 'Heures Produites pour les Chat DE HNO', NULL, 'INBHCDEH'),
(928, 9, 'Chats Traités DE Niveau 2 ', NULL, 'INBHCDEN2'),
(929, 9, 'Chats Traités DE Niveau 2  HNO', NULL, 'INBHCDEN2H'),
(930, 9, 'Chats Traités DE Niveau 3 ', NULL, 'INBHCDEN3'),
(931, 9, 'Chats Traités DE Niveau 3  HNO', NULL, 'INBHCDEN3H'),
(932, 9, 'Heures Produites pour les appels NL', NULL, 'INBHANL'),
(933, 9, 'Heures Produites pour les appels NL HNO', NULL, 'INBHANLH'),
(934, 9, 'Appels Traités NL Niveau 2 ', NULL, 'INBHNLN2'),
(935, 9, 'Appels Traités NL Niveau 2  HNO', NULL, 'INBHNLN2H'),
(936, 9, 'Appels Traités NL Niveau 3 ', NULL, 'INBHNLN3'),
(937, 9, 'Appels Traités NL Niveau 3  HNO', NULL, 'INBHNLN3H'),
(938, 9, 'Heures Produites pour les e-mails NL', NULL, 'INBHENL'),
(939, 9, 'Heures Produites pour les e-mails NL HNO', NULL, 'INBHENLH'),
(940, 9, 'Emails Traités NL Niveau 2 ', NULL, 'INBHENLN2'),
(941, 9, 'Emails Traités NL Niveau 2  HNO', NULL, 'INBHENLN2H'),
(942, 9, 'Emails Traités NL Niveau 3 ', NULL, 'INBHENLN3'),
(943, 9, 'Emails Traités NL Niveau 3  HNO', NULL, 'INBHENLN3H'),
(944, 9, 'Heures Produites pour les Chats NL', NULL, 'INBHCNL'),
(945, 9, 'Heures Produites pour les Chat NL HNO', NULL, 'INBHCNLH'),
(946, 9, 'Chats Traités NL Niveau 2 ', NULL, 'INBHCNLN2'),
(947, 9, 'Chats Traités NL Niveau 2  HNO', NULL, 'INBHCNLN2H'),
(948, 9, 'Chats Traités NL Niveau 3 ', NULL, 'INBHCNLN3'),
(949, 9, 'Chats Traités NL Niveau 3  HNO', NULL, 'INBHCNLN3H'),
(950, 9, 'Heures Produites pour les appels PT', NULL, 'INBHAPT'),
(951, 9, 'Heures Produites pour les appels PT HNO', NULL, 'INBHAPTH'),
(952, 9, 'Appels Traités PT Niveau 2 ', NULL, 'INBHPTN2'),
(953, 9, 'Appels Traités PT Niveau 2  HNO', NULL, 'INBHPTN2H'),
(954, 9, 'Appels Traités PT Niveau 3 ', NULL, 'INBHPTN3'),
(955, 9, 'Appels Traités PT Niveau 3  HNO', NULL, 'INBHPTN3H'),
(956, 9, 'Heures Produites pour les e-mails PT', NULL, 'INBHEPT'),
(957, 9, 'Heures Produites pour les e-mails PT HNO', NULL, 'INBHEPTH'),
(958, 9, 'Emails Traités PT Niveau 2 ', NULL, 'INBHEPTN2'),
(959, 9, 'Emails Traités PT Niveau 2  HNO', NULL, 'INBHEPTN2H'),
(960, 9, 'Emails Traités PT Niveau 3 ', NULL, 'INBHEPTN3'),
(961, 9, 'Emails Traités PT Niveau 3  HNO', NULL, 'INBHEPTN3H'),
(962, 9, 'Heures Produites pour les Chats PT', NULL, 'INBHCPT'),
(963, 9, 'Heures Produites pour les Chat PT HNO', NULL, 'INBHCPTH'),
(964, 9, 'Chats Traités PT Niveau 2 ', NULL, 'INBHCPTN2'),
(965, 9, 'Chats Traités PT Niveau 2  HNO', NULL, 'INBHCPTN2H'),
(966, 9, 'Chats Traités PT Niveau 3 ', NULL, 'INBHCPTN3'),
(967, 9, 'Chats Traités PT Niveau 3  HNO', NULL, 'INBHCPTN3H'),
(968, 9, 'Heures Produites pour les appels BR', NULL, 'INBHABR'),
(969, 9, 'Heures Produites pour les appels BR HNO', NULL, 'INBHABRH'),
(970, 9, 'Appels Traités BR Niveau 2 ', NULL, 'INBHBRN2'),
(971, 9, 'Appels Traités BR Niveau 2  HNO', NULL, 'INBHBRN2H'),
(972, 9, 'Appels Traités BR Niveau 3 ', NULL, 'INBHBRN3'),
(973, 9, 'Appels Traités BR Niveau 3  HNO', NULL, 'INBHBRN3H'),
(974, 9, 'Heures Produites pour les e-mails BR', NULL, 'INBHEBR'),
(975, 9, 'Heures Produites pour les e-mails BR HNO', NULL, 'INBHEBRH'),
(976, 9, 'Emails Traités BR Niveau 2 ', NULL, 'INBHEBRN2'),
(977, 9, 'Emails Traités BR Niveau 2  HNO', NULL, 'INBHEBRN2H'),
(978, 9, 'Emails Traités BR Niveau 3 ', NULL, 'INBHEBRN3'),
(979, 9, 'Emails Traités BR Niveau 3  HNO', NULL, 'INBHEBRN3H'),
(980, 9, 'Heures Produites pour les Chats BR', NULL, 'INBHCBR'),
(981, 9, 'Heures Produites pour les Chat BR HNO', NULL, 'INBHCBRH'),
(982, 9, 'Chats Traités BR Niveau 2 ', NULL, 'INBHCBRN2'),
(983, 9, 'Chats Traités BR Niveau 2  HNO', NULL, 'INBHCBRN2H'),
(984, 9, 'Chats Traités BR Niveau 3 ', NULL, 'INBHCBRN3'),
(985, 9, 'Chats Traités BR Niveau 3  HNO', NULL, 'INBHCBRN3H'),
(986, 9, 'Heures Produites pour les appels AR', NULL, 'INBHAAR'),
(987, 9, 'Heures Produites pour les appels AR HNO', NULL, 'INBHAARH'),
(988, 9, 'Appels Traités AR Niveau 2 ', NULL, 'INBHARN2'),
(989, 9, 'Appels Traités AR Niveau 2  HNO', NULL, 'INBHARN2H'),
(990, 9, 'Appels Traités AR Niveau 3 ', NULL, 'INBHARN3'),
(991, 9, 'Appels Traités AR Niveau 3  HNO', NULL, 'INBHARN3H'),
(992, 9, 'Heures Produites pour les e-mails AR', NULL, 'INBHEAR'),
(993, 9, 'Heures Produites pour les e-mails AR HNO', NULL, 'INBHEARH'),
(994, 9, 'Emails Traités AR Niveau 2 ', NULL, 'INBHEARN2'),
(995, 9, 'Emails Traités AR Niveau 2  HNO', NULL, 'INBHEARN2H'),
(996, 9, 'Emails Traités AR Niveau 3 ', NULL, 'INBHEARN3'),
(997, 9, 'Emails Traités AR Niveau 3  HNO', NULL, 'INBHEARN3H'),
(998, 9, 'Heures Produites pour les Chats AR', NULL, 'INBHCAR'),
(999, 9, 'Heures Produites pour les Chat AR HNO', NULL, 'INBHCARH'),
(1000, 9, 'Chats Traités AR Niveau 2 ', NULL, 'INBHCARN2'),
(1001, 9, 'Chats Traités AR Niveau 2  HNO', NULL, 'INBHCARN2H'),
(1002, 9, 'Chats Traités AR Niveau 3 ', NULL, 'INBHCARN3'),
(1003, 9, 'Chats Traités AR Niveau 3  HNO', NULL, 'INBHCARN3H'),
(1004, 63, 'REGULE', NULL, 'REG'),
(1005, 64, 'DEVELOPPEMENT', NULL, 'DEV'),
(1006, 65, 'CONSEIL/ACCOMPAGNEMENT', NULL, 'COAC'),
(1007, 66, 'Bonus & Malus', NULL, 'MALBON');

-- --------------------------------------------------------

--
-- Structure de la table `origin_lead`
--

DROP TABLE IF EXISTS `origin_lead`;
CREATE TABLE IF NOT EXISTS `origin_lead` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `origin_lead`
--

INSERT INTO `origin_lead` (`id`, `libelle`) VALUES
(1, 'Ancien client'),
(2, 'Appel entrant'),
(3, 'Apporteur d\'affaires'),
(4, 'BIG BOSS'),
(5, 'Base Salesforce'),
(6, 'Campagne de Prospection'),
(7, 'Campagne de Prospection BIG BOSS'),
(8, 'Campagne de Prospection CCI Chine'),
(9, 'Campagne de Prospection Mada'),
(10, 'Contact client'),
(11, 'Diagnostique'),
(12, 'Email entrant'),
(13, 'Formulaire en ligne'),
(14, 'Prospection'),
(15, 'Réseau'),
(16, 'Salon');

-- --------------------------------------------------------

--
-- Structure de la table `pays_facturation`
--

DROP TABLE IF EXISTS `pays_facturation`;
CREATE TABLE IF NOT EXISTS `pays_facturation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pays_facturation`
--

INSERT INTO `pays_facturation` (`id`, `libelle`) VALUES
(1, 'France'),
(2, 'Maroc'),
(3, 'Madagascar'),
(4, 'Niger');

-- --------------------------------------------------------

--
-- Structure de la table `pays_production`
--

DROP TABLE IF EXISTS `pays_production`;
CREATE TABLE IF NOT EXISTS `pays_production` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `pays_production`
--

INSERT INTO `pays_production` (`id`, `libelle`) VALUES
(1, 'France'),
(2, 'Maroc'),
(3, 'Madagascar'),
(4, 'Niger'),
(5, 'Sous-traitants');

-- --------------------------------------------------------

--
-- Structure de la table `potentiel_transformation`
--

DROP TABLE IF EXISTS `potentiel_transformation`;
CREATE TABLE IF NOT EXISTS `potentiel_transformation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=10 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `potentiel_transformation`
--

INSERT INTO `potentiel_transformation` (`id`, `libelle`) VALUES
(1, '10% (Pas de devis envoyé)'),
(2, '20% (Devis sans échanges)'),
(3, '30% (Devis avec quelques échanges)'),
(4, '40% (Bon feedback)'),
(5, '50% (Shortlist à 2)'),
(6, '70% (Négociation avancée)'),
(7, '90% (Ok de principe)'),
(9, '100% (BDC signé et validé)');

-- --------------------------------------------------------

--
-- Structure de la table `profil_contact`
--

DROP TABLE IF EXISTS `profil_contact`;
CREATE TABLE IF NOT EXISTS `profil_contact` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `profil_contact`
--

INSERT INTO `profil_contact` (`id`, `libelle`) VALUES
(1, 'Direction'),
(2, 'Finance'),
(3, 'Opérationnel'),
(4, 'Signataire');

-- --------------------------------------------------------

--
-- Structure de la table `reject_bdc`
--

DROP TABLE IF EXISTS `reject_bdc`;
CREATE TABLE IF NOT EXISTS `reject_bdc` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bdc_id` int(11) NOT NULL,
  `comment` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_1042830928DF9AB0` (`bdc_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `resume_lead`
--

DROP TABLE IF EXISTS `resume_lead`;
CREATE TABLE IF NOT EXISTS `resume_lead` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `origin_lead_id` int(11) NOT NULL,
  `duree_trt_id` int(11) NOT NULL,
  `potentiel_transformation_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `date_debut` date NOT NULL,
  `type_offre` varchar(54) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `resume_prestation` longtext COLLATE utf8mb4_unicode_ci,
  `potentiel_ca` decimal(10,2) DEFAULT NULL,
  `sep_contact_client` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `niveau_urgence` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
  `delai_remise_offre` date DEFAULT NULL,
  `date_demarrage` date DEFAULT NULL,
  `is_formation_facturable` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_outil_fournis` varchar(50) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `percision_client` longtext COLLATE utf8mb4_unicode_ci,
  `point_vigilance` longtext COLLATE utf8mb4_unicode_ci,
  `pieces_jointes` longtext COLLATE utf8mb4_unicode_ci COMMENT '(DC2Type:json)',
  `interlocuteur` longtext COLLATE utf8mb4_unicode_ci COMMENT '(DC2Type:array)',
  PRIMARY KEY (`id`),
  KEY `IDX_D657A00FB52250E0` (`origin_lead_id`),
  KEY `IDX_D657A00F18D61EF7` (`duree_trt_id`),
  KEY `IDX_D657A00FB0738B00` (`potentiel_transformation_id`),
  KEY `IDX_D657A00F9395C3F3` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=12 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `resume_lead`
--

INSERT INTO `resume_lead` (`id`, `origin_lead_id`, `duree_trt_id`, `potentiel_transformation_id`, `customer_id`, `date_debut`, `type_offre`, `resume_prestation`, `potentiel_ca`, `sep_contact_client`, `niveau_urgence`, `delai_remise_offre`, `date_demarrage`, `is_formation_facturable`, `is_outil_fournis`, `percision_client`, `point_vigilance`, `pieces_jointes`, `interlocuteur`) VALUES
(1, 9, 2, 4, 1, '2023-01-26', 'BDC + offre commerciale', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '48.00', NULL, 'Normal', '2023-10-27', '2023-01-30', NULL, 'Outil client et outsourcia', NULL, NULL, '[]', 'a:1:{i:0;i:1;}'),
(2, 4, 2, 7, 1, '2023-02-17', 'BDC simple', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry', '17.00', NULL, 'Normal', '2023-10-26', '2023-02-17', NULL, 'Outil client et outsourcia', NULL, NULL, '[]', 'a:1:{i:0;i:1;}'),
(3, 2, 2, 2, 1, '2023-02-24', 'BDC + offre commerciale', NULL, '29.00', NULL, 'Normal', NULL, NULL, NULL, 'Outil outsourcia', NULL, NULL, '[]', 'a:1:{i:0;i:1;}'),
(4, 15, 1, 9, 1, '2023-03-07', 'BDC + offre commerciale', NULL, '64.00', NULL, 'Normal', NULL, NULL, NULL, 'Outil client et outsourcia', NULL, NULL, '[]', 'a:1:{i:0;i:1;}'),
(5, 5, 2, 1, 2, '2023-03-07', 'BDC + offre commerciale', NULL, '46.00', NULL, 'Normal', NULL, NULL, NULL, 'Outil client et outsourcia', NULL, NULL, '[]', 'a:1:{i:0;i:2;}'),
(6, 6, 2, 3, 2, '2023-03-08', 'BDC + offre commerciale', NULL, '23.00', NULL, 'Normal', NULL, NULL, NULL, 'Outil client et outsourcia', NULL, NULL, '[]', 'a:1:{i:0;i:2;}'),
(7, 6, 1, 2, 2, '2023-03-08', 'BDC + offre commerciale', NULL, '26.00', NULL, 'Normal', NULL, NULL, NULL, 'Outil outsourcia', NULL, NULL, '[]', 'a:1:{i:0;i:2;}'),
(8, 6, 2, 3, 2, '2023-03-08', 'BDC + offre commerciale', NULL, '31.00', NULL, 'Normal', NULL, NULL, NULL, 'Outil client et outsourcia', NULL, NULL, '[]', 'a:1:{i:0;i:2;}'),
(9, 5, 2, 1, 2, '2023-03-07', 'BDC + offre commerciale', NULL, '46.00', NULL, 'Normal', NULL, NULL, NULL, 'Outil client et outsourcia', NULL, NULL, '[]', 'a:1:{i:0;i:2;}'),
(10, 5, 2, 1, 2, '2023-03-07', 'BDC + offre commerciale', NULL, '46.00', NULL, 'Normal', NULL, NULL, NULL, 'Outil client et outsourcia', NULL, NULL, '[]', 'a:1:{i:0;i:2;}'),
(11, 3, 2, 4, 2, '2023-03-08', 'BDC + offre commerciale', 'Lorem Ipsum is simply dummy text of the printing and typesetting industry.', '31.00', NULL, 'Normal', NULL, NULL, NULL, 'Outil client et outsourcia', NULL, NULL, '[]', 'a:1:{i:0;i:2;}');

-- --------------------------------------------------------

--
-- Structure de la table `societe_facturation`
--

DROP TABLE IF EXISTS `societe_facturation`;
CREATE TABLE IF NOT EXISTS `societe_facturation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pays_facturation_id` int(11) NOT NULL,
  `libelle` varchar(45) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activite` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `forme_juridique` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `capital` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `adresse` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `code_postal` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `ville` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `registre_commerce` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `identifiant_fiscal` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_774486CB899CF741` (`pays_facturation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=17 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `societe_facturation`
--

INSERT INTO `societe_facturation` (`id`, `pays_facturation_id`, `libelle`, `activite`, `forme_juridique`, `capital`, `adresse`, `code_postal`, `ville`, `registre_commerce`, `identifiant_fiscal`) VALUES
(1, 1, 'Outsourcia France', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(2, 3, 'Value Data Service', 'Toutes prestations intellectuelles, commerciales et informatiques se rapportant au traitement des données et informations.', 'SRL U', '875 020 000 MGA', 'Zone Industrielle Forello Enceinte SOMALCO Tanjombato', '102', 'Antananarivo Atsimondrano', '2009 B 00245', '5000277574'),
(3, 2, 'Outsourcia Maroc', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(4, 4, 'Outsourcia Niger', 'CENTRE DE CONTACT CLIENT ', 'SARL', '20 000 000  FCFA', '2145  AVENUE  DU NIGER  ---- NIAMEY NIGER ', '2145', 'NIAMEY', 'RCCM : NI-NIA-2015-B-2716 ', '35818S'),
(6, 1, 'ASSISTANCE COMMUNICATION', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(7, 1, 'AS-COM CENTRE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(8, 1, 'SCEMI', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(9, 1, 'STEFI INFORMATIQUE', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(10, 3, 'Value IT', 'Tout traitement sur back office, gestion de service client et développement', 'SARL', '2 000 000 MGA', 'Enceinte SOMALCO, ZI FORELLO Tanjombato', '102', 'Antananarivo Atsimondrano', '2012 B 00369', '3000861053'),
(11, 3, 'Madacontact', 'Toutes prestations de services de relations clients à distance et de centre d\'appels', 'SRL U', '2 000 000 MGA', 'Enceinte SOMALCO ZI FORELLO Tanjombato', '102', 'Antananarivo Atsimondrano', '2011 B 00729', '2000622210'),
(12, 3, 'Alias Community', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(13, 1, 'SIMPLIFY ', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(14, 2, 'CONTACT SYSTEM', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(15, 2, 'OFF SHORE ACADEMY', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL),
(16, 2, 'NEXT CONTACT', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Structure de la table `state`
--

DROP TABLE IF EXISTS `state`;
CREATE TABLE IF NOT EXISTS `state` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `status_lead`
--

DROP TABLE IF EXISTS `status_lead`;
CREATE TABLE IF NOT EXISTS `status_lead` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `customer_id` int(11) NOT NULL,
  `status` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_1097CC5F9395C3F3` (`customer_id`)
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `status_lead`
--

INSERT INTO `status_lead` (`id`, `customer_id`, `status`) VALUES
(1, 1, -1),
(2, 2, -1),
(3, 3, 20),
(4, 4, 20),
(5, 5, 20),
(6, 6, 20),
(7, 7, 20),
(8, 8, 20),
(9, 9, 20),
(10, 10, 20),
(11, 11, 20),
(12, 12, 11),
(13, 13, 11),
(14, 14, -1),
(15, 15, -1),
(16, 16, 20),
(17, 17, 11),
(18, 18, 11),
(19, 19, 11),
(20, 20, 17),
(21, 21, 1),
(22, 22, 1),
(23, 1780, -1);

-- --------------------------------------------------------

--
-- Structure de la table `statut_client`
--

DROP TABLE IF EXISTS `statut_client`;
CREATE TABLE IF NOT EXISTS `statut_client` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Structure de la table `suite_process`
--

DROP TABLE IF EXISTS `suite_process`;
CREATE TABLE IF NOT EXISTS `suite_process` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bdc_id` int(11) NOT NULL,
  `is_customer_will_send_bdc` smallint(6) NOT NULL,
  `is_seizure_contract` smallint(6) NOT NULL,
  `is_devis_pass_to_prod_after_sign` smallint(6) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_E11BCF1A28DF9AB0` (`bdc_id`)
) ENGINE=InnoDB AUTO_INCREMENT=30 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `suite_process`
--

INSERT INTO `suite_process` (`id`, `bdc_id`, `is_customer_will_send_bdc`, `is_seizure_contract`, `is_devis_pass_to_prod_after_sign`) VALUES
(1, 5, 0, 0, 0),
(2, 6, 1, 1, 1),
(3, 7, 1, 1, 1),
(4, 11, 1, 1, 1),
(5, 13, 1, 1, 1),
(6, 15, 0, 0, 0),
(7, 16, 0, 0, 0),
(13, 17, 0, 0, 0),
(14, 18, 1, 1, 1),
(15, 19, 0, 0, 0),
(16, 23, 1, 1, 1),
(17, 24, 1, 1, 1),
(18, 25, 1, 1, 1),
(19, 28, 1, 1, 1),
(20, 31, 1, 1, 1),
(21, 34, 1, 1, 1),
(22, 36, 1, 1, 1),
(23, 38, 1, 1, 1),
(24, 39, 1, 1, 1),
(25, 40, 1, 1, 1),
(26, 41, 1, 1, 1),
(27, 43, 1, 1, 1),
(28, 45, 1, 1, 1),
(29, 46, 1, 1, 1);

-- --------------------------------------------------------

--
-- Structure de la table `tarif`
--

DROP TABLE IF EXISTS `tarif`;
CREATE TABLE IF NOT EXISTS `tarif` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `bu_id` int(11) NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `type_facturation_id` int(11) NOT NULL,
  `pays_production_id` int(11) NOT NULL,
  `operation_id` int(11) NOT NULL,
  `langue_traitement_id` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_E7189C9E0319FBC` (`bu_id`),
  KEY `IDX_E7189C98D06DB10` (`type_facturation_id`),
  KEY `IDX_E7189C9DD21E7CC` (`pays_production_id`),
  KEY `IDX_E7189C944AC3583` (`operation_id`),
  KEY `IDX_E7189C995D655BB` (`langue_traitement_id`)
) ENGINE=InnoDB AUTO_INCREMENT=934 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tarif`
--

INSERT INTO `tarif` (`id`, `bu_id`, `date_debut`, `date_fin`, `type_facturation_id`, `pays_production_id`, `operation_id`, `langue_traitement_id`) VALUES
(1, 1, '2021-11-16', NULL, 1, 2, 9, 1),
(2, 2, '2021-11-16', NULL, 1, 2, 15, 1),
(3, 2, '2021-11-17', NULL, 1, 3, 18, 1),
(4, 1, '2021-11-17', NULL, 1, 2, 19, 1),
(5, 2, '2021-11-17', NULL, 1, 3, 18, 1),
(6, 1, '2021-11-17', NULL, 1, 2, 19, 1),
(7, 2, '2021-11-18', NULL, 1, 3, 16, 1),
(8, 1, '2021-11-18', NULL, 1, 2, 14, 1),
(9, 2, '2021-11-18', NULL, 1, 3, 16, 1),
(10, 1, '2021-11-18', NULL, 1, 2, 14, 1),
(11, 2, '2021-11-19', NULL, 1, 3, 18, 1),
(12, 4, '2021-11-19', NULL, 1, 2, 16, 2),
(13, 2, '2021-11-19', NULL, 1, 3, 18, 1),
(14, 4, '2021-11-19', NULL, 1, 2, 16, 2),
(15, 2, '2021-11-22', NULL, 1, 3, 19, 1),
(16, 1, '2021-11-22', NULL, 1, 3, 17, 1),
(17, 4, '2021-11-22', NULL, 1, 2, 18, 1),
(18, 2, '2021-11-22', NULL, 1, 3, 19, 1),
(19, 4, '2021-11-22', NULL, 1, 2, 18, 1),
(20, 1, '2021-11-23', NULL, 1, 1, 17, 1),
(21, 2, '2021-11-29', NULL, 1, 3, 15, 1),
(22, 1, '2021-11-29', NULL, 1, 3, 17, 1),
(23, 2, '2021-11-29', NULL, 1, 2, 18, 1),
(24, 2, '2021-11-29', NULL, 1, 3, 15, 1),
(25, 2, '2021-11-29', NULL, 1, 2, 18, 1),
(26, 4, '2021-11-29', NULL, 1, 3, 8, 1),
(27, 4, '2021-12-03', NULL, 1, 3, 22, 1),
(28, 1, '2021-12-03', NULL, 1, 2, 18, 1),
(29, 4, '2021-12-03', NULL, 1, 3, 22, 1),
(30, 1, '2021-12-03', NULL, 1, 2, 18, 1),
(31, 2, '2021-12-03', NULL, 1, 3, 15, 1),
(32, 2, '2021-12-07', NULL, 1, 3, 15, 1),
(33, 1, '2021-12-07', NULL, 1, 3, 18, 1),
(34, 4, '2021-12-07', NULL, 1, 2, 22, 1),
(35, 2, '2021-12-07', NULL, 1, 3, 15, 1),
(36, 4, '2021-12-07', NULL, 1, 2, 22, 1),
(37, 2, '2021-12-08', NULL, 1, 2, 15, 1),
(38, 1, '2021-12-08', NULL, 1, 3, 17, 10),
(39, 2, '2021-12-08', NULL, 2, 3, 15, 9),
(40, 6, '2021-12-08', NULL, 3, 1, 21, 1),
(41, 1, '2021-12-08', NULL, 1, 3, 17, 10),
(42, 6, '2021-12-08', NULL, 3, 1, 21, 1),
(43, 4, '2021-12-10', NULL, 1, 3, 21, 1),
(44, 2, '2021-12-10', NULL, 1, 3, 15, 1),
(45, 1, '2021-12-10', NULL, 1, 2, 18, 1),
(46, 4, '2021-12-10', NULL, 1, 3, 21, 1),
(47, 1, '2021-12-10', NULL, 1, 2, 18, 1),
(48, 2, '2021-12-10', NULL, 1, 2, 15, 1),
(49, 1, '2021-12-10', NULL, 1, 2, 18, 1),
(50, 2, '2021-12-10', NULL, 1, 2, 15, 1),
(51, 7, '2021-12-14', NULL, 4, 3, 4, 4),
(52, 6, '2021-12-14', NULL, 1, 3, 14, 8),
(53, 7, '2021-12-14', NULL, 4, 1, 9, 10),
(54, 7, '2021-12-14', NULL, 4, 3, 4, 4),
(55, 7, '2021-12-14', NULL, 4, 1, 9, 10),
(56, 4, '2021-12-27', NULL, 1, 3, 21, 1),
(57, 4, '2021-12-27', NULL, 1, 3, 21, 1),
(58, 2, '2021-12-28', NULL, 1, 1, 15, 1),
(59, 6, '2021-12-28', NULL, 1, 4, 40, 10),
(60, 5, '2021-12-29', NULL, 1, 1, 16, 9),
(61, 6, '2021-12-29', NULL, 3, 3, 10, 8),
(62, 7, '2021-12-29', NULL, 4, 4, 21, 9),
(63, 3, '2021-12-29', NULL, 1, 1, 16, 6),
(64, 3, '2021-12-29', NULL, 3, 2, 16, 5),
(65, 8, '2021-12-29', NULL, 2, 4, 14, 8),
(66, 6, '2021-12-29', NULL, 4, 3, 18, 8),
(67, 6, '2021-12-29', NULL, 4, 4, 17, 8),
(68, 4, '2021-12-29', NULL, 4, 2, 2, 8),
(69, 6, '2021-12-29', NULL, 1, 1, 18, 7),
(70, 4, '2021-12-29', NULL, 2, 2, 17, 4),
(71, 8, '2021-12-29', NULL, 3, 3, 14, 10),
(72, 4, '2021-12-29', NULL, 1, 3, 21, 1),
(73, 4, '2021-12-29', NULL, 1, 3, 21, 1),
(74, 1, '2021-12-29', NULL, 1, 2, 18, 1),
(75, 2, '2021-12-30', NULL, 1, 2, 17, 1),
(76, 4, '2021-12-30', NULL, 1, 3, 21, 1),
(77, 1, '2021-12-30', NULL, 1, 2, 18, 1),
(78, 4, '2021-12-30', NULL, 1, 3, 21, 2),
(79, 1, '2021-12-30', NULL, 1, 2, 18, 1),
(80, 4, '2021-12-30', NULL, 1, 3, 21, 2),
(81, 1, '2021-12-30', NULL, 1, 2, 18, 1),
(82, 1, '2022-01-19', NULL, 1, 3, 18, 1),
(83, 4, '2022-01-24', NULL, 1, 3, 21, 1),
(84, 1, '2022-01-24', NULL, 1, 2, 18, 1),
(85, 2, '2022-01-24', NULL, 1, 3, 15, 1),
(86, 1, '2022-01-24', NULL, 1, 2, 18, 1),
(87, 2, '2022-01-24', NULL, 1, 3, 15, 1),
(88, 2, '2022-01-25', NULL, 2, 3, 11, 1),
(89, 1, '2022-01-25', NULL, 3, 2, 23, 1),
(90, 2, '2022-01-25', NULL, 2, 3, 11, 1),
(91, 1, '2022-01-25', NULL, 3, 2, 23, 1),
(92, 2, '2022-01-25', NULL, 1, 3, 15, 1),
(93, 1, '2022-01-25', NULL, 1, 2, 18, 1),
(94, 2, '2022-01-25', NULL, 1, 3, 15, 1),
(95, 1, '2022-01-25', NULL, 1, 2, 18, 1),
(96, 2, '2022-01-25', NULL, 3, 3, 16, 1),
(97, 1, '2022-01-25', NULL, 3, 2, 19, 1),
(98, 2, '2022-01-25', NULL, 3, 3, 16, 1),
(99, 1, '2022-01-25', NULL, 3, 2, 19, 1),
(100, 4, '2022-01-25', NULL, 1, 3, 21, 1),
(101, 2, '2022-01-25', NULL, 1, 3, 15, 1),
(102, 1, '2022-01-25', NULL, 1, 3, 18, 1),
(103, 4, '2022-01-25', NULL, 1, 3, 21, 1),
(104, 1, '2022-01-25', NULL, 1, 3, 18, 1),
(105, 1, '2022-01-25', NULL, 1, 2, 18, 1),
(106, 2, '2022-01-25', NULL, 1, 2, 15, 1),
(107, 1, '2022-01-25', NULL, 1, 2, 18, 1),
(108, 2, '2022-01-26', NULL, 1, 2, 15, 1),
(109, 1, '2022-01-26', NULL, 1, 2, 18, 1),
(110, 2, '2022-01-26', NULL, 1, 2, 15, 1),
(111, 5, '2022-01-31', NULL, 1, 1, 17, 9),
(112, 5, '2022-02-01', NULL, 4, 1, 20, 8),
(113, 8, '2022-02-01', NULL, 2, 3, 19, 9),
(114, 5, '2022-02-01', NULL, 4, 1, 20, 8),
(115, 8, '2022-02-01', NULL, 2, 3, 19, 9),
(116, 1, '2022-02-07', NULL, 2, 1, 8, 1),
(117, 2, '2022-02-07', NULL, 1, 3, 13, 10),
(118, 1, '2022-02-07', NULL, 2, 1, 8, 1),
(119, 2, '2022-02-07', NULL, 1, 3, 13, 10),
(120, 4, '2022-02-07', NULL, 1, 3, 21, 1),
(121, 1, '2022-02-07', NULL, 1, 2, 18, 1),
(122, 2, '2022-02-07', NULL, 1, 3, 15, 1),
(123, 4, '2022-02-07', NULL, 1, 3, 21, 1),
(124, 1, '2022-02-07', NULL, 1, 2, 18, 1),
(125, 2, '2022-02-07', NULL, 1, 2, 16, 1),
(126, 4, '2022-02-09', NULL, 1, 3, 21, 1),
(127, 1, '2022-02-09', NULL, 1, 3, 18, 1),
(128, 2, '2022-02-09', NULL, 1, 3, 15, 1),
(129, 4, '2022-02-09', NULL, 1, 3, 21, 1),
(130, 6, '2022-02-11', NULL, 4, 3, 18, 7),
(131, 11, '2022-02-11', NULL, 1, 2, 1, 10),
(132, 6, '2022-02-11', NULL, 4, 3, 18, 7),
(133, 11, '2022-02-11', NULL, 1, 2, 1, 10),
(134, 6, '2022-02-11', NULL, 3, 2, 17, 7),
(135, 1, '2022-02-17', NULL, 2, 1, 1, 1),
(136, 2, '2022-02-17', NULL, 4, 3, 6, 10),
(137, 1, '2022-02-17', NULL, 2, 1, 1, 1),
(138, 2, '2022-02-17', NULL, 4, 3, 6, 10),
(139, 6, '2022-02-17', NULL, 3, 3, 18, 7),
(140, 5, '2022-02-17', NULL, 3, 1, 19, 9),
(141, 6, '2022-02-17', NULL, 3, 3, 18, 7),
(142, 5, '2022-02-17', NULL, 3, 1, 19, 9),
(143, 1, '2022-02-21', NULL, 2, 1, 11, 1),
(144, 2, '2022-02-21', NULL, 4, 3, 9, 10),
(145, 1, '2022-02-21', NULL, 2, 1, 11, 1),
(146, 1, '2022-02-21', NULL, 2, 1, 11, 1),
(147, 2, '2022-02-21', NULL, 4, 3, 9, 10),
(148, 1, '2022-02-21', NULL, 2, 1, 11, 1),
(149, 1, '2022-02-21', NULL, 2, 1, 11, 1),
(150, 2, '2022-02-21', NULL, 4, 3, 9, 9),
(151, 1, '2022-02-21', NULL, 2, 1, 11, 1),
(152, 1, '2022-02-21', NULL, 2, 1, 11, 1),
(153, 2, '2022-02-21', NULL, 4, 3, 9, 9),
(154, 1, '2022-02-21', NULL, 2, 1, 11, 1),
(155, 2, '2022-02-21', NULL, 4, 3, 9, 9),
(156, 3, '2022-02-22', NULL, 2, 2, 8, 1),
(157, 4, '2022-02-22', NULL, 4, 3, 13, 10),
(158, 3, '2022-02-22', NULL, 2, 2, 8, 1),
(159, 4, '2022-02-22', NULL, 4, 3, 13, 10),
(160, 5, '2022-02-22', NULL, 4, 3, 1, 10),
(161, 2, '2022-02-22', NULL, 2, 1, 9, 1),
(162, 5, '2022-02-22', NULL, 4, 3, 1, 10),
(163, 2, '2022-02-22', NULL, 2, 1, 9, 1),
(164, 1, '2022-02-22', NULL, 2, 1, 8, 1),
(165, 2, '2022-02-22', NULL, 4, 3, 10, 10),
(166, 1, '2022-02-22', NULL, 2, 1, 8, 1),
(167, 2, '2022-02-22', NULL, 4, 3, 10, 10),
(168, 1, '2022-02-23', NULL, 2, 1, 11, 1),
(169, 2, '2022-02-23', NULL, 4, 3, 13, 10),
(170, 1, '2022-02-23', NULL, 2, 1, 11, 1),
(171, 2, '2022-02-23', NULL, 4, 3, 13, 10),
(172, 1, '2022-02-24', NULL, 1, 2, 18, 1),
(173, 3, '2022-02-24', NULL, 2, 1, 8, 1),
(174, 4, '2022-02-24', NULL, 4, 3, 13, 10),
(175, 3, '2022-02-24', NULL, 2, 1, 8, 1),
(176, 4, '2022-02-24', NULL, 4, 3, 13, 10),
(177, 1, '2022-02-25', NULL, 5, 1, 1, 1),
(178, 3, '2022-02-25', NULL, 4, 3, 16, 10),
(179, 1, '2022-02-25', NULL, 5, 1, 1, 1),
(180, 3, '2022-02-25', NULL, 4, 3, 16, 10),
(181, 2, '2022-02-25', NULL, 5, 1, 1, 1),
(182, 3, '2022-02-25', NULL, 4, 3, 15, 10),
(183, 2, '2022-02-25', NULL, 5, 1, 1, 1),
(184, 3, '2022-02-25', NULL, 4, 3, 15, 10),
(185, 1, '2022-02-28', NULL, 1, 3, 18, 1),
(186, 2, '2022-02-28', NULL, 1, 3, 18, 1),
(187, 4, '2022-02-28', NULL, 1, 3, 21, 1),
(188, 4, '2022-02-28', NULL, 5, 3, 1, 1),
(189, 1, '2022-02-28', NULL, 1, 3, 18, 1),
(190, 5, '2022-03-01', NULL, 5, 2, 21, 6),
(191, 7, '2022-03-01', NULL, 3, 1, 23, 5),
(192, 5, '2022-03-01', NULL, 5, 2, 21, 6),
(193, 7, '2022-03-01', NULL, 3, 1, 23, 5),
(194, 2, '2022-03-02', NULL, 6, 3, 42, 7),
(195, 1, '2022-03-02', NULL, 1, 2, 34, 10),
(196, 2, '2022-03-02', NULL, 6, 3, 42, 7),
(197, 1, '2022-03-02', NULL, 1, 2, 34, 10),
(198, 3, '2022-03-02', NULL, 1, 1, 22, 2),
(199, 1, '2022-03-02', NULL, 5, 2, 23, 9),
(200, 3, '2022-03-02', NULL, 1, 1, 22, 2),
(201, 1, '2022-03-02', NULL, 5, 2, 23, 9),
(202, 3, '2022-03-03', NULL, 5, 1, 1, 1),
(203, 4, '2022-03-03', NULL, 1, 3, 17, 10),
(204, 3, '2022-03-03', NULL, 5, 1, 1, 1),
(205, 4, '2022-03-03', NULL, 1, 3, 17, 10),
(206, 3, '2022-03-03', NULL, 2, 3, 22, 4),
(207, 5, '2022-03-03', NULL, 5, 1, 41, 4),
(208, 3, '2022-03-03', NULL, 2, 3, 22, 4),
(209, 5, '2022-03-03', NULL, 5, 1, 41, 4),
(210, 7, '2022-03-03', NULL, 6, 2, 25, 5),
(211, 5, '2022-03-03', NULL, 4, 1, 20, 7),
(212, 7, '2022-03-03', NULL, 6, 2, 25, 5),
(213, 5, '2022-03-03', NULL, 4, 1, 20, 7),
(214, 1, '2022-03-03', NULL, 5, 3, 1, 10),
(215, 3, '2022-03-03', NULL, 5, 1, 1, 1),
(216, 4, '2022-03-03', NULL, 6, 3, 20, 10),
(217, 3, '2022-03-03', NULL, 5, 1, 1, 1),
(218, 4, '2022-03-03', NULL, 6, 3, 20, 10),
(219, 2, '2022-03-03', NULL, 3, 2, 22, 2),
(220, 10, '2022-03-03', NULL, 1, 1, 23, 5),
(221, 2, '2022-03-03', NULL, 3, 2, 22, 2),
(222, 10, '2022-03-03', NULL, 1, 1, 23, 5),
(223, 3, '2022-03-04', NULL, 2, 2, 27, 3),
(224, 1, '2022-03-04', NULL, 1, 1, 20, 3),
(225, 3, '2022-03-04', NULL, 2, 2, 27, 3),
(226, 1, '2022-03-04', NULL, 1, 1, 20, 3),
(227, 1, '2022-03-07', NULL, 2, 1, 22, 1),
(228, 1, '2022-03-08', NULL, 5, 3, 1, 10),
(229, 1, '2022-03-08', NULL, 2, 1, 11, 1),
(230, 2, '2022-03-08', NULL, 2, 2, 15, 1),
(231, 2, '2022-03-08', NULL, 2, 2, 15, 1),
(232, 1, '2022-03-08', NULL, 3, 2, 11, 1),
(233, 1, '2022-03-09', NULL, 1, 3, 18, 1),
(234, 2, '2022-03-09', NULL, 1, 3, 15, 1),
(235, 1, '2022-03-09', NULL, 1, 3, 18, 1),
(236, 1, '2022-03-09', NULL, 1, 3, 18, 1),
(237, 2, '2022-03-09', NULL, 1, 3, 15, 1),
(238, 1, '2022-03-09', NULL, 1, 3, 18, 1),
(239, 1, '2022-03-09', NULL, 5, 2, 1, 1),
(240, 4, '2022-03-10', NULL, 5, 3, 1, 10),
(241, 3, '2022-03-10', NULL, 4, 3, 15, 10),
(242, 4, '2022-03-10', NULL, 5, 3, 1, 10),
(243, 1, '2022-03-10', NULL, 6, 1, 48, 1),
(244, 2, '2022-03-10', NULL, 2, 1, 23, 1),
(245, 1, '2022-03-10', NULL, 6, 1, 48, 1),
(246, 1, '2022-03-11', NULL, 5, 3, 1, 10),
(247, 2, '2022-03-11', NULL, 4, 3, 15, 10),
(248, 3, '2022-03-11', NULL, 2, 3, 16, 10),
(249, 4, '2022-03-11', NULL, 2, 3, 17, 10),
(250, 1, '2022-03-11', NULL, 5, 3, 1, 10),
(251, 1, '2022-03-11', NULL, 5, 1, 1, 1),
(252, 1, '2022-03-11', NULL, 1, 3, 18, 1),
(253, 2, '2022-03-11', NULL, 1, 3, 15, 1),
(254, 1, '2022-03-11', NULL, 3, 2, 19, 1),
(255, 1, '2022-03-11', NULL, 1, 3, 18, 1),
(256, 1, '2022-03-11', NULL, 3, 2, 19, 1),
(257, 1, '2022-03-11', NULL, 3, 2, 19, 1),
(258, 2, '2022-03-11', NULL, 1, 2, 15, 1),
(259, 1, '2022-03-11', NULL, 3, 2, 19, 1),
(260, 2, '2022-03-12', NULL, 5, 2, 1, 1),
(261, 1, '2022-03-12', NULL, 5, 1, 1, 1),
(262, 2, '2022-03-12', NULL, 6, 1, 48, 1),
(263, 1, '2022-03-12', NULL, 4, 1, 15, 1),
(264, 1, '2022-03-12', NULL, 5, 1, 1, 1),
(265, 2, '2022-03-12', NULL, 2, 1, 23, 1),
(266, 4, '2022-03-12', NULL, 3, 3, 29, 1),
(267, 2, '2022-03-12', NULL, 2, 1, 23, 1),
(268, 4, '2022-03-12', NULL, 3, 3, 29, 1),
(269, 5, '2022-03-12', NULL, 2, 3, 17, 1),
(270, 1, '2022-03-12', NULL, 4, 1, 11, 1),
(271, 2, '2022-03-12', NULL, 1, 2, 30, 1),
(272, 5, '2022-03-12', NULL, 2, 3, 17, 1),
(273, 1, '2022-03-12', NULL, 4, 1, 11, 1),
(274, 2, '2022-03-12', NULL, 1, 2, 30, 1),
(275, 2, '2022-03-14', NULL, 5, 1, 1, 1),
(276, 1, '2022-03-14', NULL, 5, 2, 1, 1),
(277, 4, '2022-03-15', NULL, 2, 3, 17, 1),
(278, 4, '2022-03-15', NULL, 5, 3, 1, 1),
(279, 2, '2022-03-15', NULL, 4, 1, 15, 1),
(280, 4, '2022-03-15', NULL, 5, 3, 1, 1),
(281, 2, '2022-03-15', NULL, 4, 1, 15, 1),
(282, 4, '2022-03-18', NULL, 3, 3, 19, 1),
(283, 4, '2022-03-18', NULL, 2, 3, 15, 1),
(284, 2, '2022-03-18', NULL, 4, 2, 16, 1),
(285, 1, '2022-03-18', NULL, 3, 1, 28, 1),
(286, 4, '2022-03-18', NULL, 2, 3, 15, 1),
(287, 2, '2022-03-18', NULL, 4, 2, 16, 1),
(288, 1, '2022-03-18', NULL, 3, 1, 28, 1),
(289, 4, '2022-03-18', NULL, 1, 3, 23, 1),
(290, 2, '2022-03-18', NULL, 1, 2, 15, 1),
(291, 1, '2022-03-18', NULL, 3, 1, 19, 1),
(292, 4, '2022-03-18', NULL, 1, 3, 23, 1),
(293, 2, '2022-03-18', NULL, 1, 2, 15, 1),
(294, 1, '2022-03-18', NULL, 3, 1, 19, 1),
(295, 2, '2022-03-18', NULL, 2, 3, 15, 1),
(296, 1, '2022-03-18', NULL, 4, 3, 29, 1),
(297, 4, '2022-03-18', NULL, 1, 3, 39, 1),
(298, 2, '2022-03-18', NULL, 2, 3, 15, 1),
(299, 2, '2022-03-18', NULL, 4, 3, 15, 1),
(300, 1, '2022-03-18', NULL, 5, 3, 1, 1),
(301, 4, '2022-03-18', NULL, 2, 3, 49, 1),
(302, 2, '2022-03-18', NULL, 4, 3, 15, 1),
(303, 4, '2022-03-18', NULL, 5, 3, 23, 1),
(304, 2, '2022-03-18', NULL, 1, 2, 15, 1),
(305, 1, '2022-03-18', NULL, 3, 1, 16, 1),
(306, 4, '2022-03-18', NULL, 5, 3, 23, 1),
(307, 2, '2022-03-18', NULL, 1, 2, 15, 1),
(308, 1, '2022-03-18', NULL, 3, 1, 16, 1),
(309, 1, '2022-03-19', NULL, 3, 3, 19, 1),
(310, 2, '2022-03-19', NULL, 1, 1, 17, 1),
(311, 1, '2022-03-19', NULL, 3, 3, 19, 1),
(312, 2, '2022-03-19', NULL, 1, 1, 17, 1),
(313, 1, '2022-03-21', NULL, 1, 3, 15, 1),
(314, 2, '2022-03-21', NULL, 4, 3, 16, 1),
(315, 4, '2022-03-21', NULL, 5, 3, 17, 1),
(316, 5, '2022-03-21', NULL, 6, 3, 48, 1),
(317, 2, '2022-03-21', NULL, 6, 3, 49, 1),
(318, 1, '2022-03-21', NULL, 1, 3, 15, 1),
(319, 6, '2022-03-21', NULL, 3, 3, 19, 1),
(320, 1, '2022-03-21', NULL, 3, 1, 19, 1),
(321, 5, '2022-03-21', NULL, 3, 3, 19, 1),
(322, 4, '2022-03-22', NULL, 3, 3, 19, 1),
(323, 4, '2022-03-23', NULL, 1, 3, 18, 1),
(324, 1, '2022-03-23', NULL, 1, 3, 30, 1),
(325, 2, '2022-03-23', NULL, 1, 3, 42, 1),
(326, 5, '2022-03-23', NULL, 6, 3, 775, 1),
(327, 4, '2022-03-23', NULL, 2, 3, 776, 1),
(328, 4, '2022-03-23', NULL, 1, 3, 18, 1),
(329, 2, '2022-03-24', NULL, 1, 3, 18, 1),
(330, 2, '2022-03-24', NULL, 3, 3, 388, 1),
(331, 2, '2022-03-24', NULL, 1, 3, 30, 1),
(332, 2, '2022-03-24', NULL, 1, 3, 18, 1),
(333, 2, '2022-03-24', NULL, 3, 3, 711, 1),
(334, 1, '2022-03-24', NULL, 1, 3, 497, 1),
(335, 4, '2022-03-24', NULL, 5, 3, 680, 1),
(336, 2, '2022-03-24', NULL, 3, 3, 711, 1),
(337, 2, '2022-03-24', NULL, 1, 2, 18, 1),
(338, 2, '2022-03-24', NULL, 1, 2, 30, 1),
(339, 2, '2022-03-24', NULL, 1, 2, 18, 1),
(340, 1, '2022-03-25', NULL, 1, 2, 18, 1),
(341, 2, '2022-03-25', NULL, 1, 2, 18, 1),
(342, 2, '2022-03-25', NULL, 3, 1, 576, 1),
(343, 5, '2022-03-25', NULL, 5, 3, 693, 1),
(344, 1, '2022-03-30', NULL, 6, 1, 775, 1),
(345, 1, '2022-03-30', NULL, 3, 1, 576, 1),
(346, 1, '2022-04-01', NULL, 1, 3, 502, 1),
(347, 1, '2022-04-04', NULL, 1, 2, 18, 1),
(348, 4, '2022-04-04', NULL, 3, 3, 576, 1),
(349, 1, '2022-04-04', NULL, 1, 2, 18, 1),
(350, 4, '2022-04-04', NULL, 3, 3, 576, 1),
(351, 4, '2022-04-04', NULL, 3, 3, 378, 1),
(352, 1, '2022-04-04', NULL, 3, 1, 392, 1),
(353, 5, '2022-04-04', NULL, 3, 3, 380, 1),
(354, 2, '2022-04-04', NULL, 3, 1, 378, 1),
(355, 1, '2022-04-04', NULL, 3, 1, 378, 1),
(356, 5, '2022-04-04', NULL, 1, 3, 497, 1),
(357, 1, '2022-04-04', NULL, 3, 1, 378, 1),
(358, 5, '2022-04-04', NULL, 1, 3, 497, 1),
(359, 5, '2022-04-05', NULL, 3, 3, 578, 1),
(360, 1, '2022-04-05', NULL, 1, 2, 502, 1),
(361, 5, '2022-04-05', NULL, 3, 3, 578, 1),
(362, 1, '2022-04-05', NULL, 1, 2, 502, 1),
(363, 1, '2022-04-06', NULL, 3, 2, 576, 1),
(364, 1, '2022-04-06', NULL, 1, 2, 30, 1),
(365, 1, '2022-04-07', NULL, 1, 3, 18, 1),
(366, 1, '2022-04-07', NULL, 1, 3, 18, 1),
(367, 1, '2022-04-07', NULL, 1, 3, 18, 1),
(368, 1, '2022-04-07', NULL, 1, 3, 18, 1),
(369, 1, '2022-04-07', NULL, 1, 1, 502, 1),
(370, 4, '2022-04-08', NULL, 3, 3, 576, 1),
(371, 2, '2022-04-08', NULL, 1, 1, 30, 1),
(372, 4, '2022-04-08', NULL, 3, 3, 576, 1),
(373, 4, '2022-04-08', NULL, 3, 3, 576, 1),
(374, 2, '2022-04-08', NULL, 1, 1, 30, 1),
(375, 4, '2022-04-08', NULL, 3, 3, 576, 1),
(376, 2, '2022-04-08', NULL, 1, 1, 30, 1),
(377, 1, '2022-04-08', NULL, 3, 2, 576, 1),
(378, 4, '2022-04-09', NULL, 3, 3, 384, 1),
(379, 1, '2022-04-09', NULL, 1, 3, 18, 1),
(380, 1, '2022-04-09', NULL, 1, 3, 18, 1),
(381, 2, '2022-04-10', NULL, 5, 3, 486, 1),
(382, 5, '2022-04-12', NULL, 1, 3, 30, 1),
(383, 2, '2022-04-12', NULL, 3, 1, 378, 1),
(384, 2, '2022-04-12', NULL, 5, 2, 486, 1),
(385, 5, '2022-04-12', NULL, 1, 3, 30, 1),
(386, 2, '2022-04-12', NULL, 3, 1, 378, 1),
(387, 2, '2022-04-12', NULL, 5, 2, 486, 1),
(388, 2, '2022-04-12', NULL, 1, 1, 18, 1),
(389, 4, '2022-04-12', NULL, 3, 3, 378, 1),
(390, 2, '2022-04-12', NULL, 1, 1, 18, 1),
(391, 4, '2022-04-12', NULL, 3, 3, 378, 1),
(392, 2, '2022-04-12', NULL, 3, 2, 378, 1),
(393, 5, '2022-04-12', NULL, 1, 3, 502, 1),
(394, 2, '2022-04-12', NULL, 3, 2, 378, 1),
(395, 5, '2022-04-12', NULL, 1, 3, 502, 1),
(396, 5, '2022-04-13', NULL, 3, 3, 378, 1),
(397, 2, '2022-04-13', NULL, 1, 1, 18, 1),
(398, 5, '2022-04-13', NULL, 3, 3, 378, 1),
(399, 2, '2022-04-13', NULL, 1, 1, 18, 1),
(400, 2, '2022-04-13', NULL, 1, 1, 18, 1),
(401, 4, '2022-04-13', NULL, 3, 3, 576, 1),
(402, 2, '2022-04-13', NULL, 1, 1, 18, 1),
(403, 4, '2022-04-13', NULL, 3, 3, 576, 1),
(404, 1, '2022-04-13', NULL, 1, 1, 497, 1),
(405, 4, '2022-04-13', NULL, 3, 3, 386, 1),
(406, 1, '2022-04-13', NULL, 1, 1, 497, 1),
(407, 4, '2022-04-13', NULL, 3, 3, 386, 1),
(408, 2, '2022-04-13', NULL, 1, 3, 18, 1),
(409, 1, '2022-04-13', NULL, 3, 2, 576, 1),
(410, 2, '2022-04-13', NULL, 1, 3, 22, 1),
(411, 1, '2022-04-14', NULL, 3, 2, 576, 1),
(412, 5, '2022-04-14', NULL, 3, 3, 692, 1),
(413, 2, '2022-04-14', NULL, 1, 1, 30, 1),
(414, 5, '2022-04-14', NULL, 3, 3, 692, 1),
(415, 2, '2022-04-14', NULL, 1, 1, 30, 1),
(416, 1, '2022-04-14', NULL, 1, 2, 496, 1),
(417, 2, '2022-04-15', NULL, 3, 3, 384, 1),
(418, 1, '2022-04-15', NULL, 1, 1, 498, 1),
(419, 2, '2022-04-15', NULL, 3, 3, 384, 1),
(420, 1, '2022-04-15', NULL, 1, 1, 498, 1),
(421, 1, '2022-04-15', NULL, 1, 2, 503, 1),
(422, 1, '2022-04-19', NULL, 1, 3, 496, 1),
(423, 1, '2022-04-19', NULL, 3, 2, 576, 1),
(424, 1, '2022-04-19', NULL, 1, 1, 498, 1),
(425, 1, '2022-04-19', NULL, 1, 3, 496, 1),
(426, 2, '2022-04-20', NULL, 1, 3, 18, 1),
(427, 1, '2022-04-20', NULL, 3, 3, 576, 1),
(428, 1, '2022-04-20', NULL, 1, 1, 498, 1),
(429, 1, '2022-04-20', NULL, 3, 3, 576, 1),
(430, 1, '2022-04-20', NULL, 1, 1, 498, 1),
(431, 4, '2022-04-20', NULL, 1, 3, 677, 1),
(432, 2, '2022-04-20', NULL, 1, 3, 18, 1),
(433, 1, '2022-04-20', NULL, 3, 2, 576, 1),
(434, 2, '2022-04-20', NULL, 1, 3, 18, 1),
(435, 1, '2022-04-20', NULL, 3, 2, 576, 1),
(436, 1, '2022-04-21', NULL, 3, 1, 576, 1),
(437, 2, '2022-04-21', NULL, 3, 2, 384, 1),
(438, 1, '2022-04-21', NULL, 3, 2, 576, 1),
(439, 4, '2022-04-21', NULL, 1, 3, 676, 1),
(440, 1, '2022-04-21', NULL, 3, 2, 576, 1),
(441, 4, '2022-04-21', NULL, 1, 3, 676, 1),
(442, 1, '2022-04-21', NULL, 1, 3, 496, 1),
(443, 2, '2022-04-21', NULL, 3, 3, 382, 1),
(444, 4, '2022-04-21', NULL, 5, 3, 680, 1),
(445, 1, '2022-04-21', NULL, 1, 3, 496, 1),
(446, 1, '2022-04-21', NULL, 3, 2, 576, 1),
(447, 1, '2022-04-21', NULL, 1, 2, 496, 1),
(448, 2, '2022-04-21', NULL, 1, 2, 18, 1),
(449, 1, '2022-04-21', NULL, 1, 1, 499, 1),
(450, 2, '2022-04-21', NULL, 3, 1, 388, 1),
(451, 1, '2022-04-21', NULL, 1, 1, 499, 1),
(452, 1, '2022-04-22', NULL, 1, 1, 496, 1),
(453, 1, '2022-04-22', NULL, 3, 2, 576, 1),
(454, 2, '2022-04-25', NULL, 1, 3, 20, 1),
(455, 1, '2022-04-25', NULL, 3, 3, 576, 1),
(456, 2, '2022-04-25', NULL, 1, 3, 18, 1),
(457, 4, '2022-04-25', NULL, 5, 3, 680, 1),
(458, 1, '2022-04-25', NULL, 3, 3, 576, 1),
(459, 1, '2022-04-25', NULL, 1, 1, 497, 1),
(460, 2, '2022-04-25', NULL, 3, 1, 388, 1),
(461, 1, '2022-04-25', NULL, 3, 2, 576, 1),
(462, 2, '2022-04-25', NULL, 1, 2, 30, 1),
(463, 4, '2022-04-25', NULL, 3, 3, 679, 1),
(464, 4, '2022-04-25', NULL, 5, 3, 680, 1),
(465, 1, '2022-04-25', NULL, 1, 1, 497, 1),
(466, 4, '2022-04-26', NULL, 3, 3, 679, 1),
(467, 1, '2022-04-27', NULL, 3, 2, 576, 1),
(468, 4, '2022-04-29', NULL, 1, 3, 676, 1),
(469, 1, '2022-04-29', NULL, 3, 1, 576, 1),
(470, 4, '2022-04-29', NULL, 1, 3, 676, 1),
(471, 1, '2022-04-29', NULL, 3, 1, 576, 1),
(472, 5, '2022-05-11', NULL, 3, 3, 692, 1),
(473, 1, '2022-05-11', NULL, 1, 3, 496, 1),
(474, 2, '2022-05-11', NULL, 3, 3, 382, 1),
(475, 4, '2022-05-11', NULL, 5, 3, 680, 1),
(476, 4, '2022-05-11', NULL, 5, 3, 680, 1),
(477, 1, '2022-05-11', NULL, 1, 2, 497, 1),
(478, 2, '2022-05-11', NULL, 3, 2, 378, 1),
(479, 1, '2022-05-11', NULL, 5, 2, 596, 1),
(480, 1, '2022-05-11', NULL, 5, 2, 596, 1),
(481, 1, '2022-05-12', NULL, 3, 1, 576, 1),
(482, 5, '2022-05-12', NULL, 1, 3, 688, 1),
(483, 1, '2022-05-12', NULL, 5, 1, 596, 1),
(484, 2, '2022-05-12', NULL, 3, 1, 378, 1),
(485, 2, '2022-05-12', NULL, 3, 1, 378, 1),
(486, 5, '2022-05-17', NULL, 5, 3, 693, 1),
(487, 4, '2022-05-17', NULL, 3, 3, 679, 1),
(488, 4, '2022-05-17', NULL, 3, 3, 679, 1),
(489, 1, '2022-05-17', NULL, 1, 3, 496, 1),
(490, 1, '2022-05-18', NULL, 1, 3, 497, 1),
(491, 4, '2022-05-18', NULL, 3, 3, 679, 1),
(492, 4, '2022-05-18', NULL, 3, 3, 679, 1),
(493, 1, '2022-05-18', NULL, 1, 1, 498, 1),
(494, 2, '2022-05-18', NULL, 5, 2, 486, 1),
(495, 4, '2022-05-18', NULL, 3, 3, 679, 1),
(496, 1, '2022-05-18', NULL, 1, 1, 498, 1),
(497, 2, '2022-05-18', NULL, 5, 2, 486, 1),
(498, 4, '2022-05-18', NULL, 3, 3, 679, 1),
(499, 1, '2022-05-19', NULL, 1, 1, 499, 1),
(500, 1, '2022-05-24', NULL, 1, 3, 499, 1),
(501, 1, '2022-05-27', NULL, 1, 1, 496, 1),
(502, 1, '2022-05-31', NULL, 5, 2, 596, 1),
(503, 2, '2022-06-03', NULL, 3, 2, 378, 1),
(504, 1, '2022-06-03', NULL, 5, 2, 596, 1),
(505, 1, '2022-06-03', NULL, 5, 2, 596, 1),
(506, 2, '2022-06-08', NULL, 3, 2, 384, 1),
(507, 4, '2022-06-11', NULL, 1, 3, 676, 1),
(508, 5, '2022-06-11', NULL, 3, 3, 692, 1),
(509, 1, '2022-06-11', NULL, 3, 2, 576, 1),
(510, 5, '2022-06-14', NULL, 3, 3, 692, 1),
(511, 1, '2022-06-14', NULL, 1, 2, 503, 1),
(512, 1, '2022-06-14', NULL, 3, 2, 576, 1),
(513, 2, '2022-06-15', NULL, 5, 1, 486, 1),
(514, 1, '2022-06-15', NULL, 3, 2, 576, 1),
(515, 1, '2022-06-15', NULL, 1, 2, 499, 1),
(516, 1, '2022-06-15', NULL, 1, 3, 496, 1),
(517, 4, '2022-06-16', NULL, 3, 3, 679, 1),
(518, 1, '2022-06-16', NULL, 1, 3, 498, 1),
(519, 1, '2022-06-16', NULL, 5, 2, 596, 1),
(520, 3, '2022-06-16', NULL, 1, 2, 618, 1),
(521, 1, '2022-06-16', NULL, 1, 3, 498, 1),
(522, 3, '2022-06-16', NULL, 1, 2, 618, 1),
(523, 1, '2022-06-16', NULL, 1, 1, 497, 1),
(524, 2, '2022-06-16', NULL, 3, 1, 380, 1),
(525, 5, '2022-06-16', NULL, 5, 1, 693, 1),
(526, 5, '2022-06-16', NULL, 5, 1, 693, 1),
(527, 1, '2022-06-16', NULL, 1, 1, 497, 1),
(528, 2, '2022-06-16', NULL, 3, 1, 384, 1),
(529, 5, '2022-06-16', NULL, 5, 1, 693, 1),
(530, 5, '2022-06-16', NULL, 5, 1, 693, 1),
(531, 4, '2022-06-16', NULL, 3, 3, 679, 1),
(532, 2, '2022-06-16', NULL, 5, 3, 486, 1),
(533, 1, '2022-06-16', NULL, 1, 2, 496, 1),
(534, 3, '2022-06-16', NULL, 5, 2, 636, 1),
(535, 2, '2022-06-16', NULL, 5, 3, 486, 1),
(536, 3, '2022-06-16', NULL, 5, 2, 636, 1),
(537, 4, '2022-06-18', NULL, 5, 3, 680, 1),
(538, 2, '2022-06-18', NULL, 1, 3, 22, 1),
(539, 1, '2022-06-18', NULL, 3, 2, 576, 1),
(540, 3, '2022-06-18', NULL, 5, 2, 636, 1),
(541, 2, '2022-06-18', NULL, 1, 3, 22, 1),
(542, 3, '2022-06-18', NULL, 5, 2, 636, 1),
(543, 1, '2022-06-18', NULL, 3, 1, 576, 1),
(544, 1, '2022-06-20', NULL, 1, 1, 497, 1),
(545, 2, '2022-06-20', NULL, 3, 1, 380, 1),
(546, 5, '2022-06-20', NULL, 5, 1, 693, 1),
(547, 5, '2022-06-20', NULL, 5, 1, 693, 1),
(548, 1, '2022-06-20', NULL, 5, 2, 596, 1),
(549, 1, '2022-06-20', NULL, 5, 1, 596, 1),
(550, 1, '2022-06-20', NULL, 1, 2, 501, 1),
(551, 4, '2022-06-20', NULL, 5, 3, 754, 1),
(552, 1, '2022-06-20', NULL, 1, 2, 501, 1),
(553, 4, '2022-06-20', NULL, 5, 3, 754, 1),
(554, 1, '2022-06-20', NULL, 1, 2, 499, 1),
(555, 4, '2022-06-20', NULL, 5, 2, 680, 1),
(556, 1, '2022-06-20', NULL, 1, 1, 499, 1),
(557, 1, '2022-06-23', NULL, 1, 1, 496, 1),
(558, 2, '2022-06-23', NULL, 3, 1, 378, 1),
(559, 5, '2022-06-23', NULL, 5, 1, 693, 1),
(560, 5, '2022-06-23', NULL, 5, 1, 693, 1),
(561, 1, '2022-06-24', NULL, 1, 2, 497, 1),
(562, 1, '2022-07-01', NULL, 1, 2, 500, 1),
(563, 2, '2022-07-01', NULL, 3, 1, 382, 1),
(564, 1, '2022-07-01', NULL, 1, 2, 500, 1),
(565, 2, '2022-07-01', NULL, 3, 1, 382, 1),
(566, 1, '2022-07-04', NULL, 5, 2, 749, 1),
(567, 4, '2022-07-04', NULL, 3, 2, 679, 1),
(568, 4, '2022-07-04', NULL, 3, 2, 679, 1),
(569, 1, '2022-07-04', NULL, 5, 2, 749, 1),
(570, 4, '2022-07-04', NULL, 3, 2, 679, 1),
(571, 4, '2022-07-04', NULL, 3, 2, 679, 1),
(572, 1, '2022-07-04', NULL, 5, 2, 596, 1),
(573, 3, '2022-07-04', NULL, 3, 2, 634, 1),
(574, 3, '2022-07-04', NULL, 3, 2, 634, 1),
(575, 1, '2022-07-04', NULL, 5, 2, 596, 1),
(576, 3, '2022-07-04', NULL, 3, 2, 634, 1),
(577, 3, '2022-07-04', NULL, 3, 2, 634, 1),
(578, 3, '2022-07-07', NULL, 1, 2, 614, 1),
(579, 1, '2022-07-07', NULL, 3, 3, 576, 1),
(580, 4, '2022-07-07', NULL, 5, 3, 680, 1),
(581, 4, '2022-07-07', NULL, 5, 3, 680, 1),
(582, 3, '2022-07-07', NULL, 1, 2, 614, 1),
(583, 5, '2022-07-13', NULL, 5, 3, 693, 1),
(584, 4, '2022-07-13', NULL, 3, 3, 679, 1),
(585, 1, '2022-07-13', NULL, 1, 2, 500, 1),
(586, 2, '2022-07-13', NULL, 3, 1, 388, 1),
(587, 4, '2022-07-13', NULL, 3, 3, 679, 1),
(588, 1, '2022-07-13', NULL, 1, 2, 500, 1),
(589, 2, '2022-07-13', NULL, 3, 1, 388, 1),
(590, 5, '2022-07-14', NULL, 5, 3, 693, 1),
(591, 4, '2022-07-14', NULL, 3, 3, 679, 1),
(592, 1, '2022-07-14', NULL, 1, 1, 496, 1),
(593, 4, '2022-07-14', NULL, 3, 3, 679, 1),
(594, 1, '2022-07-14', NULL, 1, 1, 496, 1),
(595, 1, '2022-07-14', NULL, 5, 2, 596, 1),
(596, 5, '2022-07-14', NULL, 3, 3, 692, 1),
(597, 4, '2022-07-14', NULL, 5, 3, 680, 1),
(598, 1, '2022-07-14', NULL, 1, 1, 499, 1),
(599, 4, '2022-07-14', NULL, 5, 3, 680, 1),
(600, 1, '2022-07-14', NULL, 1, 1, 499, 1),
(601, 1, '2022-07-15', NULL, 5, 1, 596, 1),
(602, 5, '2022-07-15', NULL, 5, 3, 693, 1),
(603, 4, '2022-07-15', NULL, 3, 3, 679, 1),
(604, 1, '2022-07-15', NULL, 1, 1, 499, 1),
(605, 4, '2022-07-15', NULL, 3, 3, 679, 1),
(606, 1, '2022-07-15', NULL, 1, 1, 499, 1),
(607, 4, '2022-07-15', NULL, 1, 3, 676, 1),
(608, 5, '2022-07-15', NULL, 3, 3, 692, 1),
(609, 1, '2022-07-15', NULL, 5, 1, 596, 1),
(610, 5, '2022-07-15', NULL, 3, 3, 692, 1),
(611, 1, '2022-07-15', NULL, 5, 1, 596, 1),
(612, 1, '2022-07-18', NULL, 1, 2, 505, 2),
(613, 1, '2022-07-18', NULL, 1, 2, 505, 2),
(614, 1, '2022-07-18', NULL, 1, 2, 505, 2),
(615, 1, '2022-07-18', NULL, 5, 2, 597, 2),
(616, 4, '2022-07-18', NULL, 5, 2, 680, 1),
(617, 1, '2022-07-18', NULL, 1, 2, 500, 1),
(618, 1, '2022-07-18', NULL, 1, 2, 500, 1),
(619, 1, '2022-07-18', NULL, 1, 2, 496, 1),
(620, 1, '2022-07-18', NULL, 1, 2, 496, 1),
(621, 1, '2022-07-18', NULL, 3, 2, 576, 1),
(622, 1, '2022-07-18', NULL, 1, 3, 496, 1),
(623, 1, '2022-07-18', NULL, 1, 3, 496, 1),
(624, 1, '2022-07-18', NULL, 1, 3, 496, 1),
(625, 1, '2022-07-18', NULL, 1, 2, 497, 1),
(626, 1, '2022-07-18', NULL, 1, 2, 498, 1),
(627, 5, '2022-07-19', NULL, 5, 3, 693, 1),
(628, 4, '2022-07-19', NULL, 3, 3, 679, 1),
(629, 1, '2022-07-19', NULL, 1, 2, 499, 1),
(630, 4, '2022-07-19', NULL, 3, 3, 679, 1),
(631, 1, '2022-07-19', NULL, 1, 2, 499, 1),
(632, 4, '2022-07-19', NULL, 1, 3, 676, 1),
(633, 5, '2022-07-19', NULL, 3, 3, 692, 1),
(634, 1, '2022-07-19', NULL, 3, 1, 576, 1),
(635, 5, '2022-07-19', NULL, 3, 3, 692, 1),
(636, 1, '2022-07-19', NULL, 3, 1, 576, 1),
(637, 2, '2022-07-19', NULL, 1, 1, 20, 1),
(638, 1, '2022-07-19', NULL, 3, 3, 576, 1),
(639, 2, '2022-07-19', NULL, 1, 1, 20, 1),
(640, 1, '2022-07-19', NULL, 3, 3, 576, 1),
(641, 5, '2022-07-20', NULL, 3, 3, 692, 1),
(642, 4, '2022-07-20', NULL, 1, 3, 676, 1),
(643, 1, '2022-07-20', NULL, 5, 1, 596, 1),
(644, 4, '2022-07-20', NULL, 1, 3, 676, 1),
(645, 1, '2022-07-20', NULL, 5, 1, 596, 1),
(646, 1, '2022-07-22', NULL, 5, 2, 596, 1),
(647, 4, '2022-07-22', NULL, 1, 2, 678, 1),
(648, 4, '2022-07-22', NULL, 1, 2, 678, 1),
(649, 5, '2022-07-26', NULL, 3, 3, 692, 1),
(650, 4, '2022-07-26', NULL, 1, 3, 677, 1),
(651, 1, '2022-07-26', NULL, 5, 1, 596, 1),
(652, 4, '2022-07-26', NULL, 1, 3, 677, 1),
(653, 1, '2022-07-26', NULL, 5, 1, 596, 1),
(654, 3, '2022-07-26', NULL, 5, 2, 636, 1),
(655, 1, '2022-07-26', NULL, 3, 2, 576, 1),
(656, 4, '2022-07-26', NULL, 5, 1, 680, 1),
(657, 1, '2022-07-26', NULL, 3, 2, 576, 1),
(658, 2, '2022-07-26', NULL, 5, 2, 486, 1),
(659, 2, '2022-07-26', NULL, 5, 1, 486, 1),
(660, 5, '2022-07-26', NULL, 3, 3, 692, 1),
(661, 4, '2022-07-26', NULL, 1, 3, 676, 1),
(662, 1, '2022-07-26', NULL, 5, 1, 596, 1),
(663, 2, '2022-07-26', NULL, 3, 1, 738, 1),
(664, 4, '2022-07-26', NULL, 1, 3, 676, 1),
(665, 2, '2022-07-26', NULL, 3, 1, 738, 1),
(666, 5, '2022-07-27', NULL, 3, 3, 692, 1),
(667, 4, '2022-07-27', NULL, 5, 3, 680, 1),
(668, 4, '2022-07-27', NULL, 5, 3, 680, 1),
(669, 2, '2022-07-27', NULL, 5, 1, 486, 1),
(670, 3, '2022-07-27', NULL, 5, 2, 636, 1),
(671, 6, '2022-07-27', NULL, 5, 3, 695, 1),
(672, 6, '2022-07-27', NULL, 5, 3, 695, 1),
(673, 1, '2022-07-27', NULL, 1, 2, 504, 2),
(674, 2, '2022-07-27', NULL, 3, 2, 380, 1),
(675, 4, '2022-07-27', NULL, 5, 2, 680, 1),
(676, 4, '2022-07-27', NULL, 5, 2, 680, 1),
(677, 4, '2022-07-27', NULL, 1, 3, 676, 1),
(678, 2, '2022-07-27', NULL, 3, 4, 378, 1),
(679, 4, '2022-07-28', NULL, 5, 1, 754, 1),
(680, 2, '2022-07-28', NULL, 1, 3, 20, 1),
(681, 1, '2022-07-28', NULL, 3, 3, 576, 1),
(682, 6, '2022-07-28', NULL, 5, 3, 695, 1),
(683, 6, '2022-07-28', NULL, 5, 3, 695, 1),
(684, 1, '2022-07-28', NULL, 3, 1, 576, 1),
(685, 5, '2022-07-28', NULL, 1, 3, 682, 1),
(686, 2, '2022-07-28', NULL, 3, 4, 378, 1),
(687, 6, '2022-07-28', NULL, 5, 3, 695, 1),
(688, 6, '2022-07-28', NULL, 5, 3, 695, 1),
(689, 1, '2022-07-28', NULL, 3, 1, 576, 1),
(690, 2, '2022-07-28', NULL, 3, 4, 378, 1),
(691, 2, '2022-07-28', NULL, 1, 2, 18, 1),
(692, 2, '2022-07-28', NULL, 3, 2, 378, 1),
(693, 2, '2022-07-28', NULL, 3, 2, 378, 1),
(694, 4, '2022-07-29', NULL, 5, 2, 680, 1),
(695, 4, '2022-08-01', NULL, 1, 3, 678, 1),
(696, 5, '2022-08-01', NULL, 3, 1, 692, 1),
(697, 4, '2022-08-01', NULL, 1, 3, 678, 1),
(698, 5, '2022-08-01', NULL, 3, 1, 692, 1),
(699, 5, '2022-08-03', NULL, 1, 3, 682, 1),
(700, 5, '2022-08-03', NULL, 1, 1, 683, 1),
(701, 2, '2022-08-04', NULL, 1, 2, 30, 1),
(702, 6, '2022-08-05', NULL, 5, 3, 695, 1),
(703, 3, '2022-08-05', NULL, 3, 2, 632, 1),
(704, 6, '2022-08-05', NULL, 5, 3, 695, 1),
(705, 3, '2022-08-05', NULL, 3, 2, 632, 1),
(706, 5, '2022-08-06', NULL, 1, 3, 683, 1),
(707, 4, '2022-08-06', NULL, 3, 3, 679, 1),
(708, 1, '2022-08-06', NULL, 5, 1, 596, 1),
(709, 2, '2022-08-06', NULL, 1, 1, 22, 1),
(710, 4, '2022-08-06', NULL, 3, 3, 679, 1),
(711, 2, '2022-08-06', NULL, 1, 1, 22, 1),
(712, 2, '2022-08-10', NULL, 5, 2, 486, 1),
(713, 2, '2022-08-11', NULL, 1, 2, 30, 1),
(714, 1, '2022-08-12', NULL, 1, 2, 497, 1),
(715, 4, '2022-08-17', NULL, 1, 1, 676, 1),
(716, 1, '2022-08-17', NULL, 3, 1, 576, 1),
(717, 1, '2022-08-17', NULL, 3, 1, 576, 1),
(718, 4, '2022-08-17', NULL, 5, 2, 680, 1),
(719, 1, '2022-08-17', NULL, 1, 2, 502, 1),
(720, 6, '2022-08-17', NULL, 5, 3, 695, 1),
(721, 1, '2022-08-17', NULL, 1, 2, 502, 1),
(722, 6, '2022-08-17', NULL, 5, 3, 695, 1),
(723, 4, '2022-08-19', NULL, 1, 1, 678, 1),
(724, 1, '2022-08-19', NULL, 3, 2, 576, 1),
(725, 6, '2022-08-19', NULL, 5, 3, 695, 1),
(726, 4, '2022-08-19', NULL, 1, 1, 678, 1),
(727, 1, '2022-08-19', NULL, 3, 2, 576, 1),
(728, 6, '2022-08-19', NULL, 5, 3, 695, 1),
(729, 2, '2022-08-22', NULL, 3, 3, 386, 1),
(730, 1, '2022-08-29', NULL, 3, 3, 576, 1),
(731, 4, '2022-08-30', NULL, 7, 1, 677, 1),
(732, 2, '2022-08-30', NULL, 3, 2, 378, 1),
(733, 4, '2022-08-30', NULL, 7, 1, 677, 1),
(734, 2, '2022-08-30', NULL, 3, 2, 378, 1),
(735, 4, '2022-08-30', NULL, 1, 1, 677, 1),
(736, 5, '2022-08-30', NULL, 7, 3, 684, 1),
(737, 4, '2022-08-30', NULL, 1, 1, 677, 1),
(738, 5, '2022-08-30', NULL, 7, 3, 684, 1),
(739, 2, '2022-09-01', NULL, 3, 2, 378, 1),
(740, 1, '2022-09-01', NULL, 1, 2, 550, 6),
(741, 2, '2022-09-01', NULL, 3, 2, 396, 2),
(742, 2, '2022-09-01', NULL, 3, 2, 396, 2),
(743, 5, '2022-09-01', NULL, 3, 3, 692, 1),
(744, 4, '2022-09-01', NULL, 1, 2, 677, 1),
(745, 2, '2022-09-01', NULL, 7, 1, 388, 1),
(746, 5, '2022-09-01', NULL, 3, 3, 692, 1),
(747, 4, '2022-09-01', NULL, 1, 2, 677, 1),
(748, 2, '2022-09-01', NULL, 7, 1, 388, 1),
(749, 2, '2022-09-01', NULL, 7, 2, 378, 1),
(750, 5, '2022-09-02', NULL, 1, 3, 683, 1),
(751, 3, '2022-09-02', NULL, 3, 2, 624, 1),
(752, 5, '2022-09-02', NULL, 1, 3, 683, 1),
(753, 3, '2022-09-02', NULL, 3, 2, 624, 1),
(754, 5, '2022-09-03', NULL, 7, 3, 692, 1),
(755, 2, '2022-09-03', NULL, 7, 3, 388, 1),
(756, 5, '2022-09-05', NULL, 7, 3, 692, 1),
(757, 4, '2022-09-05', NULL, 5, 1, 680, 1),
(758, 5, '2022-09-05', NULL, 7, 3, 692, 1),
(759, 4, '2022-09-05', NULL, 5, 1, 680, 1),
(760, 5, '2022-09-06', NULL, 3, 3, 692, 1),
(761, 1, '2022-09-06', NULL, 7, 2, 510, 2),
(762, 2, '2022-09-06', NULL, 1, 2, 18, 1),
(763, 2, '2022-09-06', NULL, 1, 2, 18, 1),
(764, 1, '2022-09-07', NULL, 7, 2, 510, 2),
(765, 2, '2022-09-07', NULL, 1, 2, 18, 1),
(766, 2, '2022-09-07', NULL, 1, 2, 18, 1),
(767, 1, '2022-09-07', NULL, 7, 2, 510, 2),
(768, 2, '2022-09-07', NULL, 1, 2, 18, 1),
(769, 2, '2022-09-07', NULL, 1, 2, 18, 1),
(770, 1, '2022-09-07', NULL, 1, 2, 497, 1),
(771, 1, '2022-09-07', NULL, 3, 2, 576, 1),
(772, 1, '2022-09-07', NULL, 3, 2, 576, 1),
(773, 3, '2022-09-07', NULL, 3, 2, 624, 1),
(774, 5, '2022-09-07', NULL, 3, 1, 692, 1),
(775, 1, '2022-09-07', NULL, 7, 2, 510, 2),
(776, 2, '2022-09-07', NULL, 1, 2, 18, 1),
(777, 2, '2022-09-07', NULL, 1, 2, 18, 1),
(778, 1, '2022-09-07', NULL, 7, 2, 510, 2),
(779, 2, '2022-09-07', NULL, 1, 2, 18, 1),
(780, 2, '2022-09-07', NULL, 1, 2, 18, 1),
(781, 1, '2022-09-07', NULL, 7, 2, 510, 2),
(782, 2, '2022-09-07', NULL, 1, 2, 30, 1),
(783, 2, '2022-09-07', NULL, 1, 2, 30, 1),
(784, 2, '2022-09-08', NULL, 1, 2, 30, 1),
(785, 2, '2022-09-08', NULL, 1, 2, 30, 1),
(786, 2, '2022-09-08', NULL, 1, 2, 30, 1),
(787, 2, '2022-09-08', NULL, 1, 2, 30, 1),
(788, 2, '2022-09-08', NULL, 5, 2, 486, 1),
(789, 4, '2022-09-12', NULL, 7, 2, 679, 1),
(790, 5, '2022-09-12', NULL, 5, 3, 693, 1),
(791, 4, '2022-09-12', NULL, 7, 2, 679, 1),
(792, 5, '2022-09-12', NULL, 5, 3, 693, 1),
(793, 1, '2022-09-12', NULL, 1, 2, 497, 1),
(794, 1, '2022-09-12', NULL, 3, 2, 576, 1),
(795, 1, '2022-09-12', NULL, 3, 2, 576, 1),
(796, 2, '2022-09-12', NULL, 5, 2, 486, 1),
(797, 4, '2022-09-12', NULL, 7, 2, 677, 1),
(798, 4, '2022-09-12', NULL, 7, 2, 677, 1),
(799, 2, '2022-09-12', NULL, 1, 2, 30, 1),
(800, 5, '2022-09-13', NULL, 3, 1, 692, 1),
(801, 4, '2022-09-13', NULL, 5, 3, 680, 1),
(802, 5, '2022-09-13', NULL, 3, 1, 692, 1),
(803, 4, '2022-09-13', NULL, 5, 3, 680, 1),
(804, 2, '2022-09-14', NULL, 1, 2, 30, 1),
(805, 1, '2022-09-14', NULL, 7, 2, 496, 1),
(806, 1, '2022-09-14', NULL, 7, 2, 496, 1),
(807, 2, '2022-09-15', NULL, 1, 2, 30, 1),
(808, 1, '2022-09-15', NULL, 7, 2, 496, 1),
(809, 1, '2022-09-15', NULL, 7, 2, 496, 1),
(810, 1, '2022-09-16', NULL, 7, 2, 496, 1),
(811, 2, '2022-09-19', NULL, 1, 2, 30, 1),
(812, 2, '2022-09-19', NULL, 7, 2, 30, 1),
(813, 2, '2022-09-20', NULL, 1, 2, 30, 1),
(814, 1, '2022-09-20', NULL, 7, 2, 497, 1),
(815, 1, '2022-09-20', NULL, 3, 2, 576, 1),
(816, 1, '2022-09-20', NULL, 3, 2, 576, 1),
(817, 4, '2022-09-20', NULL, 5, 1, 680, 1),
(818, 1, '2022-09-21', NULL, 7, 2, 497, 1),
(819, 4, '2022-09-21', NULL, 5, 2, 680, 1),
(820, 5, '2022-09-23', NULL, 1, 3, 685, 1),
(821, 4, '2022-09-23', NULL, 5, 1, 680, 1),
(822, 2, '2022-09-26', NULL, 7, 1, 384, 1),
(823, 5, '2022-09-27', NULL, 1, 3, 685, 1),
(824, 2, '2022-10-06', NULL, 7, 2, 18, 1),
(825, 4, '2022-10-06', NULL, 5, 2, 680, 1),
(826, 4, '2022-10-06', NULL, 5, 2, 680, 1),
(827, 1, '2022-10-06', NULL, 7, 1, 576, 1),
(828, 1, '2022-10-06', NULL, 1, 2, 497, 1),
(829, 1, '2022-10-06', NULL, 3, 2, 576, 1),
(830, 1, '2022-10-06', NULL, 3, 2, 576, 1),
(831, 5, '2022-10-07', NULL, 7, 3, 692, 1),
(832, 1, '2022-10-10', NULL, 1, 2, 497, 1),
(833, 1, '2022-10-10', NULL, 3, 2, 576, 1),
(834, 1, '2022-10-10', NULL, 3, 2, 576, 1),
(835, 4, '2022-10-11', NULL, 7, 2, 679, 1),
(836, 6, '2022-10-11', NULL, 5, 1, 695, 1),
(837, 1, '2022-10-11', NULL, 1, 1, 500, 1),
(838, 1, '2022-10-11', NULL, 1, 1, 500, 1),
(839, 4, '2022-10-11', NULL, 7, 2, 679, 1),
(840, 1, '2022-10-19', NULL, 1, 2, 497, 1),
(841, 1, '2022-10-19', NULL, 3, 2, 576, 1),
(842, 1, '2022-10-19', NULL, 3, 2, 576, 1),
(843, 2, '2022-10-20', NULL, 5, 2, 486, 1),
(844, 5, '2022-10-20', NULL, 7, 1, 692, 1),
(845, 2, '2022-10-20', NULL, 1, 2, 30, 1),
(846, 1, '2022-10-20', NULL, 7, 2, 578, 2),
(847, 1, '2022-10-20', NULL, 7, 2, 578, 2),
(848, 4, '2022-10-24', NULL, 1, 2, 677, 1),
(849, 1, '2022-11-08', NULL, 3, 2, 578, 2),
(850, 1, '2022-11-10', NULL, 1, 2, 497, 1),
(851, 1, '2022-11-10', NULL, 3, 2, 576, 1),
(852, 1, '2022-11-10', NULL, 3, 2, 576, 1),
(853, 1, '2022-11-11', NULL, 1, 2, 497, 1),
(854, 1, '2022-11-11', NULL, 3, 2, 576, 1),
(855, 1, '2022-11-11', NULL, 3, 2, 576, 1),
(856, 4, '2022-11-12', NULL, 5, 1, 680, 1),
(857, 5, '2022-11-12', NULL, 3, 1, 692, 1),
(858, 5, '2022-11-12', NULL, 3, 1, 692, 1),
(859, 2, '2022-11-14', NULL, 3, 2, 378, 1),
(860, 4, '2022-11-16', NULL, 7, 2, 679, 1),
(861, 2, '2022-11-16', NULL, 5, 2, 486, 1),
(862, 2, '2022-11-16', NULL, 5, 2, 486, 1),
(863, 1, '2022-11-29', NULL, 1, 1, 498, 1),
(864, 1, '2022-12-01', NULL, 7, 1, 596, 1),
(865, 1, '2022-12-01', NULL, 1, 1, 496, 1),
(866, 6, '2022-12-01', NULL, 3, 3, 694, 1),
(867, 1, '2022-12-02', NULL, 1, 1, 496, 1),
(868, 4, '2022-12-02', NULL, 3, 1, 679, 1),
(869, 1, '2022-12-12', NULL, 1, 1, 497, 1),
(870, 2, '2022-12-21', NULL, 3, 1, 18, 1),
(871, 1, '2022-12-21', NULL, 1, 1, 497, 1),
(872, 2, '2022-12-22', NULL, 7, 1, 18, 1),
(873, 3, '2022-12-22', NULL, 1, 2, 608, 1),
(874, 4, '2022-12-22', NULL, 3, 2, 679, 1),
(875, 4, '2022-12-22', NULL, 3, 2, 679, 1),
(876, 1, '2022-12-22', NULL, 5, 1, 596, 1),
(877, 4, '2022-12-23', NULL, 1, 1, 676, 1),
(878, 1, '2022-12-23', NULL, 1, 3, 496, 1),
(879, 1, '2022-12-26', NULL, 3, 1, 597, 2),
(880, 5, '2022-12-26', NULL, 1, 1, 683, 1),
(881, 4, '2022-12-26', NULL, 1, 1, 677, 1),
(882, 2, '2022-12-26', NULL, 1, 1, 21, 1),
(883, 2, '2022-12-28', NULL, 1, 1, 20, 1),
(884, 1, '2023-01-03', NULL, 7, 1, 576, 1),
(885, 3, '2023-01-03', NULL, 5, 2, 637, 2),
(886, 2, '2023-01-03', NULL, 5, 1, 486, 1),
(887, 2, '2023-01-03', NULL, 5, 1, 486, 1),
(888, 3, '2023-01-03', NULL, 5, 2, 637, 2),
(889, 1, '2023-01-03', NULL, 7, 1, 576, 1),
(890, 2, '2023-01-03', NULL, 5, 1, 486, 1),
(891, 3, '2023-01-03', NULL, 1, 2, 612, 1),
(892, 2, '2023-01-03', NULL, 5, 1, 486, 1),
(893, 3, '2023-01-03', NULL, 1, 2, 612, 1),
(894, 1, '2023-01-03', NULL, 1, 1, 496, 1),
(895, 1, '2023-01-03', NULL, 7, 1, 576, 1),
(896, 3, '2023-01-03', NULL, 5, 2, 637, 2),
(897, 2, '2023-01-03', NULL, 1, 2, 45, 1),
(898, 2, '2023-01-03', NULL, 1, 2, 45, 1),
(899, 1, '2023-01-03', NULL, 7, 1, 576, 1),
(900, 1, '2023-01-04', NULL, 1, 1, 496, 1),
(901, 5, '2023-01-04', NULL, 7, 3, 692, 1),
(902, 1, '2023-01-17', NULL, 1, 1, 497, 1),
(903, 5, '2023-01-17', NULL, 5, 3, 693, 1),
(904, 2, '2023-01-17', NULL, 3, 4, 378, 1),
(905, 1, '2023-01-17', NULL, 1, 1, 497, 1),
(906, 5, '2023-01-17', NULL, 5, 3, 693, 1),
(907, 2, '2023-01-17', NULL, 3, 4, 378, 1),
(908, 1, '2023-01-18', NULL, 5, 2, 597, 2),
(909, 5, '2023-01-18', NULL, 1, 3, 681, 1),
(910, 4, '2023-01-18', NULL, 3, 1, 679, 1),
(911, 1, '2023-01-18', NULL, 5, 2, 597, 2),
(912, 5, '2023-01-18', NULL, 1, 3, 681, 1),
(913, 4, '2023-01-18', NULL, 3, 1, 679, 1),
(918, 1, '2023-01-18', NULL, 5, 2, 597, 2),
(919, 5, '2023-01-18', NULL, 1, 3, 681, 1),
(920, 4, '2023-01-18', NULL, 3, 1, 679, 1),
(921, 1, '2023-01-18', NULL, 5, 2, 597, 2),
(922, 1, '2023-01-18', NULL, 5, 2, 597, 2),
(923, 5, '2023-01-18', NULL, 1, 3, 681, 1),
(924, 4, '2023-01-18', NULL, 3, 1, 679, 1),
(925, 1, '2023-01-18', NULL, 5, 2, 597, 2),
(926, 1, '2023-01-18', NULL, 5, 2, 597, 2),
(927, 5, '2023-01-18', NULL, 1, 3, 681, 1),
(928, 4, '2023-01-18', NULL, 3, 1, 679, 1),
(929, 1, '2023-01-18', NULL, 5, 2, 597, 2),
(930, 1, '2023-01-18', NULL, 5, 2, 597, 2),
(931, 5, '2023-01-18', NULL, 1, 3, 681, 1),
(932, 4, '2023-01-18', NULL, 3, 1, 679, 1),
(933, 1, '2023-01-18', NULL, 5, 2, 597, 2);

-- --------------------------------------------------------

--
-- Structure de la table `tva`
--

DROP TABLE IF EXISTS `tva`;
CREATE TABLE IF NOT EXISTS `tva` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `pays_facturation_id` int(11) NOT NULL,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_EF699620899CF741` (`pays_facturation_id`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `tva`
--

INSERT INTO `tva` (`id`, `pays_facturation_id`, `libelle`) VALUES
(1, 1, '20%'),
(2, 3, '20%'),
(3, 2, '19%'),
(4, 4, '20%');

-- --------------------------------------------------------

--
-- Structure de la table `type_document`
--

DROP TABLE IF EXISTS `type_document`;
CREATE TABLE IF NOT EXISTS `type_document` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_document`
--

INSERT INTO `type_document` (`id`, `libelle`) VALUES
(1, 'Contrat'),
(2, 'Avenant'),
(3, 'Ordre de mission'),
(4, 'Cahier de charge'),
(5, 'Cas client');

-- --------------------------------------------------------

--
-- Structure de la table `type_facturation`
--

DROP TABLE IF EXISTS `type_facturation`;
CREATE TABLE IF NOT EXISTS `type_facturation` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `libelle` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `type_facturation`
--

INSERT INTO `type_facturation` (`id`, `libelle`) VALUES
(1, 'Acte'),
(2, 'A déterminer'),
(3, 'A l\'heure'),
(4, 'En régie Forfaitaire'),
(5, 'Forfait'),
(6, 'Jours/Homme'),
(7, 'Mixte (Heure/Acte)');

-- --------------------------------------------------------

--
-- Structure de la table `user`
--

DROP TABLE IF EXISTS `user`;
CREATE TABLE IF NOT EXISTS `user` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `email` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `roles` longtext COLLATE utf8mb4_unicode_ci NOT NULL COMMENT '(DC2Type:json)',
  `username` varchar(180) COLLATE utf8mb4_unicode_ci NOT NULL,
  `parent_id` int(11) NOT NULL,
  `tel` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `pays_production_id` int(11) DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `UNIQ_8D93D649E7927C74` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=22 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `user`
--

INSERT INTO `user` (`id`, `email`, `password`, `roles`, `username`, `parent_id`, `tel`, `pays_production_id`) VALUES
(1, 'test@gmail.com', '$2y$13$07rUpYRWUWFAmpgPMeABEenII7k9KZTzctU3zGqIE1vWGaWKnuBZ.', '[\"ROLE_USER\"]', 'test', 0, NULL, NULL),
(2, 'telmestour@outsourcia-group.com', '$2y$13$ix0e.8iQiKdrwJbBz4hj8.plP1k.asVVN374rkaWKdpoG2GOh5zWW', '[\"ROLE_USER\"]', 'Tarek Telmestour', 3, NULL, NULL),
(3, 'snrakotoarivony@outsourcia-group.com', '$2y$13$P0okxOwk6c0rRMh6dmP/HeW17m3uAn5W6aTCmNeSK8aUUgXUOKnGK', '[\"ROLE_USER\"]', 'Nirina Rak N+1', 0, NULL, NULL),
(4, 'solofoniaina2001@yahoo.fr', '$2y$13$vPQFZW3/7AaSfJedmBCR0.Iq6D03FfxDmpX/13AcrvMYgIQcyxP5W', '[\"ROLE_FINANCE\"]', 'solofoniaina2001', 0, NULL, NULL),
(6, 'dirfinoutsourcia2022@gmail.com', '$2y$13$c4FKTt56qYUvrgMvaok.ueSh6YZ0yqdC3o0x8RplCIuHfePq1w29u', '[\"ROLE_FINANCE\"]', 'Service Financier', 2, NULL, NULL),
(7, 'dgoutsourcia2022@gmail.com', '$2y$13$5gG/mlwh7zB18CndlYNrWunozdtvxkVJS8cAkdvV3b6pcYjDJsXmO', '[\"ROLE_DG\"]', 'Directeur Général', 2, NULL, NULL),
(8, 'comoutsourcia2022@gmail.com', '$2y$13$7zyAGAYwNjdhVGT3PIwzw.Zs8Qy8dC0Cj6J42.fgx3e4yIei2Rw1.', '[\"ROLE_USER\"]', 'Anatole BALLEY', 2, '+33 (0)6 29 99 92 99', NULL),
(9, 'dirprodoutsourciafrance2022@gmail.com', '$2y$13$.YHsugaiXJ/FDLDrvORiY.wq4fMWe19E1TUvtCPKFdsQzRFbdvb52', '[\"ROLE_DIRPROD\"]', 'DirProd France', 2, NULL, 1),
(10, 'dirprodoutsourciamada2022@gmail.com', '$2y$13$wsSvzP5xAG766cerJQlub.abhLHXEMoKv7NQgCcnuKgXnjiKjtSue', '[\"ROLE_DIRPROD\"]', 'DirProd Mada', 2, NULL, 3),
(11, 'dirprodoutsourcianiger2022@gmail.com', '$2y$13$nOTse4UITtgMdHuD1Y3eeOMdAWYRArR4AJLLreaZDszpwDqc.SC5W', '[\"ROLE_DIRPROD\"]', 'DirProd Niger', 2, NULL, 4),
(12, 'backupdirprodoutsourcia2022@gmail.com', '$2y$13$06rEpag2jYu1ah.ud09/c.OR4PWB.Ua1xR8S0/Ojuc7LtIp2SWH3S', '[\"ROLE_DIRPROD\"]', 'Backup DirProdMaroc', 2, NULL, 2),
(13, 'backupdirprodoutsourciafr2022@gmail.com', '$2y$13$sljF2oDoXTX8g3NeksJ4YOgJRlG59WZHzzrGTCSBHBDjJPt2Qn66G', '[\"ROLE_DIRPROD\"]', 'Backup DirProdFrance', 2, NULL, 1),
(14, 'backupdirprodoutsourciamg2022@gmail.com', '$2y$13$UHCEpPff35kpCqKnu7IZgOwMOEh4FMWTUpQ5zSongRDUL/9H7Mxty', '[\"ROLE_DIRPROD\"]', 'Backup DirProdMada', 2, NULL, 3),
(15, 'backupdirprodoutsourcianiger2022@gmail.com', '$2y$13$uEVfTF3.2UdKcjZrFJbuY.9mLYGnX1TVQ9cIryK6pK78LdHACOK0.', '[\"ROLE_DIRPROD\"]', 'Backup DirProdNiger', 2, NULL, 4),
(16, 'backupdirfinoutsourcia2022@gmail.com', '$2y$13$mPl/ZlkwIPRiNNOulOuppuilY7vaQ9WRdQyjSzTR/v1n1nwfmyvn2', '[\"ROLE_FINANCE\"]', 'Backup DirFin', 2, NULL, NULL),
(17, 'backupdgoutsourcia2022@gmail.com', '$2y$13$vNjlmocy3HpLEpTJs/4uTeYpgwDPPliz/xCo76Yw1GF.J/fjESh9e', '[\"ROLE_DG\"]', 'Backup DirDG', 2, NULL, NULL),
(18, 'dirgestcompteoutsourcia2022@gmail.com', '$2y$13$wK6iWra.WDHWzOGy/WsUV.Wx.xmP6FYEI9pO8P7agjk.wttUo/WmO', '[\"ROLE_DIRGESTCOMPTE\"]', 'Dir Gest Compte', 2, NULL, NULL),
(19, 'juliodimbinirina@gmail.com', '$2y$13$14XHNl53T1jEcf57XBdIkePAY0zDxfxIT79FjrwL713mm5ngCNszK', '[\"ROLE_USER\"]', 'Rene Julio', 2, NULL, NULL),
(20, 'juristeoutsourcia2022@gmail.com', '$2y$13$nHbQGlgAtKHbWvBrQptaTeMmpn4zxzLuZoNYzYq9j2gncZAk2W16W', '[\"ROLE_JURISTE\"]', 'Juriste account', 2, NULL, NULL),
(21, 'dirprodoutsourcia2022@gmail.com', '$2y$13$oN20f0Q3o0fG.vogrv1ISelg8PWriEh74PLTaOo/ENBd4.xPTZDNC', '[\"ROLE_DIRPROD\"]', 'Directeur Prod', 2, NULL, 2);

-- --------------------------------------------------------

--
-- Structure de la table `workflow_lead`
--

DROP TABLE IF EXISTS `workflow_lead`;
CREATE TABLE IF NOT EXISTS `workflow_lead` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` int(11) NOT NULL,
  `statut` int(11) NOT NULL,
  `date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_6D0E71F019EB6921` (`client_id`)
) ENGINE=InnoDB AUTO_INCREMENT=53 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Déchargement des données de la table `workflow_lead`
--

INSERT INTO `workflow_lead` (`id`, `client_id`, `statut`, `date`) VALUES
(1, 1, 1, '2023-01-26 07:29:59'),
(2, 1, 2, '2023-01-26 07:40:18'),
(3, 1, -1, '2023-01-26 07:45:24'),
(4, 1, -1, '2023-01-26 07:52:45'),
(5, 1, -1, '2023-01-26 11:15:38'),
(6, 1, 8, '2023-02-13 08:21:35'),
(7, 1, 8, '2023-02-13 08:29:33'),
(8, 1, 10, '2023-02-13 08:30:17'),
(9, 1, 12, '2023-02-13 08:32:56'),
(10, 1, 13, '2023-02-13 08:35:11'),
(11, 1, 2, '2023-02-17 07:38:11'),
(12, 1, -1, '2023-02-17 07:40:23'),
(13, 1, 4, '2023-02-17 08:03:28'),
(14, 1, 6, '2023-02-17 08:06:40'),
(15, 1, 8, '2023-02-17 08:08:29'),
(16, 1, 2, '2023-02-24 12:03:11'),
(17, 1, -1, '2023-02-24 12:05:51'),
(18, 1, -1, '2023-02-24 12:09:07'),
(19, 1, 2, '2023-03-07 11:13:06'),
(20, 1, -1, '2023-03-07 11:15:36'),
(21, 2, 1, '2023-03-07 11:25:19'),
(22, 2, 2, '2023-03-08 08:56:06'),
(23, 2, -1, '2023-03-08 08:57:58'),
(24, 2, 2, '2023-03-08 09:02:28'),
(25, 2, -1, '2023-03-08 09:04:24'),
(26, 2, 2, '2023-03-08 09:18:04'),
(27, 2, -1, '2023-03-08 09:19:44'),
(28, 2, -1, '2023-03-08 10:55:15'),
(29, 2, 2, '2023-03-08 11:01:48'),
(30, 2, -1, '2023-03-08 11:12:18'),
(31, 2, -1, '2023-03-08 11:29:12'),
(32, 2, -1, '2023-03-08 11:31:43'),
(33, 2, -1, '2023-03-08 11:33:32'),
(34, 2, 2, '2023-03-08 12:24:58'),
(35, 2, -1, '2023-03-08 13:02:14'),
(36, 2, -1, '2023-03-08 13:03:02'),
(37, 2, -1, '2023-03-08 13:03:33'),
(38, 2, 2, '2023-03-08 13:58:33'),
(39, 2, -1, '2023-03-08 14:00:38'),
(40, 2, -1, '2023-03-08 14:02:31'),
(41, 2, 13, '2023-03-09 12:21:18'),
(42, 2, 13, '2023-03-09 12:31:46'),
(43, 2, 13, '2023-03-09 12:38:08'),
(44, 2, 13, '2023-03-09 12:43:35'),
(45, 2, 13, '2023-03-09 12:50:42'),
(46, 2, 13, '2023-03-09 12:58:04'),
(47, 2, -1, '2023-03-09 13:01:42'),
(48, 2, -1, '2023-03-09 13:03:53'),
(49, 2, 13, '2023-03-09 13:54:03'),
(50, 2, 13, '2023-03-09 13:54:57'),
(51, 2, 13, '2023-03-09 13:56:07'),
(52, 2, -1, '2023-03-09 13:58:06');

--
-- Contraintes pour les tables déchargées
--

--
-- Contraintes pour la table `bdc`
--
ALTER TABLE `bdc`
  ADD CONSTRAINT `FK_6138581D3615FA65` FOREIGN KEY (`resume_lead_id`) REFERENCES `resume_lead` (`id`),
  ADD CONSTRAINT `FK_6138581D4D79775F` FOREIGN KEY (`tva_id`) REFERENCES `tva` (`id`),
  ADD CONSTRAINT `FK_6138581D899CF741` FOREIGN KEY (`pays_facturation_id`) REFERENCES `pays_facturation` (`id`),
  ADD CONSTRAINT `FK_6138581DB845FCE3` FOREIGN KEY (`statut_client_id`) REFERENCES `statut_client` (`id`),
  ADD CONSTRAINT `FK_6138581DDD21E7CC` FOREIGN KEY (`pays_production_id`) REFERENCES `pays_production` (`id`),
  ADD CONSTRAINT `FK_6138581DE7D306A2` FOREIGN KEY (`societe_facturation_id`) REFERENCES `societe_facturation` (`id`),
  ADD CONSTRAINT `FK_6138581DF4445056` FOREIGN KEY (`devise_id`) REFERENCES `devise` (`id`);

--
-- Contraintes pour la table `bdc_document`
--
ALTER TABLE `bdc_document`
  ADD CONSTRAINT `FK_75C1B58928DF9AB0` FOREIGN KEY (`bdc_id`) REFERENCES `bdc` (`id`),
  ADD CONSTRAINT `FK_75C1B5898826AFA6` FOREIGN KEY (`type_document_id`) REFERENCES `type_document` (`id`);

--
-- Contraintes pour la table `bdc_operation`
--
ALTER TABLE `bdc_operation`
  ADD CONSTRAINT `FK_342EE1DF28DF9AB0` FOREIGN KEY (`bdc_id`) REFERENCES `bdc` (`id`),
  ADD CONSTRAINT `FK_342EE1DF357C0A59` FOREIGN KEY (`tarif_id`) REFERENCES `tarif` (`id`),
  ADD CONSTRAINT `FK_342EE1DF44AC3583` FOREIGN KEY (`operation_id`) REFERENCES `operation` (`id`),
  ADD CONSTRAINT `FK_342EE1DF800DC1FD` FOREIGN KEY (`langue_trt_id`) REFERENCES `langue_trt` (`id`),
  ADD CONSTRAINT `FK_342EE1DF8D06DB10` FOREIGN KEY (`type_facturation_id`) REFERENCES `type_facturation` (`id`),
  ADD CONSTRAINT `FK_342EE1DF8E47663F` FOREIGN KEY (`cout_horaire_id`) REFERENCES `cout_horaire` (`id`),
  ADD CONSTRAINT `FK_342EE1DF9EFA7AED` FOREIGN KEY (`famille_operation_id`) REFERENCES `famille_operation` (`id`),
  ADD CONSTRAINT `FK_342EE1DFCB40E30D` FOREIGN KEY (`designation_acte_id`) REFERENCES `operation` (`id`),
  ADD CONSTRAINT `FK_342EE1DFE0319FBC` FOREIGN KEY (`bu_id`) REFERENCES `bu` (`id`);

--
-- Contraintes pour la table `bdc_operation_objectif_qualitatif`
--
ALTER TABLE `bdc_operation_objectif_qualitatif`
  ADD CONSTRAINT `FK_9115D9877B27786B` FOREIGN KEY (`bdc_operation_id`) REFERENCES `bdc_operation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_9115D98784C558C9` FOREIGN KEY (`objectif_qualitatif_id`) REFERENCES `objectif_qualitatif` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `bdc_operation_objectif_quantitatif`
--
ALTER TABLE `bdc_operation_objectif_quantitatif`
  ADD CONSTRAINT `FK_A25B503F7B27786B` FOREIGN KEY (`bdc_operation_id`) REFERENCES `bdc_operation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A25B503F899CB9C9` FOREIGN KEY (`objectif_quantitatif_id`) REFERENCES `objectif_quantitatif` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `client_document`
--
ALTER TABLE `client_document`
  ADD CONSTRAINT `FK_F68FBAB38826AFA6` FOREIGN KEY (`type_document_id`) REFERENCES `type_document` (`id`),
  ADD CONSTRAINT `FK_F68FBAB39395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Contraintes pour la table `contact`
--
ALTER TABLE `contact`
  ADD CONSTRAINT `FK_4C62E6389395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Contraintes pour la table `contact_has_profil_contact`
--
ALTER TABLE `contact_has_profil_contact`
  ADD CONSTRAINT `FK_A248224CDC677EB4` FOREIGN KEY (`profil_contact_id`) REFERENCES `profil_contact` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_A248224CE7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `coordone_bancaire`
--
ALTER TABLE `coordone_bancaire`
  ADD CONSTRAINT `FK_F8A9344AE7D306A2` FOREIGN KEY (`societe_facturation_id`) REFERENCES `societe_facturation` (`id`);

--
-- Contraintes pour la table `customer`
--
ALTER TABLE `customer`
  ADD CONSTRAINT `FK_81398E0955B93C0F` FOREIGN KEY (`mapping_client_id`) REFERENCES `mapping_client` (`id`),
  ADD CONSTRAINT `FK_81398E095BBD1224` FOREIGN KEY (`adresse_facturation_id`) REFERENCES `adresse_facturation` (`id`),
  ADD CONSTRAINT `FK_81398E09A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`),
  ADD CONSTRAINT `FK_81398E09B4B46626` FOREIGN KEY (`categorie_client_id`) REFERENCES `categorie_client` (`id`);

--
-- Contraintes pour la table `devise`
--
ALTER TABLE `devise`
  ADD CONSTRAINT `FK_43EDA4DF899CF741` FOREIGN KEY (`pays_facturation_id`) REFERENCES `pays_facturation` (`id`);

--
-- Contraintes pour la table `fiche_client`
--
ALTER TABLE `fiche_client`
  ADD CONSTRAINT `FK_7158A9821E4CCA8D` FOREIGN KEY (`nature_prestation_id`) REFERENCES `nature_prestation` (`id`),
  ADD CONSTRAINT `FK_7158A9829395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Contraintes pour la table `historique`
--
ALTER TABLE `historique`
  ADD CONSTRAINT `FK_EDBFD5ECE7A1254A` FOREIGN KEY (`contact_id`) REFERENCES `contact` (`id`);

--
-- Contraintes pour la table `indicator_qualitatif`
--
ALTER TABLE `indicator_qualitatif`
  ADD CONSTRAINT `FK_DB5AD6F553EF6646` FOREIGN KEY (`lead_detail_operation_id`) REFERENCES `lead_detail_operation` (`id`),
  ADD CONSTRAINT `FK_DB5AD6F57B27786B` FOREIGN KEY (`bdc_operation_id`) REFERENCES `bdc_operation` (`id`),
  ADD CONSTRAINT `FK_DB5AD6F584C558C9` FOREIGN KEY (`objectif_qualitatif_id`) REFERENCES `objectif_qualitatif` (`id`);

--
-- Contraintes pour la table `indicator_quantitatif`
--
ALTER TABLE `indicator_quantitatif`
  ADD CONSTRAINT `FK_1C1A0F2053EF6646` FOREIGN KEY (`lead_detail_operation_id`) REFERENCES `lead_detail_operation` (`id`),
  ADD CONSTRAINT `FK_1C1A0F207B27786B` FOREIGN KEY (`bdc_operation_id`) REFERENCES `bdc_operation` (`id`),
  ADD CONSTRAINT `FK_1C1A0F20899CB9C9` FOREIGN KEY (`objectif_quantitatif_id`) REFERENCES `objectif_quantitatif` (`id`);

--
-- Contraintes pour la table `lead_detail_operation`
--
ALTER TABLE `lead_detail_operation`
  ADD CONSTRAINT `FK_29FD728E3615FA65` FOREIGN KEY (`resume_lead_id`) REFERENCES `resume_lead` (`id`),
  ADD CONSTRAINT `FK_29FD728E44AC3583` FOREIGN KEY (`operation_id`) REFERENCES `operation` (`id`),
  ADD CONSTRAINT `FK_29FD728E800DC1FD` FOREIGN KEY (`langue_trt_id`) REFERENCES `langue_trt` (`id`),
  ADD CONSTRAINT `FK_29FD728E899CF741` FOREIGN KEY (`pays_facturation_id`) REFERENCES `pays_facturation` (`id`),
  ADD CONSTRAINT `FK_29FD728E8D06DB10` FOREIGN KEY (`type_facturation_id`) REFERENCES `type_facturation` (`id`),
  ADD CONSTRAINT `FK_29FD728E8E47663F` FOREIGN KEY (`cout_horaire_id`) REFERENCES `cout_horaire` (`id`),
  ADD CONSTRAINT `FK_29FD728E9EFA7AED` FOREIGN KEY (`famille_operation_id`) REFERENCES `famille_operation` (`id`),
  ADD CONSTRAINT `FK_29FD728ECB40E30D` FOREIGN KEY (`designation_acte_id`) REFERENCES `operation` (`id`),
  ADD CONSTRAINT `FK_29FD728EDD21E7CC` FOREIGN KEY (`pays_production_id`) REFERENCES `pays_production` (`id`),
  ADD CONSTRAINT `FK_29FD728EE0319FBC` FOREIGN KEY (`bu_id`) REFERENCES `bu` (`id`),
  ADD CONSTRAINT `FK_29FD728EF9A66EC9` FOREIGN KEY (`horaire_production_id`) REFERENCES `horaire_production` (`id`);

--
-- Contraintes pour la table `lead_detail_operation_objectif_qualitatif`
--
ALTER TABLE `lead_detail_operation_objectif_qualitatif`
  ADD CONSTRAINT `FK_DE65A1F553EF6646` FOREIGN KEY (`lead_detail_operation_id`) REFERENCES `lead_detail_operation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_DE65A1F584C558C9` FOREIGN KEY (`objectif_qualitatif_id`) REFERENCES `objectif_qualitatif` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `lead_detail_operation_objectif_quantitatif`
--
ALTER TABLE `lead_detail_operation_objectif_quantitatif`
  ADD CONSTRAINT `FK_1C1F305753EF6646` FOREIGN KEY (`lead_detail_operation_id`) REFERENCES `lead_detail_operation` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_1C1F3057899CB9C9` FOREIGN KEY (`objectif_quantitatif_id`) REFERENCES `objectif_quantitatif` (`id`) ON DELETE CASCADE;

--
-- Contraintes pour la table `operation`
--
ALTER TABLE `operation`
  ADD CONSTRAINT `FK_1981A66D9EFA7AED` FOREIGN KEY (`famille_operation_id`) REFERENCES `famille_operation` (`id`);

--
-- Contraintes pour la table `reject_bdc`
--
ALTER TABLE `reject_bdc`
  ADD CONSTRAINT `FK_1042830928DF9AB0` FOREIGN KEY (`bdc_id`) REFERENCES `bdc` (`id`);

--
-- Contraintes pour la table `resume_lead`
--
ALTER TABLE `resume_lead`
  ADD CONSTRAINT `FK_D657A00F18D61EF7` FOREIGN KEY (`duree_trt_id`) REFERENCES `duree_trt` (`id`),
  ADD CONSTRAINT `FK_D657A00F9395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`),
  ADD CONSTRAINT `FK_D657A00FB0738B00` FOREIGN KEY (`potentiel_transformation_id`) REFERENCES `potentiel_transformation` (`id`),
  ADD CONSTRAINT `FK_D657A00FB52250E0` FOREIGN KEY (`origin_lead_id`) REFERENCES `origin_lead` (`id`);

--
-- Contraintes pour la table `societe_facturation`
--
ALTER TABLE `societe_facturation`
  ADD CONSTRAINT `FK_774486CB899CF741` FOREIGN KEY (`pays_facturation_id`) REFERENCES `pays_facturation` (`id`);

--
-- Contraintes pour la table `status_lead`
--
ALTER TABLE `status_lead`
  ADD CONSTRAINT `FK_1097CC5F9395C3F3` FOREIGN KEY (`customer_id`) REFERENCES `customer` (`id`);

--
-- Contraintes pour la table `suite_process`
--
ALTER TABLE `suite_process`
  ADD CONSTRAINT `FK_E11BCF1A28DF9AB0` FOREIGN KEY (`bdc_id`) REFERENCES `bdc` (`id`);

--
-- Contraintes pour la table `tarif`
--
ALTER TABLE `tarif`
  ADD CONSTRAINT `FK_E7189C944AC3583` FOREIGN KEY (`operation_id`) REFERENCES `operation` (`id`),
  ADD CONSTRAINT `FK_E7189C98D06DB10` FOREIGN KEY (`type_facturation_id`) REFERENCES `type_facturation` (`id`),
  ADD CONSTRAINT `FK_E7189C995D655BB` FOREIGN KEY (`langue_traitement_id`) REFERENCES `langue_trt` (`id`),
  ADD CONSTRAINT `FK_E7189C9DD21E7CC` FOREIGN KEY (`pays_production_id`) REFERENCES `pays_production` (`id`),
  ADD CONSTRAINT `FK_E7189C9E0319FBC` FOREIGN KEY (`bu_id`) REFERENCES `bu` (`id`);

--
-- Contraintes pour la table `tva`
--
ALTER TABLE `tva`
  ADD CONSTRAINT `FK_EF699620899CF741` FOREIGN KEY (`pays_facturation_id`) REFERENCES `pays_facturation` (`id`);

--
-- Contraintes pour la table `workflow_lead`
--
ALTER TABLE `workflow_lead`
  ADD CONSTRAINT `FK_6D0E71F019EB6921` FOREIGN KEY (`client_id`) REFERENCES `customer` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
