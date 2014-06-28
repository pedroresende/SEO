<?php

namespace SalesEngineOnline\DesafioBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use SalesEngineOnline\DesafioBundle\Entity\Candidato;
use SalesEngineOnline\DesafioBundle\Form\CandidatoType;
use SalesEngineOnline\DesafioBundle\Helper\FetchLocations;
use SalesEngineOnline\DesafioBundle\Helper\Age;
use SalesEngineOnline\DesafioBundle\Helper\Upload;
/**
 * Description of LandingPageController
 *
 * @author Pedro Resende <pedroresende@mail.resende.biz>
 */
class LandingPageController extends Controller {

    public function indexAction(Request $request) {
        $candidato = new Candidato();
        $candidatoType = new CandidatoType();

        $locations = new FetchLocations();
        $listOfLocations = $locations->fetch();

        $choices = array();
        foreach ($listOfLocations as $location) {
            $key = $location[0];
            $value = $location[1];
            $choices[$key] = $value;
        }

        $form = $this->createForm($candidatoType, $candidato)
                ->add(
                        'localidade', 'choice', array(
                    'choices' => $choices))
                ->add('enviar', 'submit');

        $form->handleRequest($request);

        $error = null;
        if ($form->isValid()) {
            $formValues = $request->request->get('salesengineonline_desafiobundle_candidato');
            $year = $formValues['nascimento']['year'];
            $month = $formValues['nascimento']['month'];
            $day = $formValues['nascimento']['day'];
            $age = new Age($year, $month, $day);

            if ($age->validate()) {
                $upload = new Upload();
                $fotografia = null;
                $curriculum = null;
                $folder = $this->container->getParameter('uploaded_files');
                $upload->upload($request, $folder, $fotografia, $curriculum);

                $candidato->setFotografia($fotografia);
                $candidato->setCurriculum($curriculum);
                $em = $this->getDoctrine()->getManager();
                $em->persist($candidato);
                $em->flush();
                
                $mail_from = $this->container->getParameter('mail');
                $this->sendMail($mail_from, $mail_from, 'info', $candidato, $choices);
                if($formValues['emailAmigo'] != null) {
                    $this->sendMail($mail_from, $formValues['emailAmigo'], 'tip', $candidato, null);
                }
                
                return $this->render('SalesEngineOnlineDesafioBundle:landing_page:success.html.twig');
            } else {
                $error = 'A idade minima de candidatura é de 21 anos';
            }
        }
        return $this->render(
                        'SalesEngineOnlineDesafioBundle:landing_page:index.html.twig', array(
                    'form' => $form->createView(),
                    'error' => $error
                        )
        );
    }

    private function sendMail($mail_from, $mail_to, $option, $candidato, $choices) {
        switch ($option) {
            case 'info': {
                $subject = 'Novo candidato';
                $template = 'SalesEngineOnlineDesafioBundle:email:info.html.twig';
                break;
            }
            case 'tip': {
                $subject = $candidato->getNome().' recomendou-te uma página';
                $template = 'SalesEngineOnlineDesafioBundle:email:tip.html.twig';
                break;
            }
        }
        $message = \Swift_Message::newInstance()
                ->setSubject($subject)
                ->setFrom($mail_from)
                ->setTo($mail_to)
                ->setBody(
                        $this->renderView(
                            $template , array(
                                'candidato' => $candidato,
                                'choices' => $choices
                            )
                        )
                )
                ->setContentType("text/html")
        ;
        $this->get('mailer')->send($message);
    }

}
