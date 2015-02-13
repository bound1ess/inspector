<?php namespace Inspector;

class Inspector
{

    /**
     * @var string
     */
    protected $dest;

    /**
     * @var string
     */
    protected $marker = "<?php __inspectorMarker__(__FILE__, __LINE__);";

    public function __construct(
        Utilities\DirUtility $dir = null,
        Utilities\FileUtility $file = null
    )
    {
        $this->dir = $dir;
        $this->file = $file;

        // @todo inject instead
        $this->parser = new \PhpParser\Parser(new \PhpParser\Lexer);
        $this->marker = $this->parser->parse($this->marker);
    }

    public function copySourceTree($sourceDir)
    {
        $sourceDir = INSPECTOR_WD."/".$sourceDir;

        $this->dest = "/tmp/".substr(md5($sourceDir), 0, 15);

        $this->dir->copy($sourceDir, $this->dest);

        return "<info>Copying the source tree ($sourceDir) into {$this->dest}...</info>";
    }

    public function placeMarkers()
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

    protected function modifyFile($file)
    {
        if (is_null($contents = $this->file->read($file))) {
            return 0;
        }

        $lines = [];
        $modified = 0;

        $ast = $this->parser->parse($contents);

        // Traverse the AST and insert "markers";

        $this->file->write($file, implode(PHP_EOL, $lines));

        return $modified;
    }
}
