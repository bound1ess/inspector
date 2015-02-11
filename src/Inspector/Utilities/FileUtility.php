<?hh namespace Inspector\Utilities;

class FileUtility
{

    public function exists(string $file): boolean
    {
        return file_exists($file);
    }

    public function read(string $file): mixed
    {
        if ( ! $this->exists($file)) {
            return null;
        }

        return file_get_contents($file);
    }
}
