<?php

namespace App\utils;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Context\Normalizer\ObjectNormalizerContextBuilder;

class Utils
{

    public function toJson($data): string
    {
        //Inicialización de serializador
        $encoders = [new XmlEncoder(), new JsonEncoder()];
        $normalizers = new GetSetMethodNormalizer();
        $normalizers->setIgnoredAttributes(array('userProfile.user'));
        $serializer = new Serializer(array($normalizers), array($encoders));

        // $params = array("groups"=> array("dto"));


        // $context = (new ObjectNormalizerContextBuilder())
        //     ->withGroups('dto')
        //     ->toArray();


        //Conversion a JSON
        $json = $serializer->serialize($data, 'json');

        return $json;
    }

}