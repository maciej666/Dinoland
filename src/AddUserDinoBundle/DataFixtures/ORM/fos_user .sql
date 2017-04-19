-- phpMyAdmin SQL Dump
-- version 4.5.5.1deb1.trusty~ppa.1
-- http://www.phpmyadmin.net
--
-- Host: localhost
-- Czas generowania: 28 Lut 2017, 15:19
-- Wersja serwera: 5.5.47-0ubuntu0.14.04.1
-- Wersja PHP: 5.5.9-1ubuntu4.21

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


--
-- Zrzut danych tabeli `fos_user`
--

INSERT INTO `fos_user` (`id`, `dino_id`, `materia_id`, `username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `Name`, `Species`, `Age`, `salt`, `image_id`) VALUES
(1, 1, 1, 'jedral90@tlen.pl', 'jedral90@tlen.pl', 'jedral90@tlen.pl', 'jedral90@tlen.pl', 1, 'QiZkvDShdiY5X3h3nbmGcTf0uqA=', '2017-04-19 13:09:54', NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 'Dinoido', 'Teropod', 14, 'oUdaa8AmozUO8nemfICoM0GW9h9k96tk7iweqUQ0bHo', 1),
(2, 2, 2, 'bobo@ty.pl', 'bobo@ty.pl', 'bobo@ty.pl', 'bobo@ty.pl', 0, 'gt4JDASHWG7jjxiacrPqdDdyJZ4=', NULL, 'vEzNUrBXAzeVJMYVFJgQjiiZDSbvzlz705ntm8qF-sY', NULL, 'a:0:{}', 'Delim', 'Owiraptor', 1, '1jKa4P8wyg3VbLfwCKft7px2t3.1kivIhYmGrWbZlEs', NULL),
(3, 3, 3, 'alibaba@po.pl', 'alibaba@po.pl', 'alibaba@po.pl', 'alibaba@po.pl', 0, 'cL/MTBgSJMWQQfQAwPR5BNPTFKI=', NULL, '98455GKiYM4zk-KrobrSjf5C3pplbfbyEKjgrRASPss', NULL, 'a:0:{}', 'Dopinos', 'Triceratops', 30, 'FVaz8BjlcxLosZ5f9maaRCDtdz7aPkBpcU91Rvclqh0', NULL),
(4, 4, 4, 'fren@tre.pl', 'fren@tre.pl', 'fren@tre.pl', 'fren@tre.pl', 0, 'j2FFhGA9KXTGWT6EnYTikWa046M=', NULL, 'XlkLBAtkQ3Zxl_lLrTBL0RMmPeu4DKRY4jIWUxDNgaI', NULL, 'a:0:{}', 'Dopikolo', 'Brachiozaur', 26, '9gjOm7s05lxSYZlvJyE28fH28O.5wYnTPlR1c/0ZZkA', NULL),
(5, 5, 5, 'wanda@re.com', 'wanda@re.com', 'wanda@re.com', 'wanda@re.com', 0, 'LBmHD1r+DHcfV6soDVTLq028Hc0=', NULL, 'ABI884GTr97Wj2ergIBvfcdR1TfyufWjNNcxGNk8uRU', NULL, 'a:0:{}', 'Dynt', 'Welociraptor', 4, 'soz/UDHXsiG1nW9s4fK0RYE3C/WFkPkbdS40epZ.S3w', NULL),
(6, 6, 6, 'nowy@tlen.pl', 'nowy@tlen.pl', 'nowy@tlen.pl', 'nowy@tlen.pl', 0, 'LrsKucw0vZ4txKU/D94fjC/eulM=', NULL, 'be9YJkXqTSJWtms_2A-kcckhKzlwbrFGNAGCazg_sLY', NULL, 'a:0:{}', 'delimak', 'Diplodok', 15, 'i.tsJtJaDwRZxcNPRwG5yEdJdG9G27wQzb/bO7uitsw', NULL),
(7, 7, 7, 'asd@re.pl', 'asd@re.pl', 'asd@re.pl', 'asd@re.pl', 0, 'hRizWiRQHpTX7jqhtMrKcMBeKsM=', NULL, '3SkPyf1DqXe1KKuUixdN2CuGwFlTgx4yUCa08SWPwqI', NULL, 'a:0:{}', 'Dolop', 'Triceratops', 11, 'oCD9ETqexDBRb4Y9swhDQej37d6BRCxDN.ZvPUImjVo', NULL),
(8, 8, 8, 'Dodo@tr.pl', 'dodo@tr.pl', 'Dodo@tr.pl', 'dodo@tr.pl', 0, '1qrWylCy1AwI5lb01QfAauPxfdo=', NULL, 'JQPHA9EdruuUlArYQ0P6f4QP9PfbfSGdUT4r9n_bFb8', NULL, 'a:0:{}', 'Dylan', 'Diplodok', 7, 'rz6bUm3Fp3dWK2GfLpIWdm71GZJKTKQ1e3CBRH2ZOk4', NULL),
(9, 9, 9, 'Ali@o2.pl', 'ali@o2.pl', 'Ali@o2.pl', 'ali@o2.pl', 0, 'CBtRfTpOQgZve5++zuw8kB4CdQk=', NULL, '80QlcYeysgKBNQhPf93-78MDhlXJHNhJdHL3JnwaZ6E', NULL, 'a:0:{}', 'Dewon', 'Stegozaur', 18, 'LraaiZqMBPw66HB8aO4pTcxfvwejrSQb7jCF8ZMPRAk', NULL),
(10, 10, 10, 'alan@tre.pl', 'alan@tre.pl', 'alan@tre.pl', 'alan@tre.pl', 0, '1fiv+s7j80sMGjRQr3kEefq7ReY=', NULL, 'tGJAJVuXxrU28D-XWMkm50cNJcTGFos6k9LROxDTE0I', NULL, 'a:0:{}', 'Disi', 'Brachiozaur', 17, 'fJjKw0ci4JITUc76z3lV9GYQtKHz/PgjygPNdYxU80w', NULL),
(11, 11, 11, 'wela@re.pl', 'wela@re.pl', 'wela@re.pl', 'wela@re.pl', 0, 'wm935bmN3j+0vhvFEzpgtezkOko=', NULL, 'DcfIFAFCA02PfFlH-Aey3UF6j9R1vRRwdmLsnaAm5YA', NULL, 'a:0:{}', 'Dikop', 'Stegozaur', 13, 'hz2ynxnN5y4J2uxbbKY2t7m6b/3v9gZWd8psMoG75og', NULL),
(12, 12, 12, 'polo2@asd.pl', 'polo2@asd.pl', 'polo2@asd.pl', 'polo2@asd.pl', 0, 'W8aPLUBI1DpefQiNguhS2Gu9nt0=', NULL, 'owCJa26rFfj2Z1m4x2civb_ake-LZt9c50wWefXoomk', NULL, 'a:0:{}', 'Dlesi', 'Stegozaur', 8, '.LPjC0fnZgdry6IdtcpyYb.A4xjToAP/4XVzVl1QOdM', NULL);

