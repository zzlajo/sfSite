<?php

namespace Abroad\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Abroad\UserBundle\Entity\User;


class UserFixtures implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * {@inheritDoc}
     */
    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

    /**
     * {@inheritDoc}
     */
    public function load(ObjectManager $manager)
    {
        $user = new User();
        $user->setUsername('zlajo');
        $user->setFirstName('Zlatko');
        $user->setLastName('Zuberic');
        $user->setEmail('zlajo@mail.com');
        $user->setPlainPassword('1111');
        $user->setEnabled(TRUE);
        $user->setRoles(array('ROLE_SUPER_ADMIN'));
        $manager->persist($user);

        $user = new User();
        $user->setUsername('jana');
        $user->setFirstName('Janica');
        $user->setLastName('Jakic');
        $user->setEmail('jana@mail.com');
        $user->setPlainPassword('1111');
        $user->setEnabled(TRUE);
        $user->setRoles(array('ROLE_STANDARD'));
        $manager->persist($user);

        $user = new User();
        $user->setUsername('mirkan');
        $user->setFirstName('Mirko');
        $user->setLastName('Maric');
        $user->setEmail('mirkan@mail.com');
        $user->setPlainPassword('1111');
        $user->setEnabled(TRUE);
        $user->setRoles(array('ROLE_USER'));
        $manager->persist($user);

        $user = new User();
        $user->setUsername('rade');
        $user->setFirstName('Radoslav');
        $user->setLastName('Obradovic');
        $user->setEmail('rade@mail.com');
        $user->setPlainPassword('1111');
        $user->setEnabled(TRUE);
        $user->setRoles(array());
        $manager->persist($user);

        $user = new User();
        $user->setUsername('lara');
        $user->setFirstName('Larisa');
        $user->setLastName('Zajec');
        $user->setEmail('lara@mail.com');
        $user->setPlainPassword('1111');
        $user->setEnabled(TRUE);
        $user->setRoles(array());
        $manager->persist($user);


        $manager->flush();
    }
    
    public function getOrder() {
	return 3;
	
    }

}
