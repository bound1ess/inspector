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

        foreach ($this->markers as $file => $lines) {
            foreach ($lines as $line) {
                if ( ! in_array($line - 1, $this->markers[$file], true)) {
                    $markers[$file][] = $line;
                }
            }

            $markers[$file] = array_unique($markers[$file]);
        }

        return $markers;
    }
}
