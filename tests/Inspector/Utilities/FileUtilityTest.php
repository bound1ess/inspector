<?php namespace Inspector\Utilities;

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

    /**
     * @test
     */
    public function it_checks_if_the_given_file_contains_a_class_or_interface_definition()
    {
        $file = new FileUtility;

        this($file->containsDefinition(__FILE__))->should_be_true->go();
        this($file->containsDefinition(uniqid()))->should_not_be_true->go();
        this($file->containsDefinition(INSPECTOR."/composer.json"))->should_be_false->go();
    }

    /**
     * @test
     */
    public function it_writes_to_a_file()
    {
        $file = new FileUtility;
        $fileName = "/tmp/".uniqid();

        $file->write($fileName, "foobar");

        expect(this(file_get_contents($fileName)))->to_be_equal_to("foobar")->go();
    }
}
