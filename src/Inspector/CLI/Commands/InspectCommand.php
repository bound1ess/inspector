<?hh namespace Inspector\CLI\Commands;

use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;

class InspectCommand extends \Symfony\Component\Console\Command\Command
{

    public function __construct(protected \Inspector\Inspector $inspector = null)
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

        $output->writeln($this->inspector->copySourceTree($dir));
        $output->writeln($this->inspector->placeMarkers());
        // $this->inspector->runTests();
        // $this->inspector->analyse();
    }
}
