-- phpMyAdmin SQL Dump
-- version 5.2.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1:3306
-- Généré le : mer. 20 mai 2026 à 16:32
-- Version du serveur : 8.0.31
-- Version de PHP : 8.0.26

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données : `greenverse`
--

-- --------------------------------------------------------

--
-- Structure de la table `ateliers`
--

DROP TABLE IF EXISTS `ateliers`;
CREATE TABLE IF NOT EXISTS `ateliers` (
  `id` int NOT NULL AUTO_INCREMENT,
  `titre` varchar(100) NOT NULL,
  `description` text NOT NULL,
  `date_atelier` date NOT NULL,
  `heure` time NOT NULL,
  `lieu` varchar(100) NOT NULL,
  `video` varchar(255) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=16 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `ateliers`
--

INSERT INTO `ateliers` (`id`, `titre`, `description`, `date_atelier`, `heure`, `lieu`, `video`) VALUES
(7, 'Création de Bouquet', 'Apprenez à composer de magnifiques bouquets de fleurs 🌹.', '2025-12-20', '00:00:00', '', 'https://www.youtube.com/embed/-w0CbfWhk2A'),
(8, 'Plantes Potagères', 'Techniques pour cultiver tomates, carottes et laitues 🍅🥕🥬', '2025-12-27', '00:00:00', '', 'https://www.youtube.com/shorts/OZC5Jk3wEJw'),
(9, 'Plantes Aromatiques', 'Découvrez l’utilisation de basilic, menthe et thym 🌿', '2026-01-03', '00:00:00', '', 'https://www.youtube.com/watch?v=p8fy_SM5G6M');

-- --------------------------------------------------------

--
-- Structure de la table `commandes`
--

DROP TABLE IF EXISTS `commandes`;
CREATE TABLE IF NOT EXISTS `commandes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prenom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `adresse` varchar(255) NOT NULL,
  `ville` varchar(100) NOT NULL,
  `code_postal` varchar(10) NOT NULL,
  `pays` varchar(100) NOT NULL DEFAULT 'France',
  `mode_paiement` enum('carte','virement','livraison') NOT NULL,
  `total_ht` decimal(10,2) NOT NULL,
  `livraison` decimal(10,2) NOT NULL DEFAULT '0.00',
  `total_ttc` decimal(10,2) NOT NULL,
  `statut` enum('en_attente','confirmee','expediee','livree','annulee') NOT NULL DEFAULT 'en_attente',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commandes`
--

INSERT INTO `commandes` (`id`, `session_id`, `nom`, `prenom`, `email`, `telephone`, `adresse`, `ville`, `code_postal`, `pays`, `mode_paiement`, `total_ht`, `livraison`, `total_ttc`, `statut`, `created_at`) VALUES
(1, '6ee2dln8krsehnhb5d15726ah0', 'Dahmeni', 'Yasmine', 'yasminedahmeni73@gmail.com', '1', 's', 'z', '1', 'France', 'virement', '15.00', '4.90', '19.90', 'en_attente', '2026-04-28 21:02:06'),
(2, '6ee2dln8krsehnhb5d15726ah0', 'Dahmeni', 'Yasmine', 'yasminedahmeni73@gmail.com', 'ddd', 's', 'z', '1', 'France', 'virement', '30.00', '0.00', '30.00', 'livree', '2026-04-28 21:05:04'),
(4, '6ee2dln8krsehnhb5d15726ah0', 'ssss', 'sss', 'yasminedahmeni73@gmail.com', '4', 's', 'z', '1f', 'France', 'carte', '15.00', '4.90', '19.90', 'annulee', '2026-04-29 12:07:49'),
(5, '64gap80rmtve10f1e64m8cm6n8', 'Dahmeni', 'Yasmine', 'yasminedahmeni73@gmail.com', '4', 's', 'z', '1', 'France', 'livraison', '54.00', '0.00', '54.00', 'livree', '2026-04-29 13:06:07'),
(6, 'ub2mmtakn7k5asm9vno82ci3nd', 'ssss', 'sss', 'yasminedahmani411@yahoo.com', 'ss', 'ss', 'nabeul', '1', 'Tunisie', 'livraison', '0.00', '4.90', '4.90', 'expediee', '2026-05-13 16:56:37');

-- --------------------------------------------------------

--
-- Structure de la table `commande_items`
--

DROP TABLE IF EXISTS `commande_items`;
CREATE TABLE IF NOT EXISTS `commande_items` (
  `id` int NOT NULL AUTO_INCREMENT,
  `commande_id` int NOT NULL,
  `item_id` int NOT NULL,
  `item_type` enum('produit','plante') NOT NULL,
  `nom` varchar(100) NOT NULL,
  `prix` decimal(10,2) NOT NULL,
  `quantite` int NOT NULL,
  `sous_total` decimal(10,2) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `commande_id` (`commande_id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `commande_items`
--

INSERT INTO `commande_items` (`id`, `commande_id`, `item_id`, `item_type`, `nom`, `prix`, `quantite`, `sous_total`) VALUES
(1, 1, 2, 'produit', 'Kit potager', '15.00', 1, '15.00'),
(2, 2, 2, 'produit', 'Kit potager', '15.00', 2, '30.00'),
(4, 4, 2, 'produit', 'Kit potager', '15.00', 1, '15.00'),
(5, 5, 1, 'produit', 'Graines de fleurs', '3.00', 3, '9.00'),
(6, 5, 2, 'produit', 'Kit potager', '15.00', 3, '45.00'),
(7, 6, 1, 'plante', 'Rose', '0.00', 4, '0.00');

-- --------------------------------------------------------

--
-- Structure de la table `inscriptions`
--

DROP TABLE IF EXISTS `inscriptions`;
CREATE TABLE IF NOT EXISTS `inscriptions` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(150) NOT NULL,
  `email` varchar(150) NOT NULL,
  `atelier` varchar(255) NOT NULL,
  `newsletter` tinyint(1) DEFAULT '0',
  `message` text,
  `date_inscription` datetime DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `inscriptions`
--

INSERT INTO `inscriptions` (`id`, `nom`, `email`, `atelier`, `newsletter`, `message`, `date_inscription`) VALUES
(1, 'ddd', 'yasminedahmeni73@gmail.com', 'Plantes Potagères', 0, 'ddd', '2026-04-29 12:58:59'),
(2, 'sss', 'yasminedahmeni73@gmail.com', 'Plantes Potagères', 0, 'sssssssssss', '2026-04-29 13:04:04'),
(3, 'sss', 'yasminedahmeni73@gmail.com', 'Plantes Potagères', 0, 'sssssssssss', '2026-04-29 13:07:36'),
(4, 'sss', 'yasminedahmeni73@gmail.com', 'dddd', 1, 'kk', '2026-04-29 14:01:33'),
(5, 'DAHMANI', 'yd24062003@gmail.com', 'Création de Bouquet', 1, 'ssssss', '2026-05-14 17:11:07');

-- --------------------------------------------------------

--
-- Structure de la table `panier`
--

DROP TABLE IF EXISTS `panier`;
CREATE TABLE IF NOT EXISTS `panier` (
  `id` int NOT NULL AUTO_INCREMENT,
  `session_id` varchar(100) NOT NULL,
  `item_id` int NOT NULL,
  `item_type` enum('produit','plante') NOT NULL,
  `nom` varchar(100) NOT NULL,
  `image` varchar(200) DEFAULT NULL,
  `prix` decimal(10,2) NOT NULL DEFAULT '0.00',
  `quantite` int NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=25 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `panier`
--

INSERT INTO `panier` (`id`, `session_id`, `item_id`, `item_type`, `nom`, `image`, `prix`, `quantite`, `created_at`, `updated_at`) VALUES
(19, '0g6troskss83686r8edck8dbl8', 1, 'produit', 'Graines de fleurs', 'images/garaines.jpg', '3.00', 1, '2026-05-13 15:48:38', '2026-05-13 15:48:38'),
(10, '0tmcv7vbt8seugug1lrin9rq6u', 3, 'produit', 'Outils de jardinage', 'images/outils.jpg', '10.00', 4, '2026-04-28 14:44:53', '2026-04-28 14:44:59'),
(17, '64gap80rmtve10f1e64m8cm6n8', 2, 'produit', 'Kit potager', 'images/kit.jpg', '15.00', 1, '2026-04-29 13:07:39', '2026-04-29 13:07:39');

-- --------------------------------------------------------

--
-- Structure de la table `plantes`
--

DROP TABLE IF EXISTS `plantes`;
CREATE TABLE IF NOT EXISTS `plantes` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `categorie` enum('fleurs','potager','aromatiques','arbustes','arbres','aquatiques','grimpantes') NOT NULL,
  `description` varchar(255) DEFAULT NULL,
  `emoji` varchar(10) DEFAULT NULL,
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=21 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `plantes`
--

INSERT INTO `plantes` (`id`, `nom`, `categorie`, `description`, `emoji`, `image`, `created_at`, `updated_at`) VALUES
(1, 'Rose', 'fleurs', 'Fleur parfumée', '🌹', 'images/rose fleur.webp', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(2, 'Tulipe', 'fleurs', 'Fleur élégante', '🌷', 'images/tulipe fleur.jpg', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(3, 'Marguerite', 'fleurs', 'Simple et lumineuse', '🌼', 'images/marguerite fleur.webp', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(4, 'Tomate', 'potager', 'Légume productif', '🍅', 'images/tomate.jpg', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(5, 'Carotte', 'potager', 'Riche en vitamines', '🥕', 'images/carrotes.webp', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(6, 'Laitue', 'potager', 'Feuilles croquantes', '🥬', 'images/laitue.jpg', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(7, 'Basilic', 'aromatiques', 'Herbe parfumée', '🌿', 'images/basilic.webp', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(8, 'Menthe', 'aromatiques', 'Parfum frais', '🌱', 'images/menthe.jpg', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(9, 'Thym', 'aromatiques', 'Parfum méditerranéen', '🍃', 'images/thym.webp', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(10, 'Hortensia', 'arbustes', 'Fleurs volumineuses', '🌸', 'images/hortensia.jpg', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(11, 'Bougainvillier', 'arbustes', 'Couleurs vives', '🌺', 'images/bougainvillier.jpg', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(12, 'Camélia', 'arbustes', 'Fleur élégante', '🌷', 'images/camelia.jpg', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(13, 'Pommier', 'arbres', 'Fruité et décoratif', '🍏', 'images/pommier.avif', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(14, 'Cerisier', 'arbres', 'Fleurs au printemps', '🍒', 'images/cerisier.jpg', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(15, 'Érable', 'arbres', 'Feuilles rouges et dorées', '🍁', 'images/erable.jpg', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(16, 'Nénuphar', 'aquatiques', 'Flottante élégante', '🌊', 'images/nenuphar.webp', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(17, 'Lotus', 'aquatiques', 'Majestueuse sur l\'eau', '🌸', 'images/lotus.avif', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(18, 'Lierre', 'grimpantes', 'Grimpeur vert', '🌿', 'images/lierre.jpg', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(19, 'Glycine', 'grimpantes', 'Fleurs pendantes', '🌸', 'images/glycine.jpg', '2026-04-28 11:13:30', '2026-04-28 11:13:30'),
(20, 'Vigne', 'grimpantes', 'Produit de délicieux raisins', '🍇', 'images/vigne.jpg', '2026-04-28 11:13:30', '2026-04-28 11:13:30');

-- --------------------------------------------------------

--
-- Structure de la table `produits`
--

DROP TABLE IF EXISTS `produits`;
CREATE TABLE IF NOT EXISTS `produits` (
  `id` int NOT NULL AUTO_INCREMENT,
  `nom` varchar(100) NOT NULL,
  `description` text,
  `prix` decimal(10,2) NOT NULL,
  `stock` int DEFAULT '0',
  `image` varchar(200) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=MyISAM AUTO_INCREMENT=9 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `produits`
--

INSERT INTO `produits` (`id`, `nom`, `description`, `prix`, `stock`, `image`) VALUES
(1, 'Graines de fleurs', 'Un mélange varié de graines de fleurs pour embellir votre jardin.', '3.00', 50, 'images/garaines.jpg'),
(2, 'Kit potager', 'Tout le nécessaire pour démarrer votre potager maison facilement.', '15.00', 30, 'images/kit.jpg'),
(3, 'Outils de jardinage', 'Set d\'outils ergonomiques et durables pour entretenir votre jardin.', '10.00', 40, 'images/outils.jpg'),
(4, 'Plantes d\'intérieur', 'Sélection de plantes d\'intérieur faciles d\'entretien et décoratives.', '8.00', 25, 'images/plantes interieur.jpg'),
(5, 'Kits DIY', 'Kits créatifs pour réaliser vos propres projets de jardinage maison.', '12.00', 20, 'images/kit2.avif'),
(6, 'Engrais bio', 'Engrais 100 % naturel pour nourrir vos plantes sans produits chimiques.', '5.00', 60, 'images/engrais bio.jpg'),
(7, 'Pots de fleurss', '', '4.00', 80, 'images/pots.jpg'),
(8, 'Système d\'arrosage', 'Système d\'arrosage automatique goutte-à-goutte, économe en eau.', '20.00', 15, 'images/sao.jpg');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE IF NOT EXISTS `users` (
  `id` int NOT NULL AUTO_INCREMENT,
  `prenom` varchar(100) NOT NULL,
  `nom` varchar(100) NOT NULL,
  `email` varchar(150) NOT NULL,
  `telephone` varchar(20) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `role` enum('utilisateur','admin') DEFAULT 'utilisateur',
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `email` (`email`)
) ENGINE=InnoDB AUTO_INCREMENT=8 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

--
-- Déchargement des données de la table `users`
--

INSERT INTO `users` (`id`, `prenom`, `nom`, `email`, `telephone`, `password`, `role`, `created_at`) VALUES
(1, 'ameni', 'bf', 'ameni@gmail.com', '12345678', '$2y$10$A1NBXdH8kRkYDj4vyrwJbe6uA07bGZCHypa4cwaN1WNN7aFr8UU8S', 'utilisateur', '2026-04-28 11:35:10'),
(2, 'maram', 'wesleti', 'maram@gmail.com', '12345678', '$2y$10$8EElr6/0IFZVcMJSKC/Ru.YcTI5u8cyP/bCRFGy5xdR4HHwK3RPH2', 'utilisateur', '2026-04-28 11:38:17'),
(3, 'yassmin', 'dahmeni', 'yass@gmail.com', '12345678', '$2y$10$AMiHa6BPYI6Y69hwqZjqcOBOL8xE9sijJjaxMiLWQenzN8okY91Um', 'admin', '2026-04-28 20:52:23'),
(4, 'ahmed', 'chrif', 'ahmed@gmail.com', '22222222', '$2y$10$Hchy1qPrzF/iSWSaurHbvO3HspG0Wt7QItXxCu4dz.hePWPpCu8n6', 'utilisateur', '2026-04-28 22:57:29'),
(5, 'test', 'test', 'test@gmail.com', '875274196', '$2y$10$ZB9nBslc7tC8EaaiJyuYHev0l0p7gCEL3pTqgoOp/zncbiZb15Xf2', 'utilisateur', '2026-04-29 11:19:33'),
(6, 'Yasmine', 'Dahmeni', 'yasminedahmeni73@gmail.com', '95206405', '$2y$10$QWuQkzsCHAZxJRXgDuO4SuMp8dxhyA1RYUxw8vaStrodllsM./l2C', 'utilisateur', '2026-04-29 12:23:33'),
(7, 'testt', 'testt', 'testt@gmail.com', '10055555', '$2y$10$ISDuQgvNYQIDGjRp6vft2OoxhUgrITEUe4LUSWb6jbdL/vqkOZMBy', 'admin', '2026-04-29 14:32:51');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
