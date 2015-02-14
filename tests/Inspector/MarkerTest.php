<?php namespace Inspector;

class MarkerTest extends \TestCase
{

    /**
     * @test
     */
    public function it_uses_SingletonBehavior_trait()
    {
        expect(this(class_uses("Inspector\Marker")))
            ->to_have_value("Inspector\Behaviors\SingletonBehavior")->go();
    }

    /**
     * @test
     */
    public function it_adds_a_new_marker()
    {
        $storage = new Marker;

        $storage->add("foo", 123);
        $storage->add("foo", 124);
        $storage->add("foo", 124); // intended

        expect(this($storage->getAll()))->to_be_equal_to(["foo" => [123, 124, 124]])->go();
        expect(this($storage->getAll(true)))->to_be_equal_to(["foo" => [123]])->go();
    }
}
