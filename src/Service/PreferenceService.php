<?php

namespace App\Service;

use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Preference Service
 */
class PreferenceService
{
    public function __construct(private RequestStack $requestStack) {

    }

    public function getLocation(){
        return $this->requestStack->getSession()->get('location');
    }

    public function setLocation($location){
        $this->requestStack->getSession()->set('location', strtolower($location));
    }

}