<?php

namespace App\Inspection;

/*
*Spam Detector
*/

class Spam
{

    protected $inspections = [
        InvalidKeywords::class,
        KeyHeldDown::class
    ];

    public function detect($body)
    {

        foreach ($this->inspections as $inspections) {
            app($inspections)->detect($body);
        }

        return false;
    }



    protected function detectKeyHeldDown($body)
    { }
}
