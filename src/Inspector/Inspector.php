<?hh namespace Inspector;

class Inspector
{

    /**
     * @var string
     */
    protected $dest;

    /**
     * @var string
     */
    protected $marker = "__inspectorMarker__(__FILE__, __LINE__);";

    public function __construct(
        protected Utilities\DirUtility $dir = null,
        protected Utilities\FileUtility $file = null
    )
    {
    }

    public function copySourceTree(string $sourceDir): string
    {
        $sourceDir = INSPECTOR_WD."/".$sourceDir;

        $this->dest = "/tmp/".substr(md5($sourceDir), 0, 15);

        $this->dir->copy($sourceDir, $this->dest);

        return "<info>Copying the source tree ($sourceDir) into {$this->dest}...</info>";
    }

    public function placeMarkers(): string
    {
        $message = "<info>Modifying {$this->dest}...</info>".PHP_EOL;

        foreach ($this->dir->getFiles($this->dest) as $file) {
            $message .= $file;

            if ( ! $this->file->containsClass($file)) {
                $message .= " <comment>Skipped.</comment>".PHP_EOL;

                continue;
            }

            $lines = $this->modifyFile($file);

            $message .= " <info>Done, $lines lines modified.</info>".PHP_EOL;
        }

        return $message;
    }

    protected function modifyFile(string $file): integer
    {
        if (is_null($contents = $this->file->read($file))) {
            return 0;
        }

        $lines = [];
        $modified = 0;

        $stack = new \SplStack();
        array_map([$stack, "push"], array_reverse(explode(PHP_EOL, $contents)));

        while ($stack->count() > 0) {
            $lines[] = $stack->pop().$this->marker;
        }

        $this->file->write($file, implode(PHP_EOL, $lines));

        return $modified;
    }
}
