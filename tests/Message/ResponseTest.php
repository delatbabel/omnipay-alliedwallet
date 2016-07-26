<?php

namespace Omnipay\AlliedWallet\Message;

use Omnipay\Tests\TestCase;

class ResponseTest extends TestCase
{
    public function testPurchaseSuccess()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseSuccess.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->json());

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('123456', $response->getTransactionReference());
        $this->assertEquals('123456', $response->getTransactionId());
        $this->assertSame('Success', $response->getMessage());
        $this->assertSame('Successful', $response->getCode());
    }

    public function testPurchaseFailure()
    {
        $httpResponse = $this->getMockHttpResponse('PurchaseFailure.txt');
        $response = new Response($this->getMockRequest(), $httpResponse->json());

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Failure', $response->getMessage());
        $this->assertSame('Declined', $response->getCode());
    }
}
