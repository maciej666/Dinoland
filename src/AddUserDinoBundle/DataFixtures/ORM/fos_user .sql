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

INSERT INTO `fos_user` (`username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `Name`, `Species`, `Age`, `dino_id`, `materia_id`) VALUES
('jedral90@tlen.pl', 'jedral90@tlen.pl', 'jedral90@tlen.pl', 'jedral90@tlen.pl', 1, 'nVpaLmHsR7yU/MhR.hkA7R6C5ZiNmRwRQa31bxTbDDk', 'b1cci5smlhMEYI7VycHvBUoy6kQ=', '2017-02-28 15:19:01', NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 'Dinoid', 'Teropod', 14, 1, 1),
('bobo@ty.pl', 'bobo@ty.pl', 'bobo@ty.pl', 'bobo@ty.pl', 0, 'diY21DAD3SfJ/mQH.Kf4.wa6.LClr6ick2HNs7ae2EI', 'YlQV6EFDnSbatUlGSqqKVr3UL7o=', NULL, 'vEzNUrBXAzeVJMYVFJgQjiiZDSbvzlz705ntm8qF-sY', NULL, 'a:0:{}', 'Delim', 'Owiraptor', 1, 2, 2),
('alibaba@po.pl', 'alibaba@po.pl', 'alibaba@po.pl', 'alibaba@po.pl', 0, 'FVaz8BjlcxLosZ5f9maaRCDtdz7aPkBpcU91Rvclqh0', 'cL/MTBgSJMWQQfQAwPR5BNPTFKI=', NULL, '98455GKiYM4zk-KrobrSjf5C3pplbfbyEKjgrRASPss', NULL, 'a:0:{}', 'Dopino', 'Triceratops', 30, 3, 3),
('fren@tre.pl', 'fren@tre.pl', 'fren@tre.pl', 'fren@tre.pl', 0, 'F/cU7m3mHck/uO7Bk5OPbX5kGfbHgUAmjXlMOP27SCE', 'sUABHZJhrlxSlZ7JMujagbFJekY=', NULL, 'XlkLBAtkQ3Zxl_lLrTBL0RMmPeu4DKRY4jIWUxDNgaI', NULL, 'a:0:{}', 'Dopik', 'Brachiozaur', 26, 4, 4),
('wanda@re.com', 'wanda@re.com', 'wanda@re.com', 'wanda@re.com', 0, 'E9GAfoKYAfTizDVJi/7Nk50pMxkFsm4bDZ7iWzRXSTU', 'lTiobfUDQ56SQadTTixmR0TawRQ=', NULL, 'ABI884GTr97Wj2ergIBvfcdR1TfyufWjNNcxGNk8uRU', NULL, 'a:0:{}', 'Dynt', 'Welociraptor', 4, 5, 5);

