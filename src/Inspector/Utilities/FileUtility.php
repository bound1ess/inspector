<?php namespace Inspector\Utilities;

class FileUtility
{

    public function exists($file)
    {
        return file_exists($file);
    }

    public function read($file)
    {
        if ( ! $this->exists($file)) {
            return null;
        }

        return file_get_contents($file);
    }

    /**
     * @todo Do something with the name.
     * @param string $file
     * @return boolean
     */
    public function containsClass($file)
    {
        if (is_null($contents = $this->read($file))) {
            return false;
        }

        return (boolean) preg_match("/(interface|class)(\s+)(\w+)/", $contents);
    }

    public function write($file, $contents)
    {
        file_put_contents($file, $contents);
    }
}
