<?php

namespace Transit\WebBundle\Monolog\Processor;

use Transit\WebBundle\Model\Deployment;

/**
 * @author Adam Elsodaney <adam.elso@gmail.com>
 */
class DeploymentProcessor
{
    const DEPLOYMENT_COLLECTION_NAME = 'TransitDeployment';

    /**
     * @param array $record
     *
     * @return array
     */
    public function __invoke(array $record)
    {
        if (!isset($record['context']['deployment'])) {
            $record['deployment'] = null;

            return $record;
        }

        $deployment = $record['context']['deployment'];

        $record['deployment'] = $deployment instanceof Deployment ? $this->createReference($deployment) : null;

        return $record;
    }

    /**
     * @param Deployment $deployment
     *
     * @return mixed
     */
    private function createReference(Deployment $deployment)
    {
        return \MongoDBRef::create(self::DEPLOYMENT_COLLECTION_NAME, $deployment->getId());
    }
}
