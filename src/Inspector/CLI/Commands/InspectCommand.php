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
            "dir", // Name.
            null, // Shortcut.
            \Symfony\Component\Console\Input\InputOption::VALUE_REQUIRED, // Mode.
            "A source directory to work with.", // Description.
            "src" // Default value.
        );
    }

    protected function execute(Input $input, Output $output)
    {
        $dir = $input->getOption("dir");

        $output->writeln($this->inspector->copySourceTree($dir));
        $output->write($this->inspector->placeMarkers());
        $output->writeln($this->inspector->runTests());
        // $this->inspector->analyse();
    }
}
