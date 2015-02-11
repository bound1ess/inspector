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

    /**
     * @test
     */
    public function it_reads_a_file()
    {
        $file = new FileUtility;

        this($file->read(uniqid()))->should_be_null->go();
        this($file->read(__FILE__))->should_be_ok->go();
    }
}
