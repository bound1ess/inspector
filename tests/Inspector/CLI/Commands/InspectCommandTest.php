<?hh namespace Inspector\CLI\Commands;

class InspectorCommandTest extends \CommandTestCase
{

    /**
     * @test
     */
    public function it_prints_a_message()
    {
        $input = Map<string, string> {"--dir" => "src"};
        $message = $this->runCommand(new InspectCommand, $input);

        this($message)->should_be_equal_to("Working with src directory...".PHP_EOL)->go();
    }
}
