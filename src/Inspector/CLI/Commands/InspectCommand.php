<?php namespace Inspector\CLI\Commands;

use Symfony\Component\Console\Input\InputInterface as Input;
use Symfony\Component\Console\Output\OutputInterface as Output;

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
            \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, // Mode.
            "A source directory to work with.", // Description.
            "src" // Default value.
        );

        $this->addArgument(
            "test",
            \Symfony\Component\Console\Input\InputArgument::REQUIRED,
            "A directory where the tests are placed."
        );
    }

    protected function execute(Input $input, Output $output)
    {
        $this->inspector->setTestDir($input->getArgument("test"));
        $this->inspector->setSrcDir($input->getOption("src"));

        $output->writeln($this->inspector->copySourceTree());
        $output->writeln($this->inspector->placeMarkers());
        $output->writeln($this->inspector->runTests());
        $output->writeln($this->inspector->analyse());
    }
}
