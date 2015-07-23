<?php

namespace Oktolab\IntakeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConfigControllerTest extends WebTestCase
{
    public function testShowUploadForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/de');
        $this->assertGreaterThan(0, $crawler->filter('html:contains(INTAKE)')->count());
    }
}
