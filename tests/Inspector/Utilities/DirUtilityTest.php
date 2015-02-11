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
}
