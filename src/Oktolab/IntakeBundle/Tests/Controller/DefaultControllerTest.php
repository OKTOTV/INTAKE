<?php

namespace Oktolab\IntakeBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class ConfigControllerTest extends WebTestCase
{
    public function testShowUploadForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/de');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains(INTAKE)')->count());
    }

    public function testShowAbout()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/de/about');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains(Robin Schmidt)')->count());
    }

    public function testShowAboutOKTO()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/de/about_okto');
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains(Fernsehen wie von einem anderen Stern)')->count());
    }
}
