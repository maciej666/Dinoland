<?php
namespace AddUserDinoBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\AbstractFixture;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Finder\Finder;

class LoadContentData extends AbstractFixture implements FixtureInterface
{

    public function load(ObjectManager $manager)
    {
        // Dodaje content dla strony /dino_account
        $finder = new Finder();
        $finder->in(__DIR__);
        $finder->name('dino_account_content.sql');

        foreach( $finder as $file ){
            $content = $file->getContents();
            global $kernel;
            $stmt = $kernel->getContainer()->get('doctrine.orm.entity_manager')->getConnection()->prepare($content);
            $stmt->execute();
        }
    }


    public function getOrder() {
        return 4;  // Order in which this fixture will be executed
    }
}