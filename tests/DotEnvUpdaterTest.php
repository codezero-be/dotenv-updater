<?php

namespace CodeZero\DotEnvUpdater\Tests;

use CodeZero\DotEnvUpdater\DotEnvUpdater;

class DotEnvUpdaterTest extends TestCase
{
    /** @test */
    public function it_gets_values_from_an_env_file()
    {
        $this->createEnvFile([
            'EXISTING_KEY_STRING="String Value"',
            'EXISTING_KEY_INT=25',
            'EXISTING_KEY_TRUE=true',
            'EXISTING_KEY_FALSE=false',
            'EXISTING_KEY_TRUE_CAPS=TRUE',
            'EXISTING_KEY_FALSE_CAPS=FALSE',
            'EXISTING_KEY_NULL=null',
            'EXISTING_KEY_NULL_CAPS=NULL',
        ]);

        $this->assertFileExists($this->getEnvPath());

        $updater = new DotEnvUpdater($this->getEnvPath());

        $this->assertEquals('String Value', $updater->get('EXISTING_KEY_STRING'));
        $this->assertEquals(25, $updater->get('EXISTING_KEY_INT'));
        $this->assertTrue($updater->get('EXISTING_KEY_TRUE'));
        $this->assertFalse($updater->get('EXISTING_KEY_FALSE'));
        $this->assertTrue($updater->get('EXISTING_KEY_TRUE_CAPS'));
        $this->assertFalse($updater->get('EXISTING_KEY_FALSE_CAPS'));
        $this->assertNull($updater->get('EXISTING_KEY_NULL'));
        $this->assertNull($updater->get('EXISTING_KEY_NULL_CAPS'));
    }

    /** @test */
    public function it_adds_a_key_and_value_to_a_new_env_file()
    {
        $this->deleteEnvFile();
        $this->assertFileNotExists($this->getEnvPath());

        $updater = new DotEnvUpdater($this->getEnvPath());

        $updater->set('NEW_KEY_STRING', 'New String');
        $updater->set('NEW_KEY_INT', 25);
        $updater->set('NEW_KEY_TRUE', true);
        $updater->set('NEW_KEY_FALSE', false);
        $updater->set('NEW_KEY_NULL', null);

        $this->assertFileExists($this->getEnvPath());

        $this->assertEquals('New String', $updater->get('NEW_KEY_STRING'));
        $this->assertEquals(25, $updater->get('NEW_KEY_INT'));
        $this->assertTrue($updater->get('NEW_KEY_TRUE'));
        $this->assertFalse($updater->get('NEW_KEY_FALSE'));
        $this->assertNull($updater->get('NEW_KEY_NULL'));

        $this->assertEnvFileContents([
            'NEW_KEY_STRING="New String"',
            'NEW_KEY_INT=25',
            'NEW_KEY_TRUE=true',
            'NEW_KEY_FALSE=false',
            'NEW_KEY_NULL=null',
        ]);
    }

    /** @test */
    public function it_adds_a_key_and_value_to_an_existing_env_file()
    {
        $this->createEnvFile([
            'EXISTING_KEY_STRING="String Value"',
        ]);

        $updater = new DotEnvUpdater($this->getEnvPath());

        $updater->set('NEW_KEY_STRING', 'New String');
        $updater->set('NEW_KEY_INT', 25);
        $updater->set('NEW_KEY_TRUE', true);
        $updater->set('NEW_KEY_FALSE', false);
        $updater->set('NEW_KEY_NULL', null);

        $this->assertEquals('String Value', $updater->get('EXISTING_KEY_STRING'));
        $this->assertEquals(25, $updater->get('NEW_KEY_INT'));
        $this->assertEquals('New String', $updater->get('NEW_KEY_STRING'));
        $this->assertTrue($updater->get('NEW_KEY_TRUE'));
        $this->assertFalse($updater->get('NEW_KEY_FALSE'));
        $this->assertNull($updater->get('NEW_KEY_NULL'));

        $this->assertEnvFileContents([
            'EXISTING_KEY_STRING="String Value"',
            'NEW_KEY_STRING="New String"',
            'NEW_KEY_INT=25',
            'NEW_KEY_TRUE=true',
            'NEW_KEY_FALSE=false',
            'NEW_KEY_NULL=null',
        ]);
    }

