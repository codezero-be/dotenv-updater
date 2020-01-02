<?php

namespace CodeZero\DotEnvUpdater;

class DotEnvUpdater
{
    /**
     * Path to the .env file.
     *
     * @var string
     */
    protected $envPath;

    /**
     * Create a new DotEnvUpdater instance.
     *
     * @param $envPath
     */
    public function __construct($envPath)
    {
        $this->envPath = $envPath;
    }

    /**
     * Create or update a key/value pair in the .env file.
     *
     * @param string $key
     * @param string|int|bool $value
     *
     * @return void
     */
    public function set($key, $value)
    {
        $env = $this->getEnvFileContents();
        $match = $this->findKeyInEnv($key, $env);

        $content = $match
            ? $this->replaceExistingLine($key, $value, $env)
            : PHP_EOL.$this->formatKeyValuePair($key, $value);

        $append = $match ? 0 : FILE_APPEND;

        $this->writeToEnvFile($content, $append);
    }

    /**
     * Get the value of the given key in the .env file.
     *
     * @param string $key
     *
     * @return string
     */
    public function get($key)
    {
        $env = $this->getEnvFileContents();
        $match = $this->findKeyInEnv($key, $env);
        $value = $this->extractValue($match, $key);

        return $match ? $this->restoreValue($value) : '';
    }

    /**
     * Get the contents of the .env file.
     *
     * @return string
     */
    protected function getEnvFileContents()
    {
        if ( ! file_exists($this->envPath)) {
            return '';
        }

        return file_get_contents($this->envPath) ?: '';
    }

    /**
     * Find the given key in the .env file contents
     * and return the matching line.
     *
     * @param string $key
     * @param string $env
     *
     * @return string|null
     */
    protected function findKeyInEnv($key, $env)
    {
        preg_match($this->keyReplacementPattern($key), $env, $matches);

        if (count($matches) === 0) {
            return null;
        }

        return $matches[0];
    }

    /**
     * Extract the value of a .env file line.
     *
     * @param string $line
     * @param string $key
     *
     * @return false|string
     */
    protected function extractValue($line, $key)
    {
        return substr($line, strlen($key) + 1);
    }

    /**
     * Convert .env values back to their original types.
     *
     * @param string $value
     *
     * @return string|int|bool
     */
    protected function restoreValue($value)
    {
        if (strtolower($value) === 'null') return null;
        if (strtolower($value) === 'true') return true;
        if (strtolower($value) === 'false') return false;

        return trim($value, "\"");
    }

    /**
     * Replace an existing line in the .env file contents.
     *
     * @param string $key
     * @param string|int|bool $value
     * @param string $env
     *
     * @return string
     */
    protected function replaceExistingLine($key, $value, $env)
    {
        return preg_replace_callback($this->keyReplacementPattern($key), function () use ($key, $value) {
            return $this->formatKeyValuePair($key, $value);
        }, $env);
    }

    /**
     * Format the key/value pair for the .env file.
     *
     * @param string $key
     * @param string|int|bool $value
     *
     * @return string
     */
    protected function formatKeyValuePair($key, $value)
    {
        return "{$key}={$this->stringifyValue($value)}";
    }

    /**
     * Convert the given value to text so
     * it can be written to the .env file.
     *
     * @param string|int|bool $value
     *
     * @return string
     */
    protected function stringifyValue($value)
    {
        if (is_string($value) && ! empty($value)) {
            return '"'.$value.'"';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        if (is_null($value)) {
            return 'null';
        }

        return $value;
    }

    /**
     * Write content to the .env file.
     *
     * @param string $content
     * @param int $append
     *
     * @return void
     */
    protected function writeToEnvFile($content, $append = 0)
    {
        file_put_contents($this->envPath, $content, $append);
    }

    /**
     * Get a regex pattern that will match env APP_KEY with any random key.
     *
     * @param string $key
     *
     * @return string
     */
    protected function keyReplacementPattern($key)
    {
        return "/^{$key}=[^\r\n]*/m";
    }
}
