<?php
namespace Medtravel\UtilityBundle\Service;

/**
 * Connection point to the Laika library
 */
class Laika
{
    /**
     * Library that queries the Laika server to check the status of features
     *
     * @var LaikaService
     */
    private $laikaService;

    public function __construct($environment, $host)
    {
        $this->laikaService = new LaikaService($environment, $host);
        $this->laikaService->fetchAllFeatures();
    }

    public function isEnabled($featureName)
    {
        return $this->laikaService->isEnabled($featureName);;
    }
}
