<?php

namespace FitbitOAuth\ClientBundle\Service;
use FitbitOAuth\ClientBundle\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
/**
 *
 * Service that provides email utilities
 */
class EmailService {
    protected $from_address;
    protected $container;

    public function __construct(Container $container, $from_address) {
        $this->from_address = $from_address;
        $this->container=$container;
    }

    public function sendWelcomeEmail(User $user) {

        $email = $user->getEmail();
        $profile_data = $user->getUserProfileData();
        $email_template = "Emails/welcome.html.twig";
        //print_r($profile_data); exit;

        try {
            $message = \Swift_Message::newInstance()
                        ->setSubject('Welcome to Morning Mocha')
                        ->setFrom($this->from_address)
                        ->setTo($email)
                        ->setContentType("text/html")
                        ->setBody(
                            $this->container
                                 ->get('templating')
                                ->render( $email_template,
                                          array('name' => $profile_data->displayName)
                                ), 'text/html'
                          );

            $response = $this->container->get('mailer')->send($message);
            if($response) {
                $user->setStatus(User::STATUS_EMAIL_PROCESSED);
                $this- >container()->getDoctrine()->getManager()->flush();
            } else {
                 throw new Exception("Error sending email to $email with message: ".$e->getMessage());
            }
            return $response;
        } catch(Exception $e) {
            throw new Exception("Error sending email to $email with message: ".$e->getMessage());
        }
    }
    public function sendGreetingEmail(User $user) {
        $email = $user->getEmail();
        $profile_data = $user->getUserProfileData();
        $email_template = "Emails/greeting.html.twig";
        //print_r($profile_data); exit;

        try {
            $sleep_data =  $this->getSleepData($user);
            $quote =  $this->getRandomQuote();
            //print_r($sleep_data); exit;
            $message = \Swift_Message::newInstance()
                        ->setSubject('Welcome to Morning Mocha')
                        ->setFrom($this->from_address)
                        ->setTo($email)
                        ->setContentType("text/html")
                        ->setBody(
                            $this->container
                                 ->get('templating')
                                ->render( $email_template,
                                          array(
                                                'name'  => $profile_data->displayName,
                                                'sleep' => $sleep_data,
                                                'quote' => $quote 
                                          )
                                ), 'text/html'
                          );

            $response = $this->container->get('mailer')->send($message);
            return $response;
        } catch(Exception $e) {
            throw new Exception("Error sending email to $email with message: ".$e->getMessage());
        }
    }
    protected function getRandomQuote() {
        return "this is a random quote";
    }
    protected function getSleepData($user) {
        $oauth_client = $this->container->get("fitbit_oauth_client");
        $fitbithandler = new FitbitDataHandler($user, $oauth_client);
        $sleep_data = $fitbithandler->getActivityData('sleep');
        if($sleep_data!=null) {
            $minutes_slept = (int)$sleep_data['totalMinutesAsleep'];
            $hours_slept = round($minutes_slept/60);
            return $hours_slept;
        } else {
            return null;
        }
    }
}
