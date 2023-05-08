<?php

namespace App\Service;

/**
 * Data Service
 */
class DataService
{

    private $dataPath;

    private $filePath;

    private $date;

    private $time;

    private $type;

    private $subType;

    private $pathSet = false;

    private $fileString;

    private $fileJson;


    public function __construct(string $dataPath)
    {
        $this->dataPath = $dataPath;

    }//end __construct()


    public function setData(string $date, string $time, string $type, string $subType=null)
    {
        $this->date    = $date;
        $this->time    = $time;
        $this->type    = $type;
        $this->subType = $subType;
        $this->setPath();

    }//end setData()


    public function setPath()
    {
        $this->filePath  = $this->dataPath.'/'.$this->date.'/';
        $this->filePath .= $this->time.'-'.$this->type;
        if (isset($this->subType)) {
            $this->filePath .= '-'.$this->subType;
        }
        $this->filePath .= '.json';

        $this->pathSet = true;
        return $this->filePath;

    }//end setPath()


    /**
     * Get file
     */
    public function getFile(): bool
    {
        if ($this->pathSet) {
            $this->fileString = file_get_contents($this->filePath, FILE_USE_INCLUDE_PATH);
            $this->fileJson   = json_decode($this->fileString, true);
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


}//end class
