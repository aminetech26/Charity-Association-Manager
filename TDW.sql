-- MySQL dump 10.13  Distrib 8.0.40, for Win64 (x86_64)
--
-- Host: 127.0.0.1    Database: tdw_db
-- ------------------------------------------------------
-- Server version	8.0.40

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!50503 SET NAMES utf8mb4 */;
/*!40103 SET @OLD_TIME_ZONE=@@TIME_ZONE */;
/*!40103 SET TIME_ZONE='+00:00' */;
/*!40014 SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;
/*!40111 SET @OLD_SQL_NOTES=@@SQL_NOTES, SQL_NOTES=0 */;

--
-- Table structure for table `abonnement`
--

DROP TABLE IF EXISTS `abonnement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `abonnement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `type_abonnement` enum('CLASSIQUE','JEUNE','PREMIUM') NOT NULL,
  `date_debut` date NOT NULL,
  `date_fin` date NOT NULL,
  `is_active` tinyint(1) NOT NULL DEFAULT '0',
  `recu_paiement` varchar(255) DEFAULT NULL,
  `statut` enum('EN_COURS','EXPIRE','RENOUVELE') NOT NULL DEFAULT 'EN_COURS',
  PRIMARY KEY (`id`),
  CONSTRAINT `check_dates_abonnement` CHECK ((`date_fin` > `date_debut`))
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `abonnement`
--

