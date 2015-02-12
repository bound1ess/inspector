<?hh namespace Inspector\CLI\Commands;

class InspectorCommandTest extends \CommandTestCase
{

    /**
     * @test
     */
    public function it_has_the_right_name_and_description()
    {
        $command = new InspectCommand;

        this($command->getName())->should_be_equal("inspect")->go();
        this($command->getDescription())
            ->should_be_equal("Performs code coverage analysis.")->go();
    }
}
