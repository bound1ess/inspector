<?hh namespace Inspector\Utilities;

class DirUtilityTest extends \TestCase
{

    /**
     * @test
     */
    public function it_checks_if_the_given_directory_exists()
    {
        $dir = new DirUtility;

        this($dir->exists(uniqid()))->should_be_false->go();
        this($dir->exists(__DIR__))->should_be_true->go();
    }

    /**
     * @test
     */
    public function it_lists_a_directory()
    {
        $dir = new DirUtility;

        this($dir->getFiles(uniqid()))->should_be_null->go();

        this($dir->getFiles(__DIR__))->should_be_an("array")->go();
        this($dir->getFiles(__DIR__))->should_not_be_empty->go();

        this($dir->getFiles(__DIR__, ".asdf"))->should_have_length_of(0)->go();
    }

    /**
     * @test
     */
    public function it_copies_a_directory()
    {
        $dir = new DirUtility;
        $id = uniqid();

        $dir->copy(__DIR__, "/tmp/".$id);
        this($dir->getFiles(__DIR__))->should_be_equal_to($dir->getFiles("/tmp/".$id))->go();
    }
}
