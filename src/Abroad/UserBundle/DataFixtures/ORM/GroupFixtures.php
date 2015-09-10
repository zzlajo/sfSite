<?php

namespace Abroad\UserBundle\DataFixtures\ORM;

use Doctrine\Common\DataFixtures\FixtureInterface;
use Doctrine\Common\DataFixtures\OrderedFixtureInterface;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Abroad\UserBundle\Entity\Group;


class GroupFixtures implements FixtureInterface, ContainerAwareInterface, OrderedFixtureInterface
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
        $group = new Group();
        $group->setName('perjaj');
        $group->setRoles(array());
        $manager->persist($group);

        $group = new Group();
        $group->setName('Ribica');
        $group->setRoles(array());
        $manager->persist($group);

        $group = new Group();
        $group->setName('Ona mlada a ja peske');
        $group->setRoles(array());
        $manager->persist($group);

        $group = new Group();
        $group->setName('Pravoslavci iz citave Vaseljenje');
        $group->setRoles(array());
        $manager->persist($group);

        $group = new Group();
        $group->setName('Pecarosi sa DTD1');
        $group->setRoles(array());
        $manager->persist($group);

        $group = new Group();
        $group->setName('Svi developeri sa Banije');
        $group->setRoles(array());
        $manager->persist($group);

        $group = new Group();
        $group->setName('Rokeri');
        $group->setRoles(array());
        $manager->persist($group);

        


        $manager->flush();
    }
    
    public function getOrder() {
	return 4;
	
    }

}
