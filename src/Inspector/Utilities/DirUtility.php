<?hh namespace Inspector\Utilities;

class DirUtility
{

    public function exists(string $dir): boolean
    {
        // Somewhat weird.
        return file_exists($dir);
    }
}
