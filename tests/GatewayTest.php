<?php

namespace Omnipay\AlliedWallet;

use Omnipay\Tests\GatewayTestCase;

class GatewayTest extends GatewayTestCase
{
    /** @var  Gateway */
    protected $gateway;

    /** @var  array */
    protected $options;

    public function setUp()
    {
        parent::setUp();

        $this->gateway = new Gateway($this->getHttpClient(), $this->getHttpRequest());

        $this->options = array(
            'siteId'        => '123',
            'amount'        => '10.00',
            'currency'      => 'AUD',
            'transactionId' => '123456',
            'clientIp'      => '123.123.123.123',
            'card'          => $this->getValidCard(),
        );
    }

    public function testPurchaseSuccess()
    {
        $this->setMockHttpResponse('PurchaseSuccess.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('123456', $response->getTransactionReference());
        $this->assertSame('Success', $response->getMessage());
    }

    public function testPurchaseError()
    {
        $this->setMockHttpResponse('PurchaseFailure.txt');

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Failure', $response->getMessage());
    }

    public function testRefundSuccess()
    {
        $this->setMockHttpResponse('RefundSuccess.txt');

        $response = $this->gateway->refund(array('amount' => '400.00', 'transactionReference' => '123456'))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('123456', $response->getTransactionReference());
        $this->assertSame('Success', $response->getMessage());
    }

    public function testRefundError()
    {
        $this->setMockHttpResponse('RefundFailure.txt');

        $response = $this->gateway->refund(array('amount' => '500.00', 'transactionReference' => '123456'))->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Failure', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $this->setMockHttpResponse('CaptureSuccess.txt');

        $response = $this->gateway->capture(array('amount' => '400.00', 'transactionReference' => '123456'))->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('123456', $response->getTransactionReference());
    }

    public function testCaptureError()
    {
        $this->setMockHttpResponse('CaptureFailure.txt');

        $response = $this->gateway->capture(array('amount' => '400.00', 'transactionReference' => 'ch_lfUYEBK14zotCTykezJkfg'))->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Failure', $response->getMessage());
    }
}
