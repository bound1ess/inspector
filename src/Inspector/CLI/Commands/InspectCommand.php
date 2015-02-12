<?hh namespace Inspector\CLI\Commands;

use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;

use Inspector\Utilities\DirUtility;

class InspectCommand extends \Symfony\Component\Console\Command\Command
{

    public function __construct(protected DirUtility $dir = null)
    {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this->setName("inspect")->setDescription("Performs code coverage analysis.");

        $this->addOption(
            "dir", // Name.
            null, // Shortcut.
            \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, // Mode.
            "A source directory to work with.", // Description.
            "src" // Default value.
        );
    }

    protected function execute(Input $input, Output $output): void
    {
        $dir = $input->getOption("dir");

        $target = $this->copySourceTree($dir, $output);
        // $this->placeMarkers($target);
        // $this->runTests();
        // $this->analyse();
    }

    protected function copySourceTree(string $sourceDir, Output $output): string
    {
        $sourceDir = INSPECTOR_WD."/".$sourceDir;

        $dest = "/tmp/".substr(md5($sourceDir), 0, 15);

        $output->writeln("<info>Copying the source tree ($sourceDir) into $dest...</info>");

        $this->dir->copy($sourceDir, $dest);

        return $dest;
    }
}
