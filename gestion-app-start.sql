-- phpMyAdmin SQL Dump
-- version 4.5.4.1deb2ubuntu2
-- http://www.phpmyadmin.net
--
-- Client :  localhost
-- Généré le :  Mer 22 Novembre 2017 à 03:33
-- Version du serveur :  5.7.20-0ubuntu0.16.04.1
-- Version de PHP :  7.0.22-0ubuntu0.16.04.1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `gestion-app`
--
CREATE DATABASE IF NOT EXISTS `gestion-app` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci;
USE `gestion-app`;

-- --------------------------------------------------------

--
-- Structure de la table `projects`
--

DROP TABLE IF EXISTS `projects`;
CREATE TABLE `projects` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `decription` text NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `status` enum('in_progress','paused','finished','stoped') NOT NULL,
  `real_end` date NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `projects`
--

INSERT INTO `projects` (`id`, `name`, `decription`, `start`, `end`, `status`, `real_end`, `created_at`, `updated_at`, `deleted`) VALUES
(1, 'Projet 1', 'Ceci est un projet de test.', '2017-11-01', '2017-11-30', 'in_progress', '2017-11-30', '2017-11-21 21:06:05', '2017-11-21 21:06:05', 0);

-- --------------------------------------------------------

--
-- Structure de la table `tasks`
--

DROP TABLE IF EXISTS `tasks`;
CREATE TABLE `tasks` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `description` text NOT NULL,
  `start` date NOT NULL,
  `end` date NOT NULL,
  `status` enum('in_progress','paused','finished','stoped') NOT NULL,
  `priority` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `tasks`
--

INSERT INTO `tasks` (`id`, `name`, `description`, `start`, `end`, `status`, `priority`, `project_id`, `created_at`, `updated_at`, `deleted`) VALUES
(1, 'Tache 1', 'Ceci est une tache de test rattachée au projet numéro 1.', '2017-11-01', '2017-11-09', 'in_progress', 1, 1, '2017-11-21 21:12:00', '2017-11-21 21:12:00', 0);

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(30) NOT NULL,
  `email` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `description`, `created_at`, `updated_at`, `deleted`) VALUES
(1, 'Utilisateur 1', 'user1@test.com', 'Ceci est un utilisateur test travaillant sur le projet 1. Il travaille aussi sur la tache 1.', '2017-11-21 21:13:04', '2017-11-22 02:12:28', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user_project`
--

DROP TABLE IF EXISTS `user_project`;
CREATE TABLE `user_project` (
  `user_id` int(11) NOT NULL,
  `project_id` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `user_project`
--

INSERT INTO `user_project` (`user_id`, `project_id`, `created_at`, `updated_at`, `deleted`) VALUES
(1, 1, '2017-11-21 21:13:15', '2017-11-21 21:13:15', 0);

-- --------------------------------------------------------

--
-- Structure de la table `user_task`
--

DROP TABLE IF EXISTS `user_task`;
CREATE TABLE `user_task` (
  `user_id` int(11) NOT NULL,
  `task_id` int(11) NOT NULL,
  `estimation` int(11) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `deleted` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `user_task`
--

INSERT INTO `user_task` (`user_id`, `task_id`, `estimation`, `created_at`, `updated_at`, `deleted`) VALUES
(1, 1, 9, '2017-11-21 21:13:58', '2017-11-21 21:13:58', 0);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `projects`
--
ALTER TABLE `projects`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD PRIMARY KEY (`id`),
  ADD KEY `project_id` (`project_id`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Index pour la table `user_project`
--
ALTER TABLE `user_project`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `project_id` (`project_id`);

--
-- Index pour la table `user_task`
--
ALTER TABLE `user_task`
  ADD KEY `user_id` (`user_id`),
  ADD KEY `task_id` (`task_id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `projects`
--
ALTER TABLE `projects`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `tasks`
--
ALTER TABLE `tasks`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `tasks`
--
ALTER TABLE `tasks`
  ADD CONSTRAINT `task-project_id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Contraintes pour la table `user_project`
--
ALTER TABLE `user_project`
  ADD CONSTRAINT `user-id_project` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_project-id` FOREIGN KEY (`project_id`) REFERENCES `projects` (`id`);

--
-- Contraintes pour la table `user_task`
--
ALTER TABLE `user_task`
  ADD CONSTRAINT `user-id_task` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `user_task-id` FOREIGN KEY (`task_id`) REFERENCES `tasks` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
