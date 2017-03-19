-- phpMyAdmin SQL Dump
-- version 4.5.5.1deb1.trusty~ppa.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas generowania: 28 Lut 2017, 14:06
-- Wersja serwera: 5.5.47-0ubuntu0.14.04.1
-- Wersja PHP: 5.5.9-1ubuntu4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";

INSERT INTO `dino_content` (`foto`, `home_name`, `descriptionLvl1`, `descriptionLvl2`, `req_wood_1`, `req_wood_2`, `req_stone_1`, `req_stone_2`, `req_bone_1`, `req_bone_2`, `req_flint_1`, `req_flint_2`) VALUES
( 'DinoBundle:Main:x1_schron1.svg.twig', 'Dinopadół', 'Wykupienie ziemskiej powierzchni płaskiej zwiększa produkcje drewna o 5 %.', 'Kolejny poziom dinopłaszczyzny to zwiększenie produkcji kamienia o 4% a kości o 2%:)', 60, 120, 40, 90, 15, 40, 6, 25),
( 'DinoBundle:Main:x1_schron2.svg.twig', 'Dinoszuwary', 'W krzakach Dino znajduje dodatkowe pokłady drewna. Jego produkcja wzrasta o 15%!', 'O Matko jedyna Dino dodarł do najgęstszych traw:) +10% do produkcji kamienia i kości.', 250, 340, 170, 200, 100, 130, 40, 70),
( 'DinoBundle:Main:x1_schron3.svg.twig', 'Dinomoczary', 'Na podmokłym terenie Dino znajduje cenny składnik broni - krzemień. Zwiększa to produkcje o 7%.', 'Najgęstsze błota wywalają Dinowi szczęśliwą liczbę 10. O tyle rośnie produkcja drewna, kamienia i kości!', 400, 560, 320, 400, 290, 310, 150, 170),
( 'DinoBundle:Main:x1_schron4.svg.twig', 'Dinokrólewiec', 'Dino dociera na szczyt gatunkowej drabiny. Osiadł na tronie ciesząc się 40% zwyżką produkcji drewna:)', 'Na dinotronie życie jak na pączku. Dino leży i spija 20% bonus od wszystkiego!', 900, 1100, 790, 850, 650, 740, 400, 500);

