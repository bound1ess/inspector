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
}