    /** @test */
    public function it_replaces_the_value_of_an_existing_key()
    {
        $this->createEnvFile([
            'FIRST_EXISTING_KEY="String Value"',
            'SECOND_EXISTING_KEY=true',
            'THIRD_EXISTING_KEY=25',
            'FOURTH_EXISTING_KEY=null',
        ]);

        $updater = new DotEnvUpdater($this->getEnvPath());

        $updater->set('FIRST_EXISTING_KEY', 999);
        $updater->set('SECOND_EXISTING_KEY', 'New String Value');
        $updater->set('THIRD_EXISTING_KEY', null);
        $updater->set('FOURTH_EXISTING_KEY', false);

        $this->assertEquals(999, $updater->get('FIRST_EXISTING_KEY'));
        $this->assertEquals('New String Value', $updater->get('SECOND_EXISTING_KEY'));
        $this->assertNull($updater->get('THIRD_EXISTING_KEY'));
        $this->assertFalse($updater->get('FOURTH_EXISTING_KEY'));

        $this->assertEnvFileContents([
            'FIRST_EXISTING_KEY=999',
            'SECOND_EXISTING_KEY="New String Value"',
            'THIRD_EXISTING_KEY=null',
            'FOURTH_EXISTING_KEY=false',
        ]);
    }

    /** @test */
    public function it_sets_an_empty_value()
    {
        $this->createEnvFile([
            'EXISTING_KEY="String Value"',
        ]);

        $updater = new DotEnvUpdater($this->getEnvPath());

        $updater->set('EXISTING_KEY', '');
        $updater->set('NEW_KEY', '');

        $this->assertEquals('', $updater->get('EXISTING_KEY'));
        $this->assertEquals('', $updater->get('NEW_KEY'));

        $this->assertEnvFileContents([
            'EXISTING_KEY=',
            'NEW_KEY=',
        ]);
    }

    /** @test */
    public function it_sets_a_value_with_dollar_sign()
    {
        $this->createEnvFile([
            'EXISTING_KEY="String Value"',
        ]);

        $updater = new DotEnvUpdater($this->getEnvPath());

        $updater->set('EXISTING_KEY', '$2y$10$mQZg');

        $this->assertEquals('$2y$10$mQZg', $updater->get('EXISTING_KEY'));

        $this->assertEnvFileContents([
            'EXISTING_KEY="$2y$10$mQZg"',
        ]);
    }

    /**
     * Assert that the .env file contains the given lines of content.
     *
     * @param array $contents
     *
     * @return void
     */
    protected function assertEnvFileContents(array $contents)
    {
        $this->assertEquals(
            join(PHP_EOL, $contents),
            trim(file_get_contents($this->getEnvPath()))
        );
    }

    /**
     * Delete the test .env file if it exists.
     *
     * @return void
     */
    protected function deleteEnvFile()
    {
        if (file_exists($this->getEnvPath())) {
            unlink($this->getEnvPath());
        }
    }

    /**
     * Copy .env.stub to .env.
     *
     * @param array $lines
     *
     * @return void
     */
    protected function createEnvFile(array $lines = [])
    {
        file_put_contents($this->getEnvPath(), join(PHP_EOL, $lines));
    }

    /**
     * Get the path to the test .env file.
     *
     * @return string
     */
    protected function getEnvPath()
    {
        return __DIR__ . '/temp/.env';
    }

    /**
     * Cleanup after each test.
     *
     * @return void
     */
    protected function tearDown(): void
    {
        $this->deleteEnvFile();

        parent::tearDown();
    }
}
