-- phpMyAdmin SQL Dump
-- version 4.7.0
-- https://www.phpmyadmin.net/
--
-- Hôte : 127.0.0.1
-- Généré le :  jeu. 15 mai 2025 à 19:45
-- Version du serveur :  5.7.17
-- Version de PHP :  5.6.30

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `rdv_db`
--

-- --------------------------------------------------------

--
-- Structure de la table `creneau`
--

CREATE TABLE `creneau` (
  `id_creneau` int(11) NOT NULL,
  `date_rdv` date NOT NULL,
  `heure_rdv` time NOT NULL,
  `disponible` tinyint(1) DEFAULT '1'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `creneau`
--

INSERT INTO `creneau` (`id_creneau`, `date_rdv`, `heure_rdv`, `disponible`) VALUES
(1, '2025-04-15', '09:00:00', 1),
(2, '2025-04-15', '09:30:00', 1),
(3, '2025-04-15', '10:00:00', 1),
(4, '2025-04-08', '17:00:00', 1),
(5, '2025-04-08', '18:00:00', 0),
(6, '2025-04-15', '17:00:00', 0),
(7, '2025-04-28', '18:45:00', 1),
(8, '2025-04-30', '18:30:00', 0),
(9, '2025-04-30', '18:00:00', 0),
(10, '2025-04-16', '17:45:00', 0),
(11, '2025-04-11', '17:45:00', 0),
(12, '2025-04-18', '18:00:00', 0),
(13, '2025-04-19', '18:00:00', 1),
(14, '2025-04-16', '17:00:00', 0),
(15, '2025-05-15', '17:00:00', 1),
(16, '2025-05-24', '17:00:00', 1),
(17, '2025-05-22', '17:00:00', 1),
(18, '2025-06-15', '17:00:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `eleve`
--

CREATE TABLE `eleve` (
  `id_eleve` int(11) NOT NULL,
  `nom` varchar(50) NOT NULL,
  `prenom` varchar(50) NOT NULL,
  `id_parent` int(11) NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `eleve`
--

INSERT INTO `eleve` (`id_eleve`, `nom`, `prenom`, `id_parent`) VALUES
(1, 'Durand', 'Lucas', 2),
(2, 'Dupont', 'Emma', 2);

-- --------------------------------------------------------

--
-- Structure de la table `rendezvous`
--

CREATE TABLE `rendezvous` (
  `id_rdv` int(11) NOT NULL,
  `id_parent` int(11) DEFAULT NULL,
  `id_prof` int(11) DEFAULT NULL,
  `id_creneau` int(11) DEFAULT NULL,
  `id_eleve` int(11) DEFAULT NULL,
  `statut` enum('en_attente','en_attente_prof','accepte','refuse','refuse_admin') NOT NULL DEFAULT 'en_attente',
  `created_by` enum('parent','prof') NOT NULL DEFAULT 'parent',
  `motif` text,
  `motif_refus` text,
  `supprime_parent` tinyint(1) DEFAULT '0',
  `supprime_prof` tinyint(1) DEFAULT '0',
  `archive` tinyint(1) DEFAULT '0',
  `notif_parent` tinyint(1) DEFAULT '0',
  `notif_prof` tinyint(1) DEFAULT '0'
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `rendezvous`
--

INSERT INTO `rendezvous` (`id_rdv`, `id_parent`, `id_prof`, `id_creneau`, `id_eleve`, `statut`, `created_by`, `motif`, `motif_refus`, `supprime_parent`, `supprime_prof`, `archive`, `notif_parent`, `notif_prof`) VALUES
(1, 2, 3, 1, 1, 'refuse', 'parent', NULL, NULL, 1, 1, 0, 0, 0),
(2, 2, 3, 4, 1, 'refuse', 'parent', NULL, NULL, 1, 1, 0, 0, 0),
(3, 2, 3, 5, 1, 'accepte', 'parent', NULL, NULL, 1, 1, 0, 0, 0),
(4, 2, 3, 6, 1, 'accepte', 'parent', NULL, NULL, 1, 1, 0, 0, 0),
(5, 2, 3, 7, 1, 'refuse', 'parent', 'je veut pas', NULL, 1, 1, 0, 0, 0),
(6, 2, 3, 8, 1, 'accepte', 'prof', 'TESTTTTT', NULL, 1, 1, 0, 0, 0),
(7, 2, 3, 12, 1, 'accepte', 'prof', 'zdeaqeezeaeaz', NULL, 1, 1, 0, 0, 0),
(8, 2, 3, 13, 1, 'refuse', 'prof', 'TEST', NULL, 1, 1, 0, 0, 0),
(9, 2, 3, 14, 1, 'en_attente', 'parent', 'aeaeaeaea', NULL, 1, 1, 0, 0, 0),
(10, 2, 3, 15, 1, 'refuse', 'parent', 'JE PEUX PAS DéSOLé', NULL, 1, 1, 0, 0, 0),
(11, 2, 3, 16, 2, 'refuse', 'parent', 'a', NULL, 0, 0, 0, 0, 0),
(12, 2, 3, 17, 2, 'refuse_admin', 'parent', 'aaa', 'aa', 0, 0, 0, 0, 0),
(13, 2, 3, 18, 1, 'en_attente', 'parent', 'aa', NULL, 0, 0, 0, 0, 0);

-- --------------------------------------------------------

--
-- Structure de la table `utilisateur`
--

CREATE TABLE `utilisateur` (
  `id_utilisateur` int(11) NOT NULL,
  `nom` varchar(50) DEFAULT NULL,
  `prenom` varchar(50) DEFAULT NULL,
  `email` varchar(100) DEFAULT NULL,
  `mot_de_passe` varchar(255) DEFAULT NULL,
  `role` enum('parent','prof','admin') NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=latin1;

--
-- Déchargement des données de la table `utilisateur`
--

INSERT INTO `utilisateur` (`id_utilisateur`, `nom`, `prenom`, `email`, `mot_de_passe`, `role`) VALUES
(1, 'Admin', 'Admin', 'admin@test.com', 'admin123', 'admin'),
(2, 'Jean', 'Parent', 'parent@test.com', 'parent123', 'parent'),
(3, 'Julie', 'Prof', 'prof@test.com', 'prof123', 'prof');

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `creneau`
--
ALTER TABLE `creneau`
  ADD PRIMARY KEY (`id_creneau`);

--
-- Index pour la table `eleve`
--
ALTER TABLE `eleve`
  ADD PRIMARY KEY (`id_eleve`),
  ADD KEY `id_parent` (`id_parent`);

--
-- Index pour la table `rendezvous`
--
ALTER TABLE `rendezvous`
  ADD PRIMARY KEY (`id_rdv`),
  ADD KEY `id_parent` (`id_parent`),
  ADD KEY `id_prof` (`id_prof`),
  ADD KEY `id_creneau` (`id_creneau`),
  ADD KEY `id_eleve` (`id_eleve`);

--
-- Index pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  ADD PRIMARY KEY (`id_utilisateur`),
  ADD UNIQUE KEY `email` (`email`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `creneau`
--
ALTER TABLE `creneau`
  MODIFY `id_creneau` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;
--
-- AUTO_INCREMENT pour la table `eleve`
--
ALTER TABLE `eleve`
  MODIFY `id_eleve` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `rendezvous`
--
ALTER TABLE `rendezvous`
  MODIFY `id_rdv` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT pour la table `utilisateur`
--
ALTER TABLE `utilisateur`
  MODIFY `id_utilisateur` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
