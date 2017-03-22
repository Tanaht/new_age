<?php

namespace IntervenantBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class VoeuControllerControllerTest extends WebTestCase
{
    public function testSaisir()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/voeux/saisie');
    }

}