LOCK TABLES `abonnement` WRITE;
/*!40000 ALTER TABLE `abonnement` DISABLE KEYS */;
INSERT INTO `abonnement` VALUES (1,'PREMIUM','2025-01-16','2026-01-16',1,'../public/recus/images-2_6788dc4144503.jpeg','EN_COURS'),(2,'CLASSIQUE','2025-01-16','2026-01-16',1,'../public/recus/cheque-specimen_6788dca580750.jpg','EN_COURS'),(3,'CLASSIQUE','2025-01-16','2026-01-16',0,'../public/recus/005774ia57_6789157dc0a88.jpg','EN_COURS');
/*!40000 ALTER TABLE `abonnement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `benevolats`
--

DROP TABLE IF EXISTS `benevolats`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `benevolats` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compte_membre_id` int NOT NULL,
  `evenement_id` int NOT NULL,
  `statut` enum('EN_ATTENTE','VALIDE','REFUSE') NOT NULL DEFAULT 'EN_ATTENTE',
  PRIMARY KEY (`id`),
  KEY `fk_benevolat_evenement` (`evenement_id`),
  KEY `fk_benevolat_membre` (`compte_membre_id`),
  CONSTRAINT `fk_benevolat_evenement` FOREIGN KEY (`evenement_id`) REFERENCES `evenement` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_benevolat_membre` FOREIGN KEY (`compte_membre_id`) REFERENCES `compte_membre` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `benevolats`
--

LOCK TABLES `benevolats` WRITE;
/*!40000 ALTER TABLE `benevolats` DISABLE KEYS */;
INSERT INTO `benevolats` VALUES (3,2,2,'REFUSE'),(4,2,5,'EN_ATTENTE'),(5,2,3,'EN_ATTENTE');
/*!40000 ALTER TABLE `benevolats` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `categorie`
--

DROP TABLE IF EXISTS `categorie`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `categorie` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `categorie`
--

LOCK TABLES `categorie` WRITE;
/*!40000 ALTER TABLE `categorie` DISABLE KEYS */;
INSERT INTO `categorie` VALUES (4,'Agence de voyage'),(2,'Clinique'),(3,'Ecole'),(1,'Hotels');
/*!40000 ALTER TABLE `categorie` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compte_admin`
--

DROP TABLE IF EXISTS `compte_admin`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compte_admin` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom_user` varchar(100) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `role` enum('SUPER_ADMIN','ADMIN') NOT NULL DEFAULT 'ADMIN',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_admin_creator` (`created_by`),
  CONSTRAINT `fk_admin_creator` FOREIGN KEY (`created_by`) REFERENCES `compte_admin` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compte_admin`
--

LOCK TABLES `compte_admin` WRITE;
/*!40000 ALTER TABLE `compte_admin` DISABLE KEYS */;
INSERT INTO `compte_admin` VALUES (1,'admin','2025-01-16 08:24:22',1,'admin@tdw.com','$2y$10$hq9eB54Yniw2SfhVNELuQebv5VJx65aqLV04T4zn47dOCd82nOuEO','SUPER_ADMIN'),(2,'simple_admin','2025-01-16 14:21:02',1,'simple_admin@gmail.com','$2y$10$lV8tqthfM.4MmfOXn.HO7OVU/HOQyLXC2XaFUBH2etO3chkJY7.sG','ADMIN'),(3,'amir','2025-01-16 14:21:34',1,'amir@gmail.com','$2y$10$k0InM0wc24apV9npGrPSHuxEwg4dTG87ZBQOFKeWR4MWdSj0f7Juq','SUPER_ADMIN');
/*!40000 ALTER TABLE `compte_admin` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compte_membre`
--

DROP TABLE IF EXISTS `compte_membre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compte_membre` (
  `id` int NOT NULL AUTO_INCREMENT,
  `member_unique_id` varchar(50) DEFAULT '',
  `qr_code` varchar(255) DEFAULT NULL,
  `abonnement_id` int DEFAULT NULL,
  `photo` varchar(255) DEFAULT NULL,
  `nom` varchar(255) DEFAULT NULL,
  `prenom` varchar(255) DEFAULT NULL,
  `piece_identite` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `adresse` text NOT NULL,
  `numero_de_telephone` varchar(20) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `is_approved` tinyint(1) NOT NULL DEFAULT '0',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `member_unique_id_constraint` (`member_unique_id`),
  KEY `fk_membre_abonnement` (`abonnement_id`),
  CONSTRAINT `fk_membre_abonnement` FOREIGN KEY (`abonnement_id`) REFERENCES `abonnement` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compte_membre`
--

LOCK TABLES `compte_membre` WRITE;
/*!40000 ALTER TABLE `compte_membre` DISABLE KEYS */;
INSERT INTO `compte_membre` VALUES (2,'MEM-2025-00002','../public/qrcodes/MEM-2025-00002.png',2,'../public/photos/pngtree-cute-baby-girl-background-image_15708594_6788dca55e7b8.jpg','Larine','Meriem','../public/identites/carte-nationale_6788dca55eeaf.png','meriem@gmail.com','BT O9','0782806260','$2y$10$AjMvl56jZSLKBfcuAO8TZeeJShoyteGGpOeR5KbpMO.fp8yqDw.S.',1,'2025-01-16 10:17:09','2025-01-16 13:51:59'),(3,'MEM-2025-00003',NULL,3,'../public/photos/premium_photo-1682096259050-361e2989706d_6789157da8074.jpeg','TIROUCHE','Mohamed Mahdi','../public/identites/capture-decran-2024-12-03-212023_6789157da8906.png','mehdi@gmail.com','Ali Mendjeli','0796354801','$2y$10$UJ4OHWytRAyEbJnux/D0VuxIOsIk1HEiIaF3MckAzgi./95KlMEpS',0,'2025-01-16 14:19:41','2025-01-16 14:19:41'),(4,'MEM-2025-00004',NULL,NULL,'../public/photos/30618876-portrait-d-un-bel-homme-latin-avec-une-expression-serieuse_678916a26f85d.jpg','OGABI','Racim','../public/identites/capture-decran-2024-12-03-212023_678916a26ffc3.png','racim@gmail.com','Ali Mendjeli','0796354801','$2y$10$ZbUMqOYs..20XuyPP0pUSu2rO2CDyw0yCqPwBP3smv./arj9kNfXS',0,'2025-01-16 14:24:34','2025-01-16 14:24:34');
/*!40000 ALTER TABLE `compte_membre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `compte_partenaire`
--

DROP TABLE IF EXISTS `compte_partenaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `compte_partenaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partenaire_id` int NOT NULL,
  `email` varchar(255) NOT NULL,
  `mot_de_passe` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL,
  `statut` enum('ACTIVE','BLOCKED') NOT NULL DEFAULT 'ACTIVE',
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  KEY `fk_compte_partenaire` (`partenaire_id`),
  KEY `fk_compte_partenaire_creator` (`created_by`),
  CONSTRAINT `fk_compte_partenaire` FOREIGN KEY (`partenaire_id`) REFERENCES `partenaire` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_compte_partenaire_creator` FOREIGN KEY (`created_by`) REFERENCES `compte_admin` (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `compte_partenaire`
--

LOCK TABLES `compte_partenaire` WRITE;
/*!40000 ALTER TABLE `compte_partenaire` DISABLE KEYS */;
INSERT INTO `compte_partenaire` VALUES (1,1,'hotel-yasmine@gmail.com','$2y$10$Ym2OvMNw/TaJjfxQXg3aPubFDTz/yFxhB/lv85YRT3hQhIvli15rG','2025-01-16 09:01:24',1,'ACTIVE'),(2,2,'clinique-chifa@gmail.com','$2y$10$mJjN35IM87cRggoinjHW9O5f.QrRexMKoutWcm4MWICKCXaZSc6p6','2025-01-16 09:01:51',1,'ACTIVE'),(3,3,'ecole-future@gmail.com','$2y$10$rlZswBgR.aQx7FzPpEOREOrGSUjCPRk9b2UR9aEUJOHpbS5jiIOB6','2025-01-16 09:02:12',1,'ACTIVE');
/*!40000 ALTER TABLE `compte_partenaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `demande_aide`
--

DROP TABLE IF EXISTS `demande_aide`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `demande_aide` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `date_naissance` date NOT NULL,
  `type_aide` int NOT NULL,
  `description` text NOT NULL,
  `fichier_zip` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `statut` enum('accepté','refusé','en attente') NOT NULL DEFAULT 'en attente',
  `membre_id` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_demande_type_aide` (`type_aide`),
  KEY `fk_membre_id` (`membre_id`),
  CONSTRAINT `fk_demande_type_aide` FOREIGN KEY (`type_aide`) REFERENCES `type_aide` (`id`),
  CONSTRAINT `fk_membre_id` FOREIGN KEY (`membre_id`) REFERENCES `compte_membre` (`id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `demande_aide`
--

LOCK TABLES `demande_aide` WRITE;
/*!40000 ALTER TABLE `demande_aide` DISABLE KEYS */;
INSERT INTO `demande_aide` VALUES (2,'TIROUCHE','Mohamed Mahdi','2003-09-21',3,'Besoin de cours de soutien','../public/dossiers_aide/tdw-db-schema_6789118f6e1fe.rar','2025-01-16 15:02:55','en attente',2),(3,'BOUZEKRI','Amina','1985-01-16',1,'Je suis une mère célibataire avec trois enfants âgés de 4, 7 et 10 ans. Nous traversons une période difficile depuis que j’ai perdu mon emploi il y a six mois. Malgré mes efforts pour trouver un nouveau travail, nos besoins de base, notamment l’alimentation, ne sont pas assurés. Je sollicite votre aide pour bénéficier de paniers alimentaires afin de nourrir mes enfants. Toute contribution serait d’un immense soutien pour nous permettre de passer ce cap difficile.','../public/dossiers_aide/tdw-db-schema_67882637d6d27_6789170a4e481.rar','2025-01-16 15:26:18','en attente',2),(4,'KECHID','Rachid','1970-01-16',3,'Nous avons trois enfants en âge scolaire et peinons à acheter toutes les fournitures nécessaires pour leur rentrée. Entre les frais de cahiers, livres et vêtements scolaires, nos moyens financiers sont dépassés. Nos enfants rêvent de réussir leurs études, mais nous avons besoin d’aide pour leur offrir les outils indispensables à leur éducation. Nous espérons que votre association pourra nous soutenir dans ce projet.','../public/dossiers_aide/tdw-db-schema_67882637d6d27_6789173d11484.rar','2025-01-16 15:27:09','en attente',2);
/*!40000 ALTER TABLE `demande_aide` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `dons`
--

DROP TABLE IF EXISTS `dons`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `dons` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compte_membre_id` int DEFAULT NULL,
  `montant` decimal(10,2) NOT NULL,
  `recu_paiement` varchar(255) DEFAULT NULL,
  `date` date NOT NULL,
  `est_tracable` tinyint(1) NOT NULL DEFAULT '1',
  `statut` enum('EN_ATTENTE','VALIDE','REFUSE') NOT NULL DEFAULT 'EN_ATTENTE',
  PRIMARY KEY (`id`),
  KEY `fk_dons_membres` (`compte_membre_id`),
  CONSTRAINT `fk_dons_membres` FOREIGN KEY (`compte_membre_id`) REFERENCES `compte_membre` (`id`) ON DELETE CASCADE,
  CONSTRAINT `check_montant_positif` CHECK ((`montant` > 0))
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `dons`
--

LOCK TABLES `dons` WRITE;
/*!40000 ALTER TABLE `dons` DISABLE KEYS */;
INSERT INTO `dons` VALUES (2,NULL,200.00,'../public/recus_dons/cheque-specimen_6789030f39746.jpg','2025-01-16',0,'VALIDE'),(3,2,10000.00,'../public/recus_dons/images-2_6789123b39cf0.jpeg','2025-01-16',1,'EN_ATTENTE'),(4,2,25000.00,'../public/recus_dons/images-2_6789175b45e2a.jpeg','2025-01-16',1,'EN_ATTENTE'),(5,2,19000.00,'../public/recus_dons/cheque-specimen_67891768dd319.jpg','2025-01-16',1,'EN_ATTENTE');
/*!40000 ALTER TABLE `dons` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `evenement`
--

DROP TABLE IF EXISTS `evenement`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `evenement` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `description` text NOT NULL,
  `lieu` varchar(255) NOT NULL,
  `date_debut` timestamp NOT NULL,
  `date_fin` timestamp NOT NULL,
  PRIMARY KEY (`id`),
  CONSTRAINT `check_dates_evenement` CHECK ((`date_fin` > `date_debut`))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `evenement`
--

LOCK TABLES `evenement` WRITE;
/*!40000 ALTER TABLE `evenement` DISABLE KEYS */;
INSERT INTO `evenement` VALUES (1,'Collecte de vêtements pour l’hiver	','Une collecte de vêtements chauds pour les familles nécessiteuses avant l’arrivée de l’hiver. Apportez vos dons pour offrir chaleur et réconfort.	','Maison de la Culture, Oran	','2025-01-01 23:00:00','2025-07-01 23:00:00'),(2,'Marathon solidaire	','Un marathon caritatif pour récolter des fonds en faveur des enfants atteints de maladies chroniques. Participez ou soutenez en faisant un don.	','Parc Dounia, Alger	','2025-03-11 23:00:00','2025-03-14 23:00:00'),(3,'Journée santé pour tous	','Une journée dédiée à des consultations médicales gratuites, en partenariat avec des médecins et laboratoires locaux, pour les familles à faible revenu.	','Salle Polyvalente, Béjaïa	','2025-04-09 23:00:00','2025-04-10 23:00:00'),(4,'Plantation d’arbres pour l’avenir	','Une campagne de reboisement visant à planter 1 000 arbres pour lutter contre la désertification et promouvoir un environnement durable.	','Forêt de Yakouren, Tizi Ouzou	','2025-04-30 23:00:00','2025-05-02 23:00:00'),(5,'Iftar collectif pour les démunis	','Organisation d’un iftar collectif pendant le Ramadan, avec distribution de repas chauds aux sans-abri et familles dans le besoin.	','Grande Mosquée, Constantine	','2025-03-28 23:00:00','2025-04-01 23:00:00'),(6,'Campagne de don de sang	','Une campagne pour sensibiliser au don de sang et répondre aux besoins des hôpitaux. Chaque don peut sauver des vies.	','Hôpital El Chifa, Oran	','2025-05-19 23:00:00','2025-05-20 23:00:00');
/*!40000 ALTER TABLE `evenement` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groupe`
--

DROP TABLE IF EXISTS `groupe`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupe` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text,
  PRIMARY KEY (`id`),
  UNIQUE KEY `nom` (`nom`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupe`
--

LOCK TABLES `groupe` WRITE;
/*!40000 ALTER TABLE `groupe` DISABLE KEYS */;
/*!40000 ALTER TABLE `groupe` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groupe_membres`
--

DROP TABLE IF EXISTS `groupe_membres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupe_membres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `groupe_id` int NOT NULL,
  `compte_membre_id` int NOT NULL,
  `ajoute_a` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_membre_groupe` (`groupe_id`,`compte_membre_id`),
  KEY `fk_groupe_membres_membre` (`compte_membre_id`),
  CONSTRAINT `fk_groupe_membres_groupe` FOREIGN KEY (`groupe_id`) REFERENCES `groupe` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_groupe_membres_membre` FOREIGN KEY (`compte_membre_id`) REFERENCES `compte_membre` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupe_membres`
--

LOCK TABLES `groupe_membres` WRITE;
/*!40000 ALTER TABLE `groupe_membres` DISABLE KEYS */;
/*!40000 ALTER TABLE `groupe_membres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `groupe_offres`
--

DROP TABLE IF EXISTS `groupe_offres`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `groupe_offres` (
  `id` int NOT NULL AUTO_INCREMENT,
  `groupe_id` int NOT NULL,
  `offre_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_groupe_offre` (`groupe_id`,`offre_id`),
  KEY `fk_groupe_offres_offre` (`offre_id`),
  CONSTRAINT `fk_groupe_offres_groupe` FOREIGN KEY (`groupe_id`) REFERENCES `groupe` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_groupe_offres_offre` FOREIGN KEY (`offre_id`) REFERENCES `offre` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `groupe_offres`
--

LOCK TABLES `groupe_offres` WRITE;
/*!40000 ALTER TABLE `groupe_offres` DISABLE KEYS */;
/*!40000 ALTER TABLE `groupe_offres` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `news`
--

DROP TABLE IF EXISTS `news`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `news` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `thumbnail_url` varchar(255) DEFAULT NULL,
  `date_publication` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `news`
--

LOCK TABLES `news` WRITE;
/*!40000 ALTER TABLE `news` DISABLE KEYS */;
INSERT INTO `news` VALUES (1,'Offrir un Repas, Semer de l’Espoir','Chaque jour, des centaines de familles luttent pour subvenir à leurs besoins essentiels. À travers notre programme d\'aide alimentaire, nous distribuons des repas chauds et des paniers remplis de produits de première nécessité. Votre soutien nous permet de redonner le sourire à ces familles et de leur rappeler qu’elles ne sont pas seules.','../public/thumbnails/kjjs2d05d7fsdmmpcgs_6788c92616901.jpg','2025-01-16 07:53:58'),(2,'Une Éducation pour Tous','L’éducation est la clé d’un avenir meilleur. Grâce à notre initiative \"Sourire en classe\", nous fournissons des fournitures scolaires et finançons les frais de scolarité des enfants issus de familles modestes. Ensemble, nous investissons dans l’avenir en donnant aux enfants les outils nécessaires pour réussir.','../public/thumbnails/1684325475142_6788c94e7e2d5.png','2025-01-16 07:54:38'),(3,'L\'Aide Médicale Qui Sauve des Vies','Pour ceux qui n\'ont pas les moyens de se soigner, notre association joue un rôle crucial. Nous finançons des soins, des médicaments et des consultations médicales pour les personnes en situation précaire. Chaque don peut sauver une vie ou soulager une douleur.','../public/thumbnails/gettyimages-905637714-612x612_6788c9a282b2d.jpg','2025-01-16 07:56:02'),(4,'Un Toit Pour Recommencer','Un logement digne est essentiel pour retrouver la stabilité. Avec votre aide, nous contribuons à rénover des habitations insalubres et à payer des loyers pour les familles en difficulté. Offrir un toit, c\'est redonner espoir.\r\n','../public/thumbnails/images-1_6788c9c24a9d4.jpeg','2025-01-16 07:56:34'),(5,'Ramadan : Un Mois de Solidarité','Durant le Ramadan, la générosité est au cœur de nos actions. Chaque jour, nous organisons des iftars collectifs et distribuons des repas aux familles démunies. Votre soutien nous permet de partager des moments de joie et de solidarité.\r\n','../public/thumbnails/ramadan2024-maga-ftour-alger-centre_6788c9de81cba.jpg','2025-01-16 07:57:02'),(6,'Les Veuves et Orphelins, Notre Priorité','Les femmes veuves et les enfants orphelins font face à des défis quotidiens immenses. Nous leur offrons un soutien financier, matériel et moral pour qu’ils puissent surmonter les épreuves et retrouver un équilibre. Votre générosité fait la différence dans leur vie.\r\n','../public/thumbnails/veuve-mere-deces-deuil_6788ca06a8e97.jpg','2025-01-16 07:57:42'),(7,'Ramener de la Chaleur Pendant l’Hiver','Lorsque les températures baissent, les besoins augmentent. Notre programme hivernal vise à distribuer des vêtements chauds, des couvertures et des chaussures aux personnes vulnérables. Ensemble, nous pouvons les aider à traverser cette période difficile.\r\n','../public/thumbnails/clothes-food-donation-volunteer-collecting-260nw-2171719883_6788ca2f02753.webp','2025-01-16 07:58:23');
/*!40000 ALTER TABLE `news` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `notification`
--

DROP TABLE IF EXISTS `notification`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `notification` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(255) NOT NULL,
  `contenu` text NOT NULL,
  `date_envoi` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `is_sent` tinyint(1) NOT NULL DEFAULT '0',
  `groupe_cible` int DEFAULT NULL,
  `type` enum('EVENEMENT','PROMOTION','NOUVELLE_OFFRE','RAPPEL','AUTRE') NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `created_by` int NOT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_notification_creator` (`created_by`),
  KEY `fk_notification_groupe` (`groupe_cible`),
  CONSTRAINT `fk_notification_creator` FOREIGN KEY (`created_by`) REFERENCES `compte_admin` (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `notification`
--

LOCK TABLES `notification` WRITE;
/*!40000 ALTER TABLE `notification` DISABLE KEYS */;
/*!40000 ALTER TABLE `notification` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `offre`
--

DROP TABLE IF EXISTS `offre`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `offre` (
  `id` int NOT NULL AUTO_INCREMENT,
  `partenaire_id` int NOT NULL,
  `type_offre` enum('CLASSIQUE','JEUNE','PREMIUM') NOT NULL,
  `valeur` decimal(10,2) NOT NULL,
  `description` text NOT NULL,
  `date_debut` date DEFAULT NULL,
  `date_fin` date DEFAULT NULL,
  `is_special` tinyint(1) NOT NULL DEFAULT '0',
  `thumbnail_path` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_offre_partenaire` (`partenaire_id`),
  CONSTRAINT `fk_offre_partenaire` FOREIGN KEY (`partenaire_id`) REFERENCES `partenaire` (`id`) ON DELETE CASCADE,
  CONSTRAINT `check_dates_offre` CHECK ((`date_fin` > `date_debut`)),
  CONSTRAINT `check_valeur_positive` CHECK ((`valeur` >= 0))
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `offre`
--

LOCK TABLES `offre` WRITE;
/*!40000 ALTER TABLE `offre` DISABLE KEYS */;
INSERT INTO `offre` VALUES (1,1,'CLASSIQUE',10.00,'Une réduction de 10 % sur les services ou produits proposés par le partenaire.\r\n','2025-01-16','2026-01-16',0,NULL),(2,1,'PREMIUM',25.00,'Des places gratuites ou des accès VIP à des événements organisés par le partenaire (concerts, conférences, etc.).\r\nDes bons d\'achat utilisables chez le partenaire pour tout achat ou service.','2025-01-16','2026-01-17',0,NULL),(3,2,'PREMIUM',50.00,'Des places gratuites ou des accès VIP à des événements organisés par le partenaire (concerts, conférences, etc.).\r\n','2025-01-16','2026-01-17',1,'../public/thumbnails/offre-speciale-creation-banniere-vente-creative_1017-16284_6788ced8acde0.avif'),(4,3,'CLASSIQUE',25.00,'Des bons d\'achat utilisables chez le partenaire pour tout achat ou service.\r\n','2025-01-06','2026-01-06',1,'../public/thumbnails/pngtree-special-offer-banner-vector-png-image_6586628_6788cf12eb126.png'),(5,4,'JEUNE',20.00,'Une réduction importante sur les produits ou services pour les membres, valable toute l’année.','2025-01-05','2026-01-05',0,NULL),(6,5,'JEUNE',25.00,'Réservez maintenant et bénéficiez de 20% de réduction sur votre séjour !','2025-12-01','2026-12-01',1,'../public/thumbnails/1000_f_40470408_ukhfntiprwxqk6sna0guwdyrxr48vwdf_678902a68bf03.jpg');
/*!40000 ALTER TABLE `offre` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partenaire`
--

DROP TABLE IF EXISTS `partenaire`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partenaire` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(255) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `logo` varchar(255) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `adresse` text NOT NULL,
  `numero_de_telephone` varchar(20) NOT NULL,
  `site_web` varchar(255) NOT NULL,
  `statut` enum('ACTIF','INACTIF') NOT NULL DEFAULT 'ACTIF',
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `categorie_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`),
  UNIQUE KEY `site_web` (`site_web`),
  KEY `fk_partenaire_categorie` (`categorie_id`),
  CONSTRAINT `fk_partenaire_categorie` FOREIGN KEY (`categorie_id`) REFERENCES `categorie` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partenaire`
--

LOCK TABLES `partenaire` WRITE;
/*!40000 ALTER TABLE `partenaire` DISABLE KEYS */;
INSERT INTO `partenaire` VALUES (1,'Hôtel El Yasmin	','Alger	','../public/logos/hotel-international-sinaia-logo-png-transparent_6788c51edc1cf.png','contact@hotelelyasmin.dz','Rue des Jardins, Hydra	','021 55 77 88	','http://www.hotelelyasmin.dz	','ACTIF','2025-01-16 08:36:46','2025-01-16 08:36:46',1),(2,'Clinique El Chifa	','Oran','../public/logos/images_6788c5464653a.jpeg','info@clinique-elchifa.dz','Boulevard du Front de Mer	','041 23 45 67	','http://www.clinique-elchifa.dz	','ACTIF','2025-01-16 08:37:26','2025-01-16 08:37:26',2),(3,'École Future Leaders	','Constantine','../public/logos/istockphoto-1171617683-612x612_6788c58da3301.jpg','admin@futureleaders.dz','Rue Emir Abdelkader, El Khroub	','031 55 22 33	','http://www.futureleaders.dz	','ACTIF','2025-01-16 08:38:37','2025-01-16 08:38:37',3),(4,'Agence Voyage SaharaDream	','Tamanrasset','../public/logos/pngtree-travel-logo-design-template-for-business-and-company-vector-png-image_6696146_6788c5b90a692.png','info@saharadream.dz','Avenue de la Liberté	','029 34 56 78	','http://www.saharadream.dz	','ACTIF','2025-01-16 08:39:21','2025-01-16 08:39:21',4),(5,'Hôtel Zéphyr	','Annaba	','../public/logos/images_6788c5db0d073.png','contact@hotelzephyr.dz','Place du 19 Mars	','038 55 44 99	','http://www.hotelzephyr.dz	','ACTIF','2025-01-16 08:39:55','2025-01-16 08:39:55',1),(6,'Clinique El Amel	','Béjaïa','../public/logos/medical-clinic-logo-healthcare-logo-design-template-6e954d6359cbbd1b0dcb21fe6f59f4b2_screen_6788c5fa9ac0a.jpg','contact@clinique-elamel.dz','Rue des Frères Hanifi, Amizour	','034 56 77 88	','http://www.clinique-elamel.dz	','ACTIF','2025-01-16 08:40:26','2025-01-16 08:40:26',2),(7,'École Génération Tech	','Blida','../public/logos/creation-logo-ecole-education_586739-4432_6788c61d933c1.avif','info@generationtech.dz','Quartier Ben Boulaid	','025 44 33 22	','http://www.generationtech.dz	','ACTIF','2025-01-16 08:41:01','2025-01-16 08:41:01',3),(8,'Agence Voyage Blue Horizon	','Tipaza','../public/logos/logo-voyage-detaille_23-2148616611_6788c64acb90f.avif','contact@bluehorizon.dz','Rue de la Corniche	','024 66 55 44	','http://www.bluehorizon.dz	','ACTIF','2025-01-16 08:41:46','2025-01-16 08:41:46',4),(9,'Hôtel Sahara Pearl	','Ghardaïa','../public/logos/images-1_6788c66946ac8.png','info@saharapearl.dz','Rue de l’Oasis, Beni Isguen	','029 77 88 99	','http://www.saharapearl.dz	','ACTIF','2025-01-16 08:42:17','2025-01-16 08:42:17',1),(10,'Clinique Al Hayat	','Tizi Ouzou	','../public/logos/icone-du-logo-soins-sante_125964-471_6788c6864ec73.avif','contact@clinique-alhayat.dz','Rue de l’Hôpital	','026 55 66 77	','http://www.clinique-alhayat.dz	','ACTIF','2025-01-16 08:42:46','2025-01-16 08:42:46',2);
/*!40000 ALTER TABLE `partenaire` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `partenaire_favoris`
--

DROP TABLE IF EXISTS `partenaire_favoris`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `partenaire_favoris` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compte_membre_id` int NOT NULL,
  `partenaire_id` int NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_membre_partenaire` (`compte_membre_id`,`partenaire_id`),
  KEY `fk_favoris_partenaire` (`partenaire_id`),
  CONSTRAINT `fk_favoris_membre` FOREIGN KEY (`compte_membre_id`) REFERENCES `compte_membre` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_favoris_partenaire` FOREIGN KEY (`partenaire_id`) REFERENCES `partenaire` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `partenaire_favoris`
--

LOCK TABLES `partenaire_favoris` WRITE;
/*!40000 ALTER TABLE `partenaire_favoris` DISABLE KEYS */;
INSERT INTO `partenaire_favoris` VALUES (1,2,1),(2,2,2),(3,2,3),(4,2,5),(5,2,8);
/*!40000 ALTER TABLE `partenaire_favoris` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `remise_obtenus`
--

DROP TABLE IF EXISTS `remise_obtenus`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `remise_obtenus` (
  `id` int NOT NULL AUTO_INCREMENT,
  `compte_membre_id` int NOT NULL,
  `offre_id` int NOT NULL,
  `date_benefice` date NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `unique_membre_offre_date` (`compte_membre_id`,`offre_id`,`date_benefice`),
  KEY `fk_remise_offre` (`offre_id`),
  CONSTRAINT `fk_remise_membre` FOREIGN KEY (`compte_membre_id`) REFERENCES `compte_membre` (`id`) ON DELETE CASCADE,
  CONSTRAINT `fk_remise_offre` FOREIGN KEY (`offre_id`) REFERENCES `offre` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `remise_obtenus`
--

LOCK TABLES `remise_obtenus` WRITE;
/*!40000 ALTER TABLE `remise_obtenus` DISABLE KEYS */;
INSERT INTO `remise_obtenus` VALUES (4,2,1,'2025-01-10'),(3,2,2,'2025-01-17'),(2,2,4,'2025-01-16');
/*!40000 ALTER TABLE `remise_obtenus` ENABLE KEYS */;
UNLOCK TABLES;

