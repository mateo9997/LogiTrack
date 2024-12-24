<?php

namespace App\Tests\Integration;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class AuthApiTest extends WebTestCase
{
    public function testLoginWithInvalidCredentials()
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/login', [], [], ['CONTENT_TYPE'=>'application/json'],
            json_encode([
                'username' => 'invalid',
                'password' => 'wrong'
            ]));
        $this->assertResponseStatusCodeSame(401);
    }

    public function testLoginWithValidCredentials()
    {
        $client = static::createClient();
        $client->request('POST', '/api/auth/login', [], [], ['CONTENT_TYPE'=>'application/json'],
            json_encode([
                'username' => 'adminUser',
                'password' => 'adminPass'
            ]));

        $this->assertResponseStatusCodeSame(200);
        $data = json_decode($client->getResponse()->getContent(), true);
        $this->assertArrayHasKey('token', $data);
    }
}