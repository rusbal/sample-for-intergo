<?php

namespace Four13\AmazonMws\ToDb;


interface ToDbInterface
{
    public function saveToDb($fileContents);
}