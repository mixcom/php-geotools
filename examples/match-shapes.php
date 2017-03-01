<?php
require_once 'vendor/autoload.php';

use \ShapeFile\ShapeFile;

// Convert GPS coordinates to RD coordinates.
$gpscoords = new \GeoTools\Model\GPSCoordinates(52.274226, 4.748833);
$converter = new \GeoTools\Convert\DefaultRDConverter();
$rdcoords = $converter->convertWGS84ToRD($gpscoords);

// Get a simple 2D point from the coords.
$point = $rdcoords->toPoint();

// Read a shape file.
$file = new ShapeFile('shapes.shp');

$shapeRecordConverter = new \GeoTools\Util\DefaultShapeRecordConverter();

$containmentCalculator = new \GeoTools\Util\DefaultContainmentCalculator();

foreach ($file as $record) {
    if ($record['dbf']['_deleted']) {
        continue;
    }
    if (!isset ($record['shp'])) {
        continue;
    }

    // Get a rings-based shape from the shp data.
    $shape = $shapeRecordConverter->convertRingsBasedShape($record['shp']);

    // Check if the point is within the shape.
    if ($containmentCalculator->inRingsBasedShape($point, $shape)) {
        var_dump($record['dbf']);
    }
}