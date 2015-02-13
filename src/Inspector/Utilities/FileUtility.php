<?php namespace Inspector\Utilities;

class FileUtility
{

    /**
     * @param string $file
     * @return boolean
     */
    public function exists($file)
    {
        return file_exists($file);
    }

    /**
     * @param string $file
     * @return string|null
     */
    public function read($file)
    {
        if ( ! $this->exists($file)) {
            return null;
        }

        return file_get_contents($file);
    }

    /**
     * @param string $file
     * @return boolean
     */
    public function containsDefinition($file)
    {
        if (is_null($contents = $this->read($file))) {
            return false;
        }

        return (boolean) preg_match("/(interface|class)(\s+)(\w+)/", $contents);
    }

    /**
     * @param string $file
     * @return boolean
     */
    public function containsInterface($file)
    {
        if (is_null($contents = $this->read($file))) {
            return false;
        }

        return (boolean) preg_match("/interface(\s+)(\w+)/", $contents);
    }

    /**
     * @param string $file
     * @param string $contents
     * @return void
     */
    public function write($file, $contents)
    {
        file_put_contents($file, $contents);
    }
}
