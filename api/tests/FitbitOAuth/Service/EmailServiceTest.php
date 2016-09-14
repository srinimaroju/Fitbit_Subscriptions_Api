<?php
namespace Tests\FitbitOAuth\Service;

use FitbitOAuth\ClientBundle\Service\EmailService;
use FitbitOAuth\ClientBundle\Entity\User;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class EmailServiceTest extends KernelTestCase
{
	public function setUp() {
		 self::bootKernel();
    	$this->email_service = static::$kernel->getContainer()->get('email_handler');

	}
    public function testMailIsSentAndContentIsOk()
    {
    	/*
        $client = static::createClient();

        // Enable the profiler for the next request (it does nothing if the profiler is not available)
        $client->enableProfiler();

        $crawler = $client->request('POST', '/path/to/above/action');
		*/
		$from_address = "pavandevelops@gmail.com";
        $to_address = 'srinivas.maroju@outlook.com';

		static::$kernel = static::createKernel();
    	static::$kernel->boot();
    	$container = static::$kernel->getContainer();

        $mockUser = new User();
        $mockUser->setEmail($to_address);
        $mockUser->setUserProfileData(array('age'=>30,'displayName'=>'Mockingbird'));

		$this->email_service->sendTestEmail($mockUser);

        $mailCollector = $client->getProfile()->getCollector('swiftmailer');
	
        // Check that an email was sent
        $this->assertEquals(1, $mailCollector->getMessageCount());

        $collectedMessages = $mailCollector->getMessages();
        $message = $collectedMessages[0];

        // Asserting email data
        $this->assertInstanceOf('Swift_Message', $message);
       // $this->assertEquals('Hello Email', $message->getSubject());
        $this->assertEquals($from_address, key($message->getFrom()));
        $this->assertEquals($to_address, key($message->getTo()));
        $this->assertEquals(
            'Hey There. Thanks for subscribing',
            $message->getBody()
        );
    }
}