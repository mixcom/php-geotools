<?php
namespace GeoTools\Util;

use GeoTools\Model\BoundingBox2D;
use GeoTools\Model\Point2D;
use GeoTools\Model\Ring2D;
use GeoTools\Model\RingsBasedShape2D;

final class DefaultShapeRecordConverter implements ShapeRecordConverterInterface
{
    /**
     * @inheritDoc
     */
    public function convertRingsBasedShape(array $shp)
    {
        $boundingBox = null;
        if (isset ($shp['bounding_box'])) {
            $boundingBox = $this->convertBoundingBox($shp['bounding_box']);
        }
        if (!isset ($shp['parts']) || !$shp['parts']) {
            throw new \InvalidArgumentException('Shape does not contain parts');
        }
        $rings = [];
        foreach ($shp['parts'] as $partData) {
            if (isset ($partData['rings']) && $partData['rings']) {
                foreach ($partData['rings'] as $ringData) {
                    $rings[] = $this->convertRing($ringData);
                }
            }
        }
        return new RingsBasedShape2D($rings, $boundingBox);
    }

    private function convertRing(array $data)
    {
        if (!isset ($data['points']) || !$data['points']) {
            throw new \InvalidArgumentException('Ring does not contain points');
        }

        $points = [];
        foreach ($data['points'] as $pointData) {
            $points[] = $this->convertPoint($pointData);
        }

        return new Ring2D($points);
    }

    private function convertPoint(array $data)
    {
        if (!isset ($data['x']) || !$data['y']) {
            throw new \InvalidArgumentException('Point does not contain x and y coordinate');
        }

        return new Point2D($data['x'], $data['y']);
    }

    /**
     * @param array $data
     * @return \GeoTools\Model\BoundingBox2D
     */
    private function convertBoundingBox(array $data)
    {
        if (!isset ($data['xmin']) || !isset ($data['xmax']) || !isset ($data['ymin']) || !isset ($data['ymax'])) {
            throw new \InvalidArgumentException('Invalid bounding box data');
        }
        return new BoundingBox2D($data['xmin'], $data['xmax'], $data['ymin'], $data['ymax']);
    }
}
