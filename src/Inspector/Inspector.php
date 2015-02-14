<?php namespace Inspector;

/**
 * @codeCoverageIgnore
 */
class Inspector
{

    /**
     * @var string
     */
    protected $dest;

    /**
     * @var string
     */
    protected $src;

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
        $this->marker = $this->parser->parse($this->marker)[0];

        $this->printer = new \PhpParser\PrettyPrinter\Standard;

        $this->traverser = new \PhpParser\NodeTraverser;
        $this->traverser->addVisitor(new NodeVisitor($this->marker));
    }

    /**
     * @return string
     */
    public function analyse()
    {
        $message = PHP_EOL."<info>Performing analysis...</info>".PHP_EOL;

        foreach (Marker::getInstance()->getAll(true) as $file) {
            $message .= "$file:".PHP_EOL;
        }

        return $message;
    }

    /**
     * @return void
     */
    public function runTests()
    {
        // Include Composer autoloader for the project.
        require $this->src."/../vendor/autoload.php";

        // Run PHPUnit.
        if ( ! file_exists($configurationPath = $this->src."/../phpunit.xml")) {
            $configurationPath .= ".dist";
        }

        (new \PHPUnit_TextUI_Command)->run([
            "--configuration=".$configurationPath
        ], false);
    }

    public function copySourceTree($source)
    {
        $sourceDir = INSPECTOR_WD."/".$source;

        if ( ! $this->dir->exists($sourceDir)) {
            throw new \InvalidArgumentException("Directory $sourceDir doesn't exist.");
        }

        $this->dest = "/tmp/".substr(md5($sourceDir), 0, 15);

        $this->dir->copy($sourceDir, $this->dest);

        $this->src = $sourceDir;

        $message = "<info>Copying the source tree ($source) into {$this->dest}...</info>";
        $message .= PHP_EOL;

        return $message;
    }

    public function placeMarkers()
    {
        $message = "<info>Modifying {$this->dest}...</info>".PHP_EOL;

        foreach ($this->dir->getFiles($this->dest) as $file) {
            $message .= $file;

            if ( ! $this->file->containsDefinition($file)) {
                $message .= " <comment>Skipped.</comment>".PHP_EOL;

                continue;
            }

            $this->modifyFile($file);

            $message .= " <info>Done, the file was modified.</info>".PHP_EOL;
        }

        return $message;
    }

    protected function modifyFile($file)
    {
        if (is_null($contents = $this->file->read($file))) {
            return 0;
        }

        try {
            $ast = $this->parser->parse($contents);

            // Traverse the AST and insert "markers".
            $ast = $this->traverser->traverse($ast);

            $this->file->write($file, $this->printer->prettyPrintFile($ast));
        } catch (\PhpParser\Error $exception) {
            throw $exception;
        }
    }
}
