<?php

namespace SalesEngineOnline\DesafioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContext;
use SalesEngineOnline\DesafioBundle\Entity\User;

/**
 * Description of SecurityController
 *
 * @author Pedro Resende <pedroresende@mail.resende.biz>
 */
class SecurityController extends Controller
{

    public function loginAction(Request $request)
    {
        $session = $request->getSession();

        // get the login error if there is one
        if ($request->attributes->has(SecurityContext::AUTHENTICATION_ERROR)) {
            $error = $request->attributes->get(
                    SecurityContext::AUTHENTICATION_ERROR
            );
        } else {
            $error = $session->get(SecurityContext::AUTHENTICATION_ERROR);
            $session->remove(SecurityContext::AUTHENTICATION_ERROR);
        }

        return $this->render(
                        'SalesEngineOnlineDesafioBundle::login.html.twig', array(
                    // last username entered by the user
                    'last_username' => $session->get(SecurityContext::LAST_USERNAME),
                    'error' => $error,
                        )
        );
    }

    public function recoverAction(Request $request)
    {
        $user = new User();

        if ($request->getMethod() == 'POST') {
            if ($request->request->get('_email') != null) {
                $email = $request->request->get('_email');
                $user = $this->getDoctrine()->getRepository('SalesEngineOnlineDesafioBundle:User')->findBy(array('email' => $email));
                if (sizeof($user) > 0) {
                    $this->generatePasswordAndMail($user[0], $email);
                    return $this->render(
                        'SalesEngineOnlineDesafioBundle::recover.html.twig', array(
                            'error' => '',
                            'info' => 'Palavra-chave recuperada com sucesso'
                        )
                    );
                } else {
                    return $this->render(
                        'SalesEngineOnlineDesafioBundle::recover.html.twig', array(
                            'error' => 'E-mail não encontrado',
                            'info' => ''
                        )
                    );
                }
            } else {
                return $this->render(
                    'SalesEngineOnlineDesafioBundle::recover.html.twig', array(
                        'error' => 'É necessario colocar um e-mail',
                        'info' => ''
                    )
                );
            }
        } else {
            return $this->render(
                'SalesEngineOnlineDesafioBundle::recover.html.twig', array(
                    'error' => '',
                    'info' => ''
                )
            );
        }
    }

    private function generatePasswordAndMail( User $user, $email ) {
        $factory = $this->get('security.encoder_factory');
        $encoder = $factory->getEncoder($user);
        $salt = md5(time());
        $user->setSalt($salt);
        $generatepassword = $this->get('xvolutions_admin.misc')->randomPassword();
        $password = $encoder->encodePassword($generatepassword, $salt);
        $user->setPassword($password);
        $em = $this->getDoctrine()->getManager();
        $em->flush();
        $message = \Swift_Message::newInstance()
                ->setSubject('Recuperaçao de palavra-chave')
                ->setFrom($this->container->getParameter('mailer_user'))
                ->setTo($email)
                ->setBody(
                    $this->renderView(
                        'SalesEngineOnlineDesafioBundle:email:recover.html.twig',
                        array(
                            'name' => $user->getName(),
                            'password' => $generatepassword
                        )
                    )
                )
                ->setContentType("text/html")
        ;
        $this->get('mailer')->send($message);
    }

    

}
