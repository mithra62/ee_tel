<?php

namespace Mithra62\Tel\Tags;

class Format extends AbstractTag
{
    // Example tag: {exp:phone_number:format}
    public function process()
    {
        $number = $this->param('number');
        if($number) {
            return ee('tel:FormatService')->phone($number);
        }
    }
}
