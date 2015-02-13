<?php namespace Inspector\Utilities;

class DirUtility
{

    public function __construct(FileUtility $file = null)
    {
        $this->file = $file;
    }

    public function exists($dir)
    {
        // Somewhat weird.
        return file_exists($dir);
    }

    public function getFiles($dir, $suffix = ".php")
    {
        if ( ! $this->exists($dir)) {
            return null;
        }

        $iterator = new \RecursiveIteratorIterator(new \RecursiveDirectoryIterator($dir));
        $files = [];

        foreach ($iterator as $entry) {
            if ( ! $entry->isDir() and preg_match("/.+\\$suffix$/", $entry->getFilename())) {
                $files[] = $entry->getRealPath();
            }
        }

        return $files;
    }

    /**
     * This method will just copy all files stored in $source to $destination.
     * The initial directory structure will be IGNORED.
     */
    public function copy($source, $destination)
    {
        if ( ! $this->exists($destination)) {
            mkdir($destination);
        }

        if ( ! is_null($files = $this->getFiles($source))) {
            foreach ($files as $file) {
                $newName = str_replace("/", "_", substr($file, strlen(realpath($source)) + 1));

                $this->file->write($destination."/".$newName, $this->file->read($file));
            }
        }
    }
}
