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

        $storage->useFile("foo");

        $storage->execute(123);
        $storage->execute(124);
        $storage->execute(124);

        $storage->expect(123);
        $storage->expect(125);
        $storage->expect(126);

        expect(this($storage->getDeadMarkers()))
            ->to_be_equal_to([["foo", 125]])->go();
    }
}
