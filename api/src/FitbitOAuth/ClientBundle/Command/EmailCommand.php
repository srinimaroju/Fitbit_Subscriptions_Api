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
        
        // the full command description shown when running the command with
        // the "--help" option
        ->setHelp("This command allows you to send email via fitbit uid");
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
	    // outputs multiple lines to the console (adding "\n" at the end of each line)
	    $output->writeln([
	        'Email Handler',
	        '============',
	        '',
	    ]);
	    $handler = $this->getContainer()->get("email_handler");
	    $em=$this->getContainer()->get('doctrine')->getManager();
	    
	    $repository = $em->getRepository('FitbitOAuth\\ClientBundle\\Entity\\User');
	    $fitbit_uid = $input->getArgument('fitbit_uid');
	    $user=$repository->findOneBy(array('fitbit_uid'=>$fitbit_uid));
	    //$output->writeln(var_export($user));
	    $handler->sendTestEmail($user);
	    // outputs a message followed by a "\n"
	    $output->writeln('Email sent to '.$user->getEmail());
	    
	}
}
