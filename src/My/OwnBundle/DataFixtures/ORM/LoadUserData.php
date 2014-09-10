<?php




namespace My\OwnBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use My\OwnBundle\Entity\User;

class LoadUserData implements FixtureInterface
{
    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('JackTheStripper');
				$user->setEmail('admin@email.com');
        $user->setPassword('secret111');
				$user->setPlainPassword('secret111');
				$user->setEnabled(1);
				$user->setAdmin(TRUE);

        $manager->persist($user);
        $manager->flush();
    }
}