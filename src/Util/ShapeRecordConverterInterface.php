<?php
namespace GeoTools\Util;

interface ShapeRecordConverterInterface
{
    /**
     * @param array $shp
     * @return \GeoTools\Model\RingsBasedShape2D
     * @throws \InvalidArgumentException
     *      If the shape is not a rings-based shape.
     */
    public function convertRingsBasedShape(array $shp);
}
