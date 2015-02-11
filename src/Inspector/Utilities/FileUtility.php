<?hh namespace Inspector\Utilities;

class FileUtility
{

    public function exists(string $file): boolean
    {
        return file_exists($file);
    }
}
