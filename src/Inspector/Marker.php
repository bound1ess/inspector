<?php namespace Inspector;

/**
 * Think of it as a "markers" storage.
 */
class Marker
{

    use Behaviors\SingletonBehavior;

    /**
     * @var array
     */
    protected $markers = [];

    /**
     * @param string $file
     * @param integer $line
     * @return void
     */
    public function add($file, $line)
    {
        $this->markers[$file][] = $line;
    }

    /**
     * @param boolean $removeUnnecessary
     * @return array
     */
    public function getAll($removeUnnecessary = false)
    {
        if ( ! $removeUnnecessary) {
            return $this->markers;
        }

        $markers = [];

        foreach ($this->markers as $file) {
            foreach ($file as $line) {
                if ( ! isset($markers[$file]) or ! in_array($line - 1, $markers[$file])) {
                    $markers[$file][] = $line;
                }
            }
        }

        return $markers;
    }
}
