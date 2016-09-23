<?php

namespace Omnipay\AlliedWallet\Message;

use Omnipay\Tests\TestCase;

class SoapPurchaseRequestTest extends TestCase
{
    /** @var  AbstractRequest */
    protected $request;

    public function setUp()
    {
        $this->request = new SoapPurchaseRequest($this->getHttpClient(), $this->getHttpRequest());
        $this->request->initialize(
            array(
                'siteId'        => '123',
                'amount'        => '10.00',
                'currency'      => 'AUD',
                'transactionId' => '123456',
                'clientIp'      => '123.123.123.123',
            )
        );
    }

    public function testDataWithCard()
    {
        $card = $this->getValidCard();
        $this->request->setCard($card);
        $data = $this->request->getData();

        $this->assertSame($card['number'], $data['CardNumber']);
    }
}
