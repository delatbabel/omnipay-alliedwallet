<?php

namespace Omnipay\AlliedWallet;

use Omnipay\Tests\GatewayTestCase;

class SoapGatewayTest extends GatewayTestCase
{
    /** @var  Gateway */
    protected $gateway;

    /** @var  array */
    protected $options;

    /** @var \PHPUnit_Framework_MockObject_MockObject  */
    protected $mockSoapClient;

    public function setUp()
    {
        parent::setUp();

        $wsdlFile = __DIR__ . '/Mock/AlliedWalletSoapClient.wsdl';
        // fwrite(STDERR, "WDSLFile = $wsdlFile\n");
        $this->mockSoapClient = $this->getMockFromWsdl($wsdlFile, 'AlliedWalletSoapClient');

        $this->gateway = new SoapGateway($this->getHttpClient(), $this->getHttpRequest(), $this->mockSoapClient);
    }

    public function testPurchaseSuccessWithTransactionId()
    {
        $result = new \stdClass();
        $result->State          = 0;
        $result->Status         = 1;
        $result->Message        = 'Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match';
        $result->Technical      = '';
        $result->TransactionID  = 'f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc';

        $wrapper = new \stdClass();
        $wrapper->ExecuteCreditCard2Result = $result;

        $this->mockSoapClient->expects($this->any())
            ->method('ExecuteCreditCard2')
            ->will($this->returnValue($wrapper));

        $this->options = array(
            'siteId'        => '123',
            'amount'        => '10.00',
            'currency'      => 'AUD',
            'transactionId' => '123456',
            'clientIp'      => '123.123.123.123',
            'card'          => $this->getValidCard(),
        );

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc', $response->getTransactionReference());
        $this->assertSame('Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match', $response->getMessage());
    }

    public function testPurchaseSuccessNoTransactionId()
    {
        $result = new \stdClass();
        $result->State          = 0;
        $result->Status         = 1;
        $result->Message        = 'Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match';
        $result->Technical      = '';
        $result->TransactionID  = 'f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc';

        $wrapper = new \stdClass();
        $wrapper->ExecuteCreditCardResult = $result;

        $this->mockSoapClient->expects($this->any())
            ->method('ExecuteCreditCard')
            ->will($this->returnValue($wrapper));

        $this->options = array(
            'siteId'        => '123',
            'amount'        => '10.00',
            'currency'      => 'AUD',
            'clientIp'      => '123.123.123.123',
            'card'          => $this->getValidCard(),
        );

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc', $response->getTransactionReference());
        $this->assertSame('Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match', $response->getMessage());
    }

