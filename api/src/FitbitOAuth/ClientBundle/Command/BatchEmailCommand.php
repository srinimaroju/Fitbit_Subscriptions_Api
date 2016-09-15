<?php
/**
 * To execute email commands
 */
namespace FitbitOAuth\ClientBundle\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;

use Symfony\Component\Console\Logger\ConsoleLogger;

use FitbitOAuth\ClientBundle\Entity\User;
use FitbitOAuth\ClientBundle\Entity\Notification;

class BatchEmailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        // the name of the command (the part after "bin/console")
        ->setName('app:process-batch-email')

        // the short description shown while running "php bin/console list"
        ->setDescription('Sends Batch Email')
        ->addArgument('batch_type', InputArgument::REQUIRED, 'welcome or greeting')
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp("This command allows you to process batch emails for all users");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
	    // outputs multiple lines to the console (adding "\n" at the end of each line)
	    $output->writeln([
	        'Batch Email Handler',
	        '====================',
	        '',
	    ]);
	   	$type =  $input->getArgument('batch_type');
	    $function = "process".ucfirst($type)."Emails";
	    $this->$function($output);
	    
	    
	}

	/**
	 * Batch processes welcome emails
	 */
	protected function processWelcomeEmails(OutputInterface $output) {
		 $output->writeln([
	        'Processing Welcome Emails',
	        '====================',
	        '',
	    ]);
		$em = $this->getContainer()->get('doctrine')->getManager();
		$repository = $em->getRepository('FitbitOAuth\\ClientBundle\\Entity\\User');
	    $users = $repository->findBy(array('status'=>User::STATUS_SUBSCRIBED));


		$logger = $this->getContainer()->get('logger');

	    if(!count($users)) {
	    	$logger->info("Nothing to process..exiting");
		    $output->writeln("Nothing to process..exiting");
	    }


	    foreach($users as $user) {
	    	$fitbit_uid = $user->getFitbitUid();
		    $handler = $this->getContainer()->get("email_handler");
		    
		    if($user->getEmail()=="") {
		    	$logger->error("No email address for ".$fitbit_uid);
		    	$output->writeln("No email address for ".$fitbit_uid);
		    } else {
			    try {
			    	$response = $handler->sendWelcomeEmail($user);
			    	if($response) {
				    	$user->setStatus(User::STATUS_EMAIL_PROCESSED);
					    $message = "Welcome Email sent to ".$user->getEmail();
				    } else {
				    	$user->setStatus(User::STATUS_EMAIL_FAILED);
				    	$message = "Failed sending welcome Email to ".$user->getEmail();
				    }
			    }
			    catch(Exception $e) {
			    	$user->setStatus(User::STATUS_EMAIL_FAILED);
			    	$message = "Exception sending welcome Email to ".$user->getEmail(). " with message". $e->getMessage();
			    }
		    }
		    
		    $logger->info($message);
			$output->writeln($message);
			$em->flush();
	    }
	}
	/**
	 * Batch processes greeting emails
	 */
	protected function processGreetingEmails(OutputInterface $output) {
		 $output->writeln([
	        'Processing Greeting Emails',
	        '====================',
	        '',
	    ]);
		$em = $this->getContainer()->get('doctrine')->getManager();
		$repository = $em->getRepository('FitbitOAuth\\ClientBundle\\Entity\\Notification');
	    $user_repository = $em->getRepository('FitbitOAuth\\ClientBundle\\Entity\\User');
	    	
	    $unprocessed_notifications = $repository->findBy(array('status'=> Notification::STATUS_RECEIVED));
	    //print_r($unprocessed_notifications);

		$logger = $this->getContainer()->get('logger');

	    if(!count($unprocessed_notifications)) {
	    	$logger->info("Nothing to process..exiting");
		    $output->writeln("Nothing to process..exiting");
	    }


	    foreach($unprocessed_notifications as $notification) {
			$user = $user_repository->findOneBy(array('fitbit_uid'=>$notification->getFitbitUid())); 
	    
	    	$fitbit_uid = $user->getFitbitUid();
		    $handler = $this->getContainer()->get("email_handler");
		    
		    if($user->getEmail()=="") {
			  //  $notification->setStatus(Notification::STATUS_FAILED_PROCESSING);
		    	$logger->error("No email address for ".$fitbit_uid);
		    	$output->writeln("No email address for ".$fitbit_uid);
		    } else {
			    try {
			    	$response = $handler->sendGreetingEmail($user);
			    	if($response) {
				    	$notification->setStatus(Notification::STATUS_PROCESSED);
					    $message = "Greeting Email sent to ".$user->getEmail();
				    } else {
				    	$notification->setStatus(Notification::STATUS_FAILED_PROCESSING);
				    	$message = "Failed sending greeting Email to ".$user->getEmail();
				    }
			    }
			    catch(Exception $e) {
			    	$notification->setStatus(Notification::STATUS_FAILED_PROCESSING);
			    	$message = "Exception sending greeting Email to ".$user->getEmail(). " with message". $e->getMessage();
			    }
			    
			    
			    $logger->info($message);
				$output->writeln($message);
				$em->flush();
			}
	    }
	}

}
