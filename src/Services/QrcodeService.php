<?php


namespace App\Services;

use Endroid\QrCode\Color\Color;
use Endroid\QrCode\Label\Margin\Margin;
use Endroid\QrCode\Encoding\Encoding;
use Endroid\QrCode\Builder\BuilderInterface;
use Endroid\QrCode\ErrorCorrectionLevel\ErrorCorrectionLevelHigh;
use Endroid\QrCode\Label\Alignment\LabelAlignmentCenter;


class QrcodeService
{

    /**
     * @var BuilderInterface
     */
    protected $bulider;
    public function __construct(BuilderInterface $builder)

    {

        $this->bulider = $builder;

    }

    public function qrcode($query)
    {

        $url = 'https://www.google.com/search/';
        $url = 'http://localhost/users/=';

        $objDateTime = new \DateTime('NOW');
        $dateString = $objDateTime->format('d-m-Y H:i:s');




        $result = $this->bulider
            ->data($url.$query)
            ->encoding(new Encoding('UTF-8'))
            ->errorCorrectionLevel(new ErrorCorrectionLevelHigh())
            ->size(400)
            ->margin(10)
            ->labelText($dateString)
            ->build()
        ;

        //generate name
        $namePng = uniqid('', '') . '.png';

        //Save img png
        $result->saveToFile((\dirname(__DIR__,2).'/public/assets/qr-code/'.$namePng));

        return $result->getDataUri();
    }
}