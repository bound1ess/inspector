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
     * @var string
     */
    protected $file;

    /**
     * @param string $file
     * @param integer $line
     * @return void
     */
    public function add($file, $line)
    {
        if (isset ($this->markers[$file]) and in_array($line, $this->markers[$file], true)) {
            unset($this->markers[$file][array_search($line, $this->markers[$file], true)]);

            return null;
        }

        $this->markers[$file][] = $line;
    }

    /**
     * @param boolean $removeUnnecessary
     * @return array
     */
    public function getDeadMarkers($removeUnnecessary = false)
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

    /**
     * @param string $file
     * @return void
     */
    public function useFile($file)
    {
        $this->file = $file;
    }

    /**
     * @param integer $line
     * @return void
     */
    public function expect($line)
    {
        $this->add($this->file, $line);
    }
}
