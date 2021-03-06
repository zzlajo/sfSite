<?php

namespace Doctrine\DBAL\Migrations\Tests\Tools\Console\Command;

use Doctrine\DBAL\Migrations\Tests\MigrationTestCase;
use Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Tester\CommandTester;

class MigrateCommandTest extends MigrationTestCase
{

    public function testGetVersionNameFromAlias()
    {
        $class = new \ReflectionClass('Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand');
        $method = $class->getMethod('getVersionNameFromAlias');
        $method->setAccessible(true);

        $configuration = $this->getMockBuilder('Doctrine\DBAL\Migrations\Configuration\Configuration')
            ->setConstructorArgs(array($this->getSqliteConnection()))
            ->setMethods(array('resolveVersionAlias'))
            ->getMock();

        $output = $this->getOutputStream();

        $this->assertEquals(false, $method->invokeArgs(new MigrateCommand(), array('prev', $output, $configuration)));
        $this->assertContains('Already at first version.', $this->getOutputStreamContent($output));

        $output = $this->getOutputStream();

        $this->assertEquals(false, $method->invokeArgs(new MigrateCommand(), array('next', $output, $configuration)));
        $this->assertContains('Already at latest version.', $this->getOutputStreamContent($output));

        $output = $this->getOutputStream();

        $this->assertEquals(false, $method->invokeArgs(new MigrateCommand(), array('giberich', $output, $configuration)));
        $this->assertContains('Unknown version: giberich', $this->getOutputStreamContent($output));

        $output = $this->getOutputStream();

        $configuration
            ->expects($this->once())
            ->method('resolveVersionAlias')
            ->will($this->returnValue('1234'));

        $this->assertEquals('1234', $method->invokeArgs(new MigrateCommand(), array('test', $output, $configuration)));
        $this->assertEquals('', $this->getOutputStreamContent($output));
    }

    public function testCanExecute()
    {
        if (!class_exists('Symfony\Component\Console\Helper\QuestionHelper')) {
            $this->markTestSkipped(
                'The QuestionHelper must be available.'
            );
        }

        $input = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->setConstructorArgs(array(array()))
            ->setMethods(array('isInteractive'))
            ->getMock();

        $input->expects($this->any())
            ->method('isInteractive')
            ->will($this->returnValue(true));

        $output = $this->getOutputStream();

        $class = new \ReflectionClass('Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand');
        $method = $class->getMethod('canExecute');
        $method->setAccessible(true);

        /** @var \Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand $command */
        $command = $this->getMock(
            'Doctrine\DBAL\Migrations\Tools\Console\Command\MigrateCommand',
            array('getHelperSet')
        );

        $helper = new QuestionHelper();
        $helper->setInputStream($this->getInputStream("y\n"));
        if ($helper instanceof QuestionHelper) {
            $helperSet = new HelperSet(array(
                'question' => $helper
            ));
        }
        $command->setHelperSet($helperSet);
        $command->expects($this->any())
            ->method('getHelperSet')
            ->will($this->returnValue($helperSet));

        //should return true if user confirm
        $this->assertEquals(true, $method->invokeArgs($command, array('test', $input, $output)));

        //shoudl return false if user cancel
        $helper->setInputStream($this->getInputStream("n\n"));
        $this->assertEquals(false, $method->invokeArgs($command, array('test', $input, $output)));

        //should return true if non interactive
        $input = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->setConstructorArgs(array(array()))
            ->setMethods(array('isInteractive'))
            ->getMock();
        $input->expects($this->any())
            ->method('isInteractive')
            ->will($this->returnValue(false));
        $this->assertEquals(true, $method->invokeArgs($command, array('test', $input, $output)));
    }
}
