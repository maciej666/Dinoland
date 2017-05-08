<?php

$message = \Swift_Message::newInstance()
    ->setSubject('Mail z pdf')
    ->setFrom('boban.kamienczuk666@gmail.com')
    ->setTo('jedral90@tlen.pl')
    ->setBody(
        $this->renderView(
        // app/Resources/views/Emails/pdf_email.html.twig
            'Emails/pdf_email.html.twig'
        ),
        'text/html'
    )
    ->attach(Swift_Attachment::fromPath('bundles/adduserdino/file/MaciejJędralCvPl.pdf'))
;
$this->get('mailer')->send($message);