    public function testPurchaseFailure()
    {
        $result = new \stdClass();
        $result->State          = 0;
        $result->Status         = 2;
        $result->Message        = 'Declined';
        $result->Technical      = '';
        $result->TransactionID  = '';

        $wrapper = new \stdClass();
        $wrapper->ExecuteCreditCardResult = $result;

        $this->mockSoapClient->expects($this->any())
            ->method('ExecuteCreditCard')
            ->will($this->returnValue($wrapper));

        $this->options = array(
            'siteId'        => '123',
            'amount'        => '10.00',
            'currency'      => 'AUD',
            'clientIp'      => '123.123.123.123',
            'card'          => $this->getValidCard(),
        );

        $response = $this->gateway->purchase($this->options)->send();

        $this->assertFalse($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertSame('Declined', $response->getMessage());
        $this->assertSame(2, $response->getCode());
        $this->assertEmpty($response->getTransactionReference());
    }

    public function testAuthorizeSuccessWithTransactionId()
    {
        $result = new \stdClass();
        $result->State          = 1;
        $result->Status         = 1;
        $result->Message        = 'Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match';
        $result->Technical      = '';
        $result->TransactionID  = 'f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc';

        $wrapper = new \stdClass();
        $wrapper->PreauthorizeCreditCard2Result = $result;

        $this->mockSoapClient->expects($this->any())
            ->method('PreauthorizeCreditCard2')
            ->will($this->returnValue($wrapper));

        $this->options = array(
            'siteId'        => '123',
            'amount'        => '10.00',
            'currency'      => 'AUD',
            'transactionId' => '123456',
            'clientIp'      => '123.123.123.123',
            'card'          => $this->getValidCard(),
        );

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc', $response->getTransactionReference());
        $this->assertSame('Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match', $response->getMessage());
    }

    public function testAuthorizeSuccessNoTransactionId()
    {
        $result = new \stdClass();
        $result->State          = 1;
        $result->Status         = 1;
        $result->Message        = 'Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match';
        $result->Technical      = '';
        $result->TransactionID  = 'f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc';

        $wrapper = new \stdClass();
        $wrapper->PreauthorizeCreditCardResult = $result;

        $this->mockSoapClient->expects($this->any())
            ->method('PreauthorizeCreditCard')
            ->will($this->returnValue($wrapper));

        $this->options = array(
            'siteId'        => '123',
            'amount'        => '10.00',
            'currency'      => 'AUD',
            'clientIp'      => '123.123.123.123',
            'card'          => $this->getValidCard(),
        );

        $response = $this->gateway->authorize($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc', $response->getTransactionReference());
        $this->assertSame('Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match', $response->getMessage());
    }

    public function testCaptureSuccess()
    {
        $result = new \stdClass();
        $result->State          = 2;
        $result->Status         = 1;
        $result->Message        = 'Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match';
        $result->Technical      = '';
        $result->TransactionID  = 'f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc';

        $wrapper = new \stdClass();
        $wrapper->CaptureResult = $result;

        $this->mockSoapClient->expects($this->any())
            ->method('Capture')
            ->will($this->returnValue($wrapper));

        $this->options = array(
            'transactionReference'  => 'f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc',
        );

        $response = $this->gateway->capture($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc', $response->getTransactionReference());
        $this->assertSame('Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match', $response->getMessage());
    }

    public function testRefundSuccessNoAmount()
    {
        $result = new \stdClass();
        $result->State          = 4;
        $result->Status         = 1;
        $result->Message        = 'Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match';
        $result->Technical      = '';
        $result->TransactionID  = 'f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc';

        $wrapper = new \stdClass();
        $wrapper->RefundResult = $result;

        $this->mockSoapClient->expects($this->any())
            ->method('Refund')
            ->will($this->returnValue($wrapper));

        $this->options = array(
            'transactionReference'  => 'f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc',
        );

        $response = $this->gateway->refund($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc', $response->getTransactionReference());
        $this->assertSame('Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match', $response->getMessage());
    }

    public function testRefundSuccessWithAmount()
    {
        $result = new \stdClass();
        $result->State          = 4;
        $result->Status         = 1;
        $result->Message        = 'Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match';
        $result->Technical      = '';
        $result->TransactionID  = 'f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc';

        $wrapper = new \stdClass();
        $wrapper->PartialRefundResult = $result;

        $this->mockSoapClient->expects($this->any())
            ->method('PartialRefund')
            ->will($this->returnValue($wrapper));

        $this->options = array(
            'transactionReference'  => 'f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc',
            'amount'                => 5.00,
        );

        $response = $this->gateway->refund($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc', $response->getTransactionReference());
        $this->assertSame('Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match', $response->getMessage());
    }

    public function testVoidSuccess()
    {
        $result = new \stdClass();
        $result->State          = 3;
        $result->Status         = 1;
        $result->Message        = 'Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match';
        $result->Technical      = '';
        $result->TransactionID  = 'f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc';

        $wrapper = new \stdClass();
        $wrapper->VoidResult = $result;

        $this->mockSoapClient->expects($this->any())
            ->method('Void')
            ->will($this->returnValue($wrapper));

        $this->options = array(
            'transactionReference'  => 'f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc',
        );

        $response = $this->gateway->void($this->options)->send();

        $this->assertTrue($response->isSuccessful());
        $this->assertFalse($response->isRedirect());
        $this->assertEquals('f0e5b9f4-ec28-4ff2-aaed-195ec71aefcc', $response->getTransactionReference());
        $this->assertSame('Approved BankAuth: 826914443 CvvResult: M CVVCodeReason: CVV2 Match', $response->getMessage());
    }
}
