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

class EmailCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
        // the name of the command (the part after "bin/console")
        ->setName('app:send-email')

        // the short description shown while running "php bin/console list"
        ->setDescription('Sends Email')
        ->addArgument('fitbit_uid', InputArgument::REQUIRED, 'The fitbit uid of the user')
        ->addArgument('type',       InputArgument::REQUIRED, 'welcome or greeting')
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp("This command allows you to send a welcome email via fitbit uid");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
	    // outputs multiple lines to the console (adding "\n" at the end of each line)
	    $output->writeln([
	        'Email Handler',
	        '============',
	        '',
	    ]);

	    $type =  $input->getArgument('type');
	    $function = "send".ucfirst($type)."Email";
	    $handler = $this->getContainer()->get("email_handler");
	    $em=$this->getContainer()->get('doctrine')->getManager();
	    
	    $repository = $em->getRepository('FitbitOAuth\\ClientBundle\\Entity\\User');
	    $fitbit_uid = $input->getArgument('fitbit_uid');
	    $user=$repository->findOneBy(array('fitbit_uid'=>$fitbit_uid));
	    
	    if($user->getEmail()=="") {
	    	$logger->error("No email address for $fitbit_uid");
	    	$output->writeln("No email address for $fitbit_uid");
	    	return;
	    }

	    $handler->$function($user);
	    $logger = $this->getContainer()->get('logger');
	    // outputs a message followed by a "\n"
	    $logger->info("$type Email sent to ".$user->getEmail());
	    $output->writeln("$type Email sent to ".$user->getEmail());
	    
	}
	protected function generateQuote() {
		return "Insert quote here";
	}

}
