<?php

namespace Oktolab\IntakeBundle\Tests\Controller;

use Oktolab\IntakeBundle\Entity\IntakeUser;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class LoginControllerTest extends WebTestCase
{
    public function testShowLoginForm()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $crawler = $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());
        $this->assertGreaterThan(0, $crawler->filter('html:contains(Anmeldung)')->count());
        $this->assertGreaterThan(0, $crawler->filter('html:contains(Benutzername)')->count());
    }

    public function testLogin()
    {
        $client = static::createClient();
        $crawler = $client->request('GET', '/login');
        $crawler = $client->followRedirect();
        $form = $crawler->selectButton('Einloggen')->form(array(
            '_username' => 'admin',
            '_password' => 'secret'
        ));

        $client->submit($form);
        $client->followRedirect();
        $this->assertTrue($client->getResponse()->isSuccessful());
    }
}
