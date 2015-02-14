<?php namespace Inspector;

use Inspector\Utilities\DirUtility;
use Inspector\Utilities\FileUtility;

use PhpParser\Parser;
use PhpParser\Lexer;
use PhpParser\NodeTraverser;
use PhpParser\PrettyPrinter\Standard;

/**
 * @codeCoverageIgnore
 */
class Inspector
{

    /**
     * @var string
     */
    protected $destDir;

    /**
     * @var string
     */
    protected $srcDir;

    /**
     * @var string
     */
    protected $testDir;

    /**
     * @var string
     */
    protected $marker = "<?php __inspectorMarker__(__FILE__, __LINE__);";

    /**
     * @param Inspector\Utilities\DirUtility $dir
     * @param Inspector\Utilities\FileUtility $file
     * @return object
     */
    public function __construct(DirUtility $dir, FileUtility $file)
    {
        $this->dir = $dir;
        $this->file = $file;

        // @todo inject instead
        $this->parser    = new Parser(new Lexer);
        $this->marker    = $this->parser->parse($this->marker)[0];
        $this->printer   = new Standard;
        $this->traverser = new NodeTraverser;

        $this->traverser->addVisitor(new NodeVisitor($this->marker));
    }

    /**
     * @param string $path
     * @return void
     */
    public function setSrcDir($path)
    {
        $this->srcDir = $path;
    }

    /**
     * @param string $path
     * @return void
     */
    public function setTestDir($path)
    {
        $this->testDir = $path;
    }

    /**
     * @return string
     */
    public function analyse()
    {
        $message = PHP_EOL."<info>Performing analysis...</info>".PHP_EOL;

        foreach (Marker::getInstance()->getAll(true) as $file => $lines) {
            $className = str_replace("_", "\\", substr($file, strlen($this->destDir) + 1));
            $className = substr($className, 0, strlen($className) - 4);

            $message .= sprintf(
                "<info>%s: %s markers were executed:</info>%s",
                $className,
                count($lines),
                PHP_EOL
            );

            $message .= sprintf("    <comment>lines %s.</comment>", implode(", ", $lines));
            $message .= PHP_EOL;
        }

        return $message."Done.";
    }

    /**
     * @return string
     */
    public function runTests()
    {
        // Create a classmap for the source tree.
        $map = [];

        foreach ($this->dir->getFiles($this->destDir) as $file) {
            $class = str_replace("_", "\\", substr($file, strlen($this->destDir) + 1));
            $class = substr($class, 0, strlen($class) - 4); // Remove ".php" suffix.

            $map[$class] = $file;
        }

        $loader = new \Composer\Autoload\ClassLoader;
        $loader->addClassMap($map);

        // Tricky manipulations with autoloaders.
        require $this->srcDir."/../vendor/autoload.php";

        $loader->register(true); // Prepend the autoloader.

        // Build a PHPUnit test suite.
        $suite = new \PHPUnit_Framework_TestSuite;

        foreach ($this->dir->getFiles($this->testDir) as $file) {
            $declaredClasses = get_declared_classes();

            require $file;
            
            $className = array_diff(get_declared_classes(), $declaredClasses);
            $className = end($className);

            if ( ! is_subclass_of($className, "PHPUnit_Framework_TestCase")) {
                continue;
            }

            $suite->addTest(new \PHPUnit_Framework_TestSuite(
                new \ReflectionClass($className)
            ));
        }

        // Now load the "missing" files.
        $files = array_merge(
            $this->dir->getFiles($this->srcDir),
            $this->dir->getFiles($this->testDir)
        );

        foreach ($files as $file) {
            if ( ! $this->file->containsDefinition($file)) {
                require $file;
            }
        }

        // Finally, run the suite.
        \PHPUnit_TextUI_TestRunner::run($suite);
    }

    /**
     * @return string
     */
    public function copySourceTree()
    {
        $this->srcDir = INSPECTOR_WD."/".$this->srcDir;

        if ( ! $this->dir->exists($this->srcDir)) {
            throw new \InvalidArgumentException("Directory {$this->srcDir} doesn't exist.");
        }

        $this->destDir = "/tmp/".substr(md5($this->srcDir), 0, 15);

        $this->dir->copy($this->srcDir, $this->destDir);

        $message = "<info>Copying the source tree into {$this->destDir}...</info>";
        $message .= PHP_EOL;

        return $message;
    }

    /**
     * @return string
     */
    public function placeMarkers()
    {
        $message = "<info>Modifying {$this->destDir}...</info>".PHP_EOL;

        foreach ($this->dir->getFiles($this->destDir) as $file) {
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

    /**
     * @param string $file
     * @throws PhpParser\Error
     * @return void
     */
    protected function modifyFile($file)
    {
        if (is_null($contents = $this->file->read($file))) {
            return null;
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
