<?hh

use Symfony\Component\Console\Command\Command;

class CommandTestCase extends TestCase
{

    protected function runCommand(Command $command, Map<string, string> $input) : string
    {
        $stream = fopen("php://memory", "r+");

        $command->run(
            new Symfony\Component\Console\Input\ArrayInput((array)$input),
            new Symfony\Component\Console\Output\StreamOutput($stream)
        );

        rewind($stream);
        return stream_get_contents($stream);
    }
}
