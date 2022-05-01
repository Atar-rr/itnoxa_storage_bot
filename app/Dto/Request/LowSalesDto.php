<?php

namespace App\Dto\Request;

use App\Dto\DtoInterface;
use DateTime;

class LowSalesDto implements DtoInterface
{
    private DateTime $downTimeInterval;

    private DateTime $upperTimeInterval;

    /**
     * @param  DateTime  $downTimeInterval
     * @param  DateTime  $upperTimeInterval
     */
    public function __construct(DateTime $downTimeInterval, DateTime $upperTimeInterval)
    {
        $this->downTimeInterval  = $downTimeInterval;
        $this->upperTimeInterval = $upperTimeInterval;
    }

    /**
     * @return DateTime
     */
    public function getDownTimeInterval(): DateTime
    {
        return $this->downTimeInterval;
    }

    /**
     * @return DateTime
     */
    public function getUpperTimeInterval(): DateTime
    {
        return $this->upperTimeInterval;
    }

    /**
     * @throws \Exception
     */
    public static function fromArray(array $data): DtoInterface
    {
       return new self(
           new DateTime($data['down_date']),
           new DateTime($data['up_date'])
       );
    }
}
