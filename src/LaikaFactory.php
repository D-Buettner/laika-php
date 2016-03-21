<?php
namespace medigo\laika;

/**
 * Connection point to the Laika library
 */
class LaikaFactory
{

    public static function createLaika()
    {
        $laikaService = new LaikaService("environment", "host");
        //$laikaService->fetchAllFeatures();
        return $laikaService;
    }
}
