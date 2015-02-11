<?hh namespace Inspector\Utilities;

class FileUtilityTest extends \TestCase
{

    /**
     * @test
     */
    public function it_checks_if_the_given_file_exists()
    {
        $file = new FileUtility;

        expect(this($file->exists(uniqid())))->to_be_false->go();
        expect(this($file->exists(__FILE__)))->to_be_true->go();
    }
}
