#!/usr/bin/env php
<?php

$installer = new XoopsUnitInstaller();
$installer->run();

class XoopsUnitInstaller
{
	public function run()
	{
		$currentDir = getcwd();

		$testDir = $this->_askString("What is the test directory name?", 'tests');
		$testDir = $currentDir. DIRECTORY_SEPARATOR . $testDir;

		if ( is_dir($testDir) === false )
		{
			$makesTestDirectory = $this->_askYesNo("Do you want to make 'tests' directory here?");

			if ( $makesTestDirectory === 'no' )
			{
				$this->_message("Sorry, Stoped to install xoopsunit.");
				exit;
			}

			$this->_mkdir($testDir);
		}

		$this->_mkdir($testDir. DIRECTORY_SEPARATOR .'Coverage');
		$this->_mkdir($testDir. DIRECTORY_SEPARATOR .'YourModule');
		$this->_makeGitIgnore($testDir);
		$this->_makePhpUnitXml($testDir);
		$this->_makeBootstrap($testDir);
		$this->_makeComposerJson($testDir);
		$this->_makeSampleTestCase($testDir);
		$this->_printNextStep($testDir);
	}

	protected function _askString($question, $default)
	{
		try
		{
			echo "$question [$default]: ";
			return $this->_readline($default);
		}
		catch ( Exception $e )
		{
			echo '...'.$e->getMessage(), PHP_EOL;
			return $this->_askString($question, $default);
		}
	}

	protected function _askYesNo($question, $default = 'yes')
	{
		try
		{
			echo "$question [$default]: ";
			return $this->_readlineYesNo($default);
		}
		catch ( Exception $e )
		{
			echo '...'.$e->getMessage(), PHP_EOL;
			return $this->_askYesNo($question, $default);
		}
	}

	protected function _readline($default = '')
	{
		$input = trim(fgets(fopen('php://stdin', 'r')));

		if ( $input === '' )
		{
			return $default;
		}

		return $input;
	}

	protected function _readlineYesNo($default = 'yes')
	{
		$yesNo = $this->_readline();
		$yesNo = strtolower($yesNo);

		if ( $yesNo === '' )
		{
			return $default;
		}

		if ( in_array($yesNo, array('yes', 'no')) )
		{
			return $yesNo;
		}

		throw new RuntimeException('Please, yes or no');
	}

	protected function _message($message)
	{
		echo $message, PHP_EOL;
	}

	protected function _error($message)
	{
		file_put_contents('php://stderr', $message.PHP_EOL);
		exit(1);
	}

	protected function _info($message)
	{
		$this->_message('[info] '.$message);
	}

	protected function _mkdir($dir)
	{
		if ( file_exists($dir) )
		{
			return;
		}

		if ( @mkdir($dir) === false )
		{
			$error = error_get_last();
			$this->_error(sprintf("Failed to make directory: %s\n%s", $dir, $error['message']));
		}
	}

	protected function _makeGitIgnore($testDir)
	{
		if ( $this->_askYesNo("Do you want to add 'Coverage' directory and 'vendor' directory to gitignore?") === 'yes' )
		{
			$gitignoreFile = $testDir . DIRECTORY_SEPARATOR . '.gitignore';
			file_put_contents($gitignoreFile, '/Coverage/*'."\n", FILE_APPEND);
			file_put_contents($gitignoreFile, '/vendor/*'."\n", FILE_APPEND);
		}
	}

	protected function _makePhpUnitXml($testDir)
	{
		$xmlFile = $testDir. DIRECTORY_SEPARATOR . 'phpunit.xml.dist';

		if ( file_exists($xmlFile) === true )
		{
			return;
		}

		file_put_contents($xmlFile, $this->_getPhpUnitXml());
	}

	protected function _makeBootstrap($testDir)
	{
		$bootstrapFile = $testDir . DIRECTORY_SEPARATOR . 'Bootstrap.php';

		if ( file_exists($bootstrapFile) === true )
		{
			return;
		}

		file_put_contents($bootstrapFile, $this->_getBootstrapContents());
	}

	protected function _makeComposerJson($testDir)
	{
		$composerJsonFile = $testDir . DIRECTORY_SEPARATOR . 'composer.json';

		if ( file_exists($composerJsonFile) === true )
		{
			return;
		}

		file_put_contents($composerJsonFile, $this->_getComposerJsonContents());
	}

	protected function _makeSampleTestCase($testDir)
	{
		$sampleTestCase = $testDir . DIRECTORY_SEPARATOR . 'YourModule' . DIRECTORY_SEPARATOR . 'SampleTest.php';

		if ( file_exists($sampleTestCase) === true )
		{
			return;
		}

		file_put_contents($sampleTestCase, $this->_getSampleTestCase());
	}

	protected function _getPhpUnitXml()
	{
		return '<?xml version="1.0" encoding="UTF-8"?>
<phpunit
	bootstrap="Bootstrap.php"
	processIsolation="false"
	verbose="true"
	strict="false"
	syntaxCheck="true"
	colors="true">
	<testsuites>
		<testsuite name="PHPUnit">
			<directory>YourModule</directory>
		</testsuite>
	</testsuites>

	<logging>
		<log
			type="coverage-html"
			target="Coverage"
			charset="UTF-8"
			yui="true"
			highlight="false"
			lowUpperBound="35"
			highLowerBound="70" />
		<log type="testdox-html" target="Coverage/testdox.html" />
		<log type="testdox-text" target="Coverage/testdox.txt" />
	</logging>

	<filter>
		<whitelist>
			<directory suffix=".php">../html/modules/your_module</directory>
			<!-- file>/path/to/file</file -->
			<exclude>
				<!-- directory suffix="Interface.php">../Source</directory -->
				<!-- file>../Public/index.php</file -->
			</exclude>
		</whitelist>
		<blacklist>
			<!-- directory suffix=".php" group="PHPUNIT">../Vendor</directory -->
		</blacklist>
	</filter>
</phpunit>
';
	}

	protected function _getBootstrapContents()
	{
		ob_start();
		echo "<?php\n";
?>
if ( version_compare(PHP_VERSION, '5.3.0', '<') )
{
	echo "This tests requires PHP 5.3.0 or later", PHP_EOL;
	exit(1);
}

// Load composer autoloader
require_once __DIR__ . '/vendor/autoload.php';
<?php
		return ob_get_clean();
	}

	protected function _getComposerJsonContents()
	{
		return '{
	"require": {
		"php":            ">=5.3.2",
		"EHER/PHPUnit":   ">=1.6",
		"suin/xoopsunit": ">=1.2"
	}
}';
	}

	protected function _getSampleTestCase()
	{
		ob_start();
		echo "<?php\n";
		?>
class YourModule_SampleTest extends \XoopsUnit\TestCase
{
	public function testSample()
	{
		$this->assertTrue(true);
	}
}
	<?php
		return ob_get_clean();
	}

	protected function _printNextStep($testDir)
	{
		$testDirectory = basename($testDir);
		$this->_message("");
		$this->_message("Install composer if you don't have:");
		$this->_message('    $ curl -s http://getcomposer.org/installer | php');
		$this->_message("");
		$this->_message("Run composer and install depending packages:");
		$this->_message('    $ cd '.$testDirectory.' && composer install');
		$this->_message("    or");
		$this->_message('    $ cd '.$testDirectory.' && composer.phar install');
		$this->_message("");
		$this->_message("Execute phpunit if you finished to install depending packages:");
		$this->_message("    \$ cd $testDirectory");
		$this->_message('    $ ./vendor/bin/phpunit');
	}
}



