<?php

namespace SalesEngineOnline\DesafioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;

/**
 * Description of SecurityController
 *
 * @author Pedro Resende <pedroresende@mail.resende.biz>
 */
class AdminController extends Controller {

    /**
     * Controller responsible to show the backoffice main page
     * 
     * @return type the template
     */
    public function backofficeAction() {
        $this->verifyaccess();

        return $this->render(
            'SalesEngineOnlineDesafioBundle::main.html.twig');
    }

    /**
     * This function is responsible to verify is the use with the
     * Role admin is authenticated
     * 
     * @throws AccessDeniedException
     */
    public function verifyaccess() {
        if (false === $this->get('security.context')->isGranted('ROLE_ADMIN')) {
            throw new AccessDeniedException();
        }
    }

}
