<?hh namespace Inspector\CLI\Commands;

type Input = \Symfony\Component\Console\Input\InputInterface;
type Output = \Symfony\Component\Console\Output\OutputInterface;

class InspectCommand extends \Symfony\Component\Console\Command\Command
{

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
        $output->writeln(sprintf("Working with %s directory...", $input->getOption("dir")));
    }
}
