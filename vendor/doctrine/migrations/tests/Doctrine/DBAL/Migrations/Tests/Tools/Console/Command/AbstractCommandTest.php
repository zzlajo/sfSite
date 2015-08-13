<?php

namespace Doctrine\DBAL\Migrations\Tests\Tools\Console\Command;

use Doctrine\DBAL\Configuration;
use Doctrine\DBAL\Migrations\Tests\MigrationTestCase;
use Doctrine\DBAL\Tools\Console\Helper\ConnectionHelper;
use Symfony\Component\Console\Helper\DialogHelper;
use Symfony\Component\Console\Helper\HelperSet;
use Symfony\Component\Console\Helper\QuestionHelper;

class AbstractCommandTest extends MigrationTestCase
{
    private $originalCwd;

    /**
     * Invoke invisible migration configuration getter
     *
     * @param mixed $input
     * @param mixed $configuration
     *
     * @return \Doctrine\DBAL\Migrations\Configuration\Configuration
     */
    public function invokeMigrationConfigurationGetter($input, $configuration = null, $noConnection=false)
    {
        $class = new \ReflectionClass('Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand');
        $method = $class->getMethod('getMigrationConfiguration');
        $method->setAccessible(true);

        /** @var \Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand $command */
        $command = $this->getMockForAbstractClass(
            'Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand',
            array('command')
        );

        if (!$noConnection) {
            $command->setHelperSet(new HelperSet(array(
                'connection' => new ConnectionHelper($this->getSqliteConnection())
            )));
        } else {
            $command->setHelperSet(new HelperSet());
        }
        if (null !== $configuration) {
            $command->setMigrationConfiguration($configuration);
        }

        $output = $this->getMockBuilder('Symfony\Component\Console\Output\Output')
            ->setMethods(array('doWrite', 'writeln'))
            ->getMock();

        $output->expects($this->any())
            ->method('doWrite');

        return $method->invokeArgs($command, array($input, $output));
    }


    /**
     * Test if the returned migration configuration is the injected one
     */
    public function testInjectedMigrationConfigurationIsBeingReturned()
    {
        $input = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->setConstructorArgs(array(array()))
            ->setMethods(array('getOption'))
            ->getMock();

        $input->expects($this->any())
            ->method('getOption')
            ->with($this->logicalOr($this->equalTo('db-configuration'), $this->equalTo('configuration')))
            ->will($this->returnValue(null));

        $configuration = $this
            ->getMockBuilder('Doctrine\DBAL\Migrations\Configuration\Configuration')
            ->disableOriginalConstructor()
            ->getMock();

        $this->assertEquals($configuration, $this->invokeMigrationConfigurationGetter($input, $configuration));
    }

    /**
     * Test if the migration configuration returns the connection from the helper set
     */
    public function testMigrationConfigurationReturnsConnectionFromHelperSet()
    {
        $input = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->setConstructorArgs(array(array()))
            ->setMethods(array('getOption'))
            ->getMock();

        $input->expects($this->any())
            ->method('getOption')
            ->with($this->logicalOr($this->equalTo('db-configuration'), $this->equalTo('configuration')))
            ->will($this->returnValue(null));

        $actualConfiguration = $this->invokeMigrationConfigurationGetter($input);

        $this->assertInstanceOf('Doctrine\DBAL\Migrations\Configuration\Configuration', $actualConfiguration);
        $this->assertEquals($this->getSqliteConnection(), $actualConfiguration->getConnection());
    }

    /**
     * Test if the migration configuration returns the connection from the input option
     */
    public function testMigrationConfigurationReturnsConnectionFromInputOption()
    {
        $input = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->setConstructorArgs(array(array()))
            ->setMethods(array('getOption'))
            ->getMock();

        $input->expects($this->any())
            ->method('getOption')
            ->will($this->returnValueMap(array(
                array('db-configuration', __DIR__ . '/_files/db-config.php')
            )));

        $actualConfiguration = $this->invokeMigrationConfigurationGetter($input);

        $this->assertInstanceOf('Doctrine\DBAL\Migrations\Configuration\Configuration', $actualConfiguration);
        $this->assertEquals($this->getSqliteConnection(), $actualConfiguration->getConnection());
    }

    /**
     * Test if the migration configuration returns values from the configuration file
     */
    public function testMigrationConfigurationReturnsConfigurationFileOption()
    {
        $input = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->setConstructorArgs(array(array()))
            ->setMethods(array('getOption'))
            ->getMock();

        $input->expects($this->any())
            ->method('getOption')
            ->will($this->returnValueMap(array(
                array('configuration', __DIR__ . '/_files/config.yml')
            )));

        $actualConfiguration = $this->invokeMigrationConfigurationGetter($input);

        $this->assertInstanceOf('Doctrine\DBAL\Migrations\Configuration\YamlConfiguration', $actualConfiguration);
        $this->assertEquals('name', $actualConfiguration->getName());
        $this->assertEquals('migrations_table_name', $actualConfiguration->getMigrationsTableName());
        $this->assertEquals('migrations_namespace', $actualConfiguration->getMigrationsNamespace());
    }

    /**
     * Test if the migration configuration use the connection in a configuration passed to it.
     */
    public function testMigrationConfigurationReturnsConnectionFromConfigurationIfNothingElseIsProvided()
    {
        $input = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->setConstructorArgs(array(array()))
            ->getMock();

        $configuration = new \Doctrine\DBAL\Migrations\Configuration\Configuration($this->getSqliteConnection());
        $actualConfiguration = $this->invokeMigrationConfigurationGetter($input, $configuration, true);

        $this->assertInstanceOf('Doctrine\DBAL\Migrations\Configuration\Configuration', $actualConfiguration);
        $this->assertEquals($this->getSqliteConnection(), $actualConfiguration->getConnection());
        $this->assertEquals('doctrine_migration_versions', $actualConfiguration->getMigrationsTableName());
        $this->assertEquals(null, $actualConfiguration->getMigrationsNamespace());
    }