--
-- Table structure for table `type_aide`
--

DROP TABLE IF EXISTS `type_aide`;
/*!40101 SET @saved_cs_client     = @@character_set_client */;
/*!50503 SET character_set_client = utf8mb4 */;
CREATE TABLE `type_aide` (
  `id` int NOT NULL AUTO_INCREMENT,
  `label` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `dossier_requis` text NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `label` (`label`)
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;
/*!40101 SET character_set_client = @saved_cs_client */;

--
-- Dumping data for table `type_aide`
--

LOCK TABLES `type_aide` WRITE;
/*!40000 ALTER TABLE `type_aide` DISABLE KEYS */;
INSERT INTO `type_aide` VALUES (1,'Aide Alimentaire','Fourniture de paniers alimentaires de base aux familles nécessiteuses pour répondre à leurs besoins essentiels.','☐ Photocopie de la carte d’identité nationale\r\n☐ Certificat de résidence\r\n☐ Attestation de non-revenu ou fiche de paie récente (si applicable) '),(2,'Aide Médicale','Couverture des frais de soins médicaux, d’achat de médicaments ou de consultations spécialisées.','☐ Photocopie de la carte d’identité nationale\r\n☐ Prescription médicale ou certificat médical\r\n☐ Attestation de non-couverture sociale\r\n'),(3,'Aide Scolaire','Distribution de kits scolaires ou financement des frais de scolarité pour les enfants défavorisés. ','☐ Certificat de scolarité de l’enfant\r\n☐ Photocopie de la carte d’identité du tuteur légal\r\n☐ Attestation de revenus ou déclaration sur l’honneur de non-revenu'),(4,'Aide en Vêtements','Distribution de vêtements pour adultes et enfants, adaptés aux saisons et aux besoins spécifiques','☐ Photocopie de la carte d’identité nationale\r\n☐ Certificat de résidence\r\n☐ Attestation de précarité ou déclaration sur l’honneur');
/*!40000 ALTER TABLE `type_aide` ENABLE KEYS */;
UNLOCK TABLES;
/*!40103 SET TIME_ZONE=@OLD_TIME_ZONE */;

/*!40101 SET SQL_MODE=@OLD_SQL_MODE */;
/*!40014 SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS */;
/*!40014 SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
/*!40111 SET SQL_NOTES=@OLD_SQL_NOTES */;

-- Dump completed on 2025-01-16 15:38:49
