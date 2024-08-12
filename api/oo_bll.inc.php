<?php

class BLLUser
{

    // -------CLASS FIELDS------------------
    public $id = null;

    public $firstname;

    public $lastname;

    public $username;

    public $password;

    public $email;

    public $phonenumber;

    public $favedevice;

    public function fromArray(stdClass $passoc)
    {
        foreach ($passoc as $tkey => $tvalue) {
            $this->{$tkey} = $tvalue;
        }
    }
}

class BLLallDevices
{

    // -------CLASS FIELDS------------------
    public $id = null;

    public $devicelist;

    public function fromArray(stdClass $passoc)
    {
        foreach ($passoc as $tkey => $tvalue) {
            $this->{$tkey} = $tvalue;
        }
    }
}

class BLLDevice
{

    // -------CLASS FIELDS------------------
    public $id = null;

    public $devicename;

    public $releasedate;

    public $deviceimage_href;

    public $operatingsystem;

    public $dimensions;

    public $cpucores;

    public $manufacturer;

    public $recommendationscore;

    public $battery;

    public $weight;

    public $camera;

    public $ram;

    public $chip;

    public $reviewhref;

    public $ext1;

    public $ext2;

    public $ext3;

    public $ext1name;

    public $ext2name;

    public $ext3name;

    public $buy1;

    public $buy2;

    public $buy3;

    public $buy1link;

    public $buy2link;

    public $buy3link;

    public $price1;

    public $price2;

    public $price3;

    public function fromArray(stdClass $passoc)
    {
        foreach ($passoc as $tkey => $tvalue) {
            $this->{$tkey} = $tvalue;
        }
    }
}

class BLLUserReviews
{

    // -------CLASS FIELDS------------------
    public $id = null;

    public $deviceid;

    public $firstname;

    public $lastname;

    public $devicerating;

    public $reviewtitle;

    public $reviewdesc;

    public function fromArray(stdClass $passoc)
    {
        foreach ($passoc as $tkey => $tvalue) {
            $this->{$tkey} = $tvalue;
        }
    }
}

class BLLUserScores
{

    // -------CLASS FIELDS------------------
    public $id = null;

    public $deviceid;

    public $devicerating;

    public function fromArray(stdClass $passoc)
    {
        foreach ($passoc as $tkey => $tvalue) {
            $this->{$tkey} = $tvalue;
        }
    }
}

?>