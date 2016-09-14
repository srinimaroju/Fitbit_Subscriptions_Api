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

    public function sendTestEmail(User $user) {

        $email = $user->getEmail();
        $profile_data = $user->getUserProfileData();

        //print_r($profile_data); exit;

        try {
            $message = \Swift_Message::newInstance()
                        ->setSubject('Welcome to Morning Mocha')
                        ->setFrom($this->from_address)
                        ->setTo($email)
                        ->setContentType("text/html")
                        ->setBody(
                            $this->container->get('templating')->render(
                                    // app/Resources/views/Emails/registration.html.twig
                                    'Emails/welcome.html.twig',
                                    array('name' => $profile_data->displayName)
                                ),
                                'text/html'
                            )
                            /*
                            * If you also want to include a plaintext version of the message
                            ->addPart(
                            $this->renderView(
                            'Emails/registration.txt.twig',
                            array('name' => $name)
                            ),
                            'text/plain'
                            )
                            */
                        ;
            $response = $this-> container->get('mailer')->send($message);
            return $response;
        } catch(Exception $e) {
            throw new Exception("Error sending email to $email with message: ".$e->getMessage());
        }
    }
}
