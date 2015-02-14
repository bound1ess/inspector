<?php namespace Inspector\CLI\Commands;

use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;

use Symfony\Component\Console\Input\InputOption;

class InspectCommand extends \Symfony\Component\Console\Command\Command
{

    public function __construct(\Inspector\Inspector $inspector = null)
    {
        parent::__construct();

        $this->inspector = $inspector;
    }

    protected function configure()
    {
        $this->setName("inspect")->setDescription("Performs code coverage analysis.");

        $this->addOption(
            "src", // Name.
            null, // Shortcut.
            InputOption::VALUE_REQUIRED, // Mode.
            "A source directory to work with.", // Description.
            "src" // Default value.
        );

        $this->addOption(
            "test",
            null,
            InputOption::VALUE_REQUIRED,
            "A directory where the tests are placed.",
            "tests"
        );
    }

    protected function execute(Input $input, Output $output)
    {
        $this->inspector->setTestDir($input->getOption("test"));
        $this->inspector->setSrcDir($input->getOption("src"));

        $output->writeln($this->inspector->copySourceTree());
        $output->writeln($this->inspector->placeMarkers());

        $this->inspector->runTests();

        $output->writeln($this->inspector->analyse());
    }
}
