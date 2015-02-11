<?hh namespace Inspector\Utilities;

class DirUtility
{

    public function exists(string $dir): boolean
    {
        // Somewhat weird.
        return file_exists($dir);
    }

    public function getFiles(string $dir, string $suffix = ".php"): mixed
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
    public function copy(string $source, string $destination): void
    {
        if ( ! $this->exists($destination)) {
            mkdir($destination);
        }

        if ( ! is_null($files = $this->getFiles($source))) {
            foreach ($files as $file) {
                $newName = str_replace("/", "_", substr($file, strlen($source) + 1));

                $this->write($destination."/".$newName, $this->read($file));
            }
        }
    }

    public function write(string $file, string $contents): void
    {
        file_put_contents($file, $contents);
    }
}
