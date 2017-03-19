Symfony Standard Edition
========================

Po odpaleniu projektu trzeba załadować dane do bazy danych za pomocą komendy
bin/console doctrine:fixtures:load

Trzeba też ręcznie wykonać zapytanie SQL dla encji User z FOSUserBundle bo nie mogłem ustawić żeby się
ładowało za pomocą fixtures. Także trzeba np. w phpmyadmin wykonac to zapytanie:

INSERT INTO `fos_user` (`username`, `username_canonical`, `email`, `email_canonical`, `enabled`, `salt`, `password`, `last_login`, `confirmation_token`, `password_requested_at`, `roles`, `Name`, `Species`, `Age`, `dino_id`, `species_info_id`, `materia_id`) VALUES
('jedral90@tlen.pl', 'jedral90@tlen.pl', 'jedral90@tlen.pl', 'jedral90@tlen.pl', 1, 'nVpaLmHsR7yU/MhR.hkA7R6C5ZiNmRwRQa31bxTbDDk', 'b1cci5smlhMEYI7VycHvBUoy6kQ=', '2017-02-28 15:19:01', NULL, NULL, 'a:1:{i:0;s:10:"ROLE_ADMIN";}', 'Dinoid', 'Teropod', 14, 1, NULL, 1),
('bobo@ty.pl', 'bobo@ty.pl', 'bobo@ty.pl', 'bobo@ty.pl', 0, 'diY21DAD3SfJ/mQH.Kf4.wa6.LClr6ick2HNs7ae2EI', 'YlQV6EFDnSbatUlGSqqKVr3UL7o=', NULL, 'vEzNUrBXAzeVJMYVFJgQjiiZDSbvzlz705ntm8qF-sY', NULL, 'a:0:{}', 'Delim', 'Owiraptor', 1, 2, NULL, 2),
('alibaba@po.pl', 'alibaba@po.pl', 'alibaba@po.pl', 'alibaba@po.pl', 0, 'FVaz8BjlcxLosZ5f9maaRCDtdz7aPkBpcU91Rvclqh0', 'cL/MTBgSJMWQQfQAwPR5BNPTFKI=', NULL, '98455GKiYM4zk-KrobrSjf5C3pplbfbyEKjgrRASPss', NULL, 'a:0:{}', 'Dopino', 'Triceratops', 30, 3, NULL, 3),
('fren@tre.pl', 'fren@tre.pl', 'fren@tre.pl', 'fren@tre.pl', 0, 'F/cU7m3mHck/uO7Bk5OPbX5kGfbHgUAmjXlMOP27SCE', 'sUABHZJhrlxSlZ7JMujagbFJekY=', NULL, 'XlkLBAtkQ3Zxl_lLrTBL0RMmPeu4DKRY4jIWUxDNgaI', NULL, 'a:0:{}', 'Dopik', 'Brachiozaur', 26, 4, NULL, 4),
('wanda@re.com', 'wanda@re.com', 'wanda@re.com', 'wanda@re.com', 0, 'E9GAfoKYAfTizDVJi/7Nk50pMxkFsm4bDZ7iWzRXSTU', 'lTiobfUDQ56SQadTTixmR0TawRQ=', NULL, 'ABI884GTr97Wj2ergIBvfcdR1TfyufWjNNcxGNk8uRU', NULL, 'a:0:{}', 'Dynt', 'Welociraptor', 4, 5, NULL, 5);

Nie jest tego dużo, starałem się zrobić "One Page Website", także nie ma za wielu ścieżek.

ścieżki dla ułatwienia to:

/
/register
/login
/dino_account
/admin/index (tutaj jest coś w zakładce Dino-konta)

Konto admina to to z mailem jedral90@tlen.pl a hasło to qwe. Nie jest to dokończone, także zeby zapisać
zmiany admina trzeba podać hasło użytkownika (dla każdego użytkownika hasło: qwe)

Strone robiłem na bootstrap'owych szablonach.

Pozdrawiam, Maciej Jędral

What's inside?
--------------

The Symfony Standard Edition is configured with the following defaults:

  * An AppBundle you can use to start coding;

  * Twig as the only configured template engine;

  * Doctrine ORM/DBAL;

  * Swiftmailer;

  * Annotations enabled for everything.

It comes pre-configured with the following bundles:

  * **FrameworkBundle** - The core Symfony framework bundle

  * [**SensioFrameworkExtraBundle**][6] - Adds several enhancements, including
    template and routing annotation capability

  * [**DoctrineBundle**][7] - Adds support for the Doctrine ORM

  * [**TwigBundle**][8] - Adds support for the Twig templating engine

  * [**SecurityBundle**][9] - Adds security by integrating Symfony's security
    component

  * [**SwiftmailerBundle**][10] - Adds support for Swiftmailer, a library for
    sending emails

  * [**MonologBundle**][11] - Adds support for Monolog, a logging library

  * **WebProfilerBundle** (in dev/test env) - Adds profiling functionality and
    the web debug toolbar

  * **SensioDistributionBundle** (in dev/test env) - Adds functionality for
    configuring and working with Symfony distributions

  * [**SensioGeneratorBundle**][13] (in dev/test env) - Adds code generation
    capabilities

  * **DebugBundle** (in dev/test env) - Adds Debug and VarDumper component
    integration

All libraries and bundles included in the Symfony Standard Edition are
released under the MIT or BSD license.

Enjoy!

[1]:  https://symfony.com/doc/3.1/setup.html
[6]:  https://symfony.com/doc/current/bundles/SensioFrameworkExtraBundle/index.html
[7]:  https://symfony.com/doc/3.1/doctrine.html
[8]:  https://symfony.com/doc/3.1/templating.html
[9]:  https://symfony.com/doc/3.1/security.html
[10]: https://symfony.com/doc/3.1/email.html
[11]: https://symfony.com/doc/3.1/logging.html
[12]: https://symfony.com/doc/3.1/assetic/asset_management.html
[13]: https://symfony.com/doc/current/bundles/SensioGeneratorBundle/index.html
# DinoGame
