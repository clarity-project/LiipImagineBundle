<?php

namespace Liip\ImagineBundle\Tests\Filter;

use Liip\ImagineBundle\Imagine\Filter\Loader\ScaleFilterLoader;
use Liip\ImagineBundle\Tests\AbstractTest;
use Imagine\Image\Box;

/**
 * Test cases for ScaleFilterLoader class.
 *
 * @covers Liip\ImagineBundle\Imagine\Filter\Loader\ScaleFilterLoader
 *
 * @author Alex Wilson <a@ax.gy>
 */
class ScaleFilterLoaderTest extends AbstractTest
{
    /**
     * @var int
     */
    const DUMMY_IMAGE_WIDTH = 500;

    /**
     * @var int
     */
    const DUMMY_IMAGE_HEIGHT = 600;

    protected function getMockImage()
    {
        $mockImageSize = new Box(
            self::DUMMY_IMAGE_WIDTH,
            self::DUMMY_IMAGE_HEIGHT
        );
        $mockImage = parent::getMockImage();
        $mockImage->method('getSize')->willReturn(new Box(
            self::DUMMY_IMAGE_WIDTH,
            self::DUMMY_IMAGE_HEIGHT
        ));
        return $mockImage;
    }

    /**
     * @covers ScaleFilterLoader::load
     */
    public function testItShouldPreserveRatio()
    {
        $loader = new ScaleFilterLoader();
        $image = $this->getMockImage();
        $image->expects($this->once())
            ->method('resize')
            ->with(new Box(
                self::DUMMY_IMAGE_WIDTH,
                self::DUMMY_IMAGE_HEIGHT
            ))
            ->willReturn($image);

        $result = $loader->load($image, array(
          'to' => 1.0
        ));
    }

    /**
     * @param int[] $dimension
     * @param Box $expected
     *
     * @covers ScaleFilterLoader::load
     *
     * @dataProvider dimensionsDataProvider
     */
    public function testItShouldUseDimensions($dimensions, $expected)
    {
        $loader = new ScaleFilterLoader();

        $image = $this->getMockImage();
        $image->expects($this->once())
            ->method('resize')
            ->with($expected)
            ->willReturn($image);

        $options = array(
            'dim' => $dimensions
        );

        $result = $loader->load($image, $options);
    }

    /**
     * @covers ScaleFilterLoader::load
     *
     * @expectedException \InvalidArgumentException
     */
    public function itShouldThrowInvalidArgumentException()
    {
        (new ScaleFilterLoader('foo', 'bar'))
          ->load($this->getMockImage(), array());
    }


    /**
     * @returns array Array containing coordinate and width/height pairs.
     */
    public function dimensionsDataProvider()
    {
        return array(
            array(array(150, 150), new Box(125, 150)),
            array(array(30, 60), new Box(30, 36)),
            array(array(1000, 1200), new Box(1000, 1200)),
        );
    }
}
