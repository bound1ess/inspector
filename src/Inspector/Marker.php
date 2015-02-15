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
    protected $expected = [];

    /**
     * @var array
     */
    protected $executed = [];

    /**
     * @var string
     */
    protected $file;

    /**
     * @param integer $line
     * @return void
     */
    public function execute($line)
    {
        $this->executed[] = [$this->file, $line];
    }

    /**
     * @return array
     */
    public function getDeadMarkers()
    {
        $markers = [];

        foreach ($this->expected as $marker) {
            if ( ! in_array($marker, $this->executed)) {
                $markers[] = $marker;
            }
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
        if (in_array([$this->file, $line - 1], $this->expected)) {
            return null;
        }

        $this->expected[] = [$this->file, $line];
    }
}
