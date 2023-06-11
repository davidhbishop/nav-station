<?php

namespace App\Service;

/**
 * Location Service
 */
class LocationService
{

    private $locationPath;

    private $fileString;

    private $fileJson;


    public function __construct()
    {

    }//end __construct()

    public function setLocationPath(string $locationPath) {
        $this->locationPath = $locationPath;
    }

    /**
     * Get file
     */
    public function getFile(): bool
    {
        if (file_exists($this->locationPath)) {
            $this->fileString = file_get_contents($this->locationPath, FILE_USE_INCLUDE_PATH);
            $this->fileJson = json_decode($this->fileString, true);
            return true;
        }
        return false;

    }//end getFile()

    public function getString(): string
    {
        return $this->fileString;
    }

    public function getJSON(): array
    {
        return $this->fileJson;
    }

    public function getLocations(): array
    {
        $this->getFile();
        return $this->fileJson;

    }


}//end class