    /**
     * Test if trhow an error if no connection is passed.
     * @expectedException InvalidArgumentException
     * @expectedExceptionMessage You have to specify a --db-configuration file or pass a Database Connection as a dependency to the Migrations.
     */
    public function testMigrationConfigurationReturnsErrorWhenNoConnectionIsProvided()
    {
        $input = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->setConstructorArgs(array(array()))
            ->getMock();

        $actualConfiguration = $this->invokeMigrationConfigurationGetter($input, null, true);

        $this->assertInstanceOf('Doctrine\DBAL\Migrations\Configuration\Configuration', $actualConfiguration);
        $this->assertEquals($this->getSqliteConnection(), $actualConfiguration->getConnection());
        $this->assertEquals('doctrine_migration_versions', $actualConfiguration->getMigrationsTableName());
        $this->assertEquals(null, $actualConfiguration->getMigrationsNamespace());
    }

    public function testMigrationsConfigurationFromCommandLineOverridesInjectedConfiguration()
    {
        $input = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->setConstructorArgs(array(array()))
            ->setMethods(array('getOption'))
            ->getMock();

        $input->expects($this->any())
            ->method('getOption')
            ->will($this->returnValueMap(array(
                array('configuration', __DIR__ . '/_files/config.yml')
            )));

        $configuration = $this
            ->getMockBuilder('Doctrine\DBAL\Migrations\Configuration\Configuration')
            ->disableOriginalConstructor()
            ->getMock();

        $actualConfiguration = $this->invokeMigrationConfigurationGetter($input, $configuration);

        $this->assertInstanceOf('Doctrine\DBAL\Migrations\Configuration\YamlConfiguration', $actualConfiguration);
        $this->assertEquals('name', $actualConfiguration->getName());
        $this->assertEquals('migrations_table_name', $actualConfiguration->getMigrationsTableName());
        $this->assertEquals('migrations_namespace', $actualConfiguration->getMigrationsNamespace());
    }

    /**
     * @see https://github.com/doctrine/migrations/issues/228
     * @group regression
     */
    public function testInjectedConfigurationIsPreferedOverConfigFileIsCurrentWorkingDirectory()
    {
        $input = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->setConstructorArgs(array(array()))
            ->setMethods(array('getOption'))
            ->getMock();

        $input->expects($this->any())
            ->method('getOption')
            ->will($this->returnValueMap(array(
                array('configuration', null)
            )));

        $configuration = $this
            ->getMockBuilder('Doctrine\DBAL\Migrations\Configuration\Configuration')
            ->disableOriginalConstructor()
            ->getMock();

        chdir(__DIR__.'/_files');
        $actualConfiguration = $this->invokeMigrationConfigurationGetter($input, $configuration);

        $this->assertSame($configuration, $actualConfiguration);
    }

    public function invokeAbstractCommandConfirmation($input, $helper, $response="y", $question="There is no question?")
    {
        $class = new \ReflectionClass('Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand');
        $method = $class->getMethod('askConfirmation');
        $method->setAccessible(true);

        /** @var \Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand $command */
        $command = $this->getMockForAbstractClass(
            'Doctrine\DBAL\Migrations\Tools\Console\Command\AbstractCommand',
            array('command')
        );

        $helper->setInputStream($this->getInputStream($response . "\n"));
        if ($helper instanceof QuestionHelper) {
            $helperSet = new HelperSet(array(
                'question' => $helper
            ));
        } else {
            $helperSet = new HelperSet(array(
                'dialog' => $helper
            ));
        }

        $command->setHelperSet($helperSet);

        $output = $this->getMockBuilder('Symfony\Component\Console\Output\Output')
            ->setMethods(array('doWrite', 'writeln'))
            ->getMock();

        $output->expects($this->any())
            ->method('doWrite');

        return $method->invokeArgs($command, array($question, $input, $output));
    }

    public function testAskConfirmation()
    {
        $input = $this->getMockBuilder('Symfony\Component\Console\Input\ArrayInput')
            ->setConstructorArgs(array(array()))
            ->setMethods(array('getOption'))
            ->getMock();

        /**
         * This test is testing a deprecated method.
         * PHPunit convert those deprecations errors into tests failures.
         * You can either use \PHPUnit_Framework_Error_Deprecated::$enabled = false;
         * or use the @ operator to suppress the error.
         * The advantage of the later is that it also remove the error message from the phpunit output.
         */
        if (class_exists("Symfony\\Component\\Console\\Helper\\DialogHelper"))
        {
            @$helper = new DialogHelper();

            @$this->assertEquals(true, $this->invokeAbstractCommandConfirmation($input, $helper));
            @$this->assertEquals(false, $this->invokeAbstractCommandConfirmation($input, $helper, "n"));
        }

        if (class_exists("Symfony\\Component\\Console\\Helper\\QuestionHelper")) {
            $helper = new QuestionHelper();
            $this->assertEquals(true, $this->invokeAbstractCommandConfirmation($input, $helper));
            $this->assertEquals(false, $this->invokeAbstractCommandConfirmation($input, $helper, "n"));
        }
    }

    protected function setUp()
    {
        $this->originalCwd = getcwd();
    }

    protected function tearDown()
    {
        if (getcwd() !== $this->originalCwd) {
            chdir($this->originalCwd);
        }
    }
}
