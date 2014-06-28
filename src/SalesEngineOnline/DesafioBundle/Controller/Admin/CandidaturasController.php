<?php

namespace SalesEngineOnline\DesafioBundle\Controller\Admin;

use SalesEngineOnline\DesafioBundle\Controller\AdminController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Exception;
use SalesEngineOnline\DesafioBundle\Helper\FetchLocations;

/**
 * Description of CandidaturasController
 *
 * @author Pedro Resende <pedroresende@mail.resende.biz>
 */
class CandidaturasController extends AdminController {

    public function listarcandidaturaAction($id) {
        parent::verifyaccess();

        $locations = new FetchLocations();
        $listOfLocations = $locations->fetch();

        $choices = array();
        foreach ($listOfLocations as $location) {
            $key = $location[0];
            $value = $location[1];
            $choices[$key] = $value;
        }

        // Verify if the section don't exists yet
        $candidato = $this->getDoctrine()->getRepository('SalesEngineOnlineDesafioBundle:Candidato')->find($id);

        return $this->render('SalesEngineOnlineDesafioBundle:candidaturas:listar.html.twig', array(
                    'title' => 'Listar Candidatura',
                    'candidato' => $candidato,
                    'choices' => $choices
        ));
    }

    public function candidaturasAction($option = NULL, $id = NULL) {
        parent::verifyaccess();

        $locations = new FetchLocations();
        $listOfLocations = $locations->fetch();

        $choices = array();
        foreach ($listOfLocations as $location) {
            $key = $location[0];
            $value = $location[1];
            $choices[$key] = $value;
        }

        $status = NULL;
        $error = NULL;
        switch ($option) {
            case 'remove': {
                    $this->removeCandidatura($id, $status, $error);
                    break;
                }
            case 'removeselected': {
                    $ids = json_decode($id);
                    $this->removeSelectedCandidaturas($ids, $status, $error);
                    break;
                }
        }

        if ($error != NULL) {
            return new Response($error, Response::HTTP_BAD_REQUEST);
        }
        if ($status != NULL) {
            return new Response($status, Response::HTTP_OK);
        }

        $candidaturasList = $this->getDoctrine()->getRepository('SalesEngineOnlineDesafioBundle:Candidato')->findAll();

        return $this->render('SalesEngineOnlineDesafioBundle:candidaturas:candidaturas.html.twig', array(
                    'candidaturasList' => $candidaturasList,
                    'status' => $status,
                    'error' => $error,
                    'choices' => $choices
        ));
    }

    private function removeCandidatura($id, &$status, &$error) {
        try {
            $em = $this->getDoctrine()->getManager();
            $candidatura = $em->getRepository('SalesEngineOnlineDesafioBundle:Candidato')->find($id);
            if ($candidatura != 'empty') {
                $fileNames = array($candidatura->getCurriculum(), $candidatura->getFotografia());
                $this->removeFile($fileNames);
                $em->remove($candidatura);
                $em->flush();
                $status = 'Candidatura removida com sucesso';
            } else {
                $error = "Erro ao remover a candidatura";
            }
        } catch (Exception $ex) {
            $error = "Erro $ex ao remover a candidatura";
        }
    }

    private function removeSelectedCandidaturas($ids, &$status, &$error) {
        try {
            $em = $this->getDoctrine()->getManager();
            foreach ($ids as $id) {
                $candidatura = $em->getRepository('SalesEngineOnlineDesafioBundle:Candidato')->find($id);
                if ($candidatura != 'empty') {
                    $fileNames = array($candidatura->getCurriculum(), $candidatura->getFotografia());
                    $this->removeFile($fileNames);
                    $em->remove($candidatura);
                    $em->flush();
                    $status = 'Candidatura(s) removida(s) com sucesso';
                } else {
                    $error = "Erro ao remover a(s) candidatura(s)";
                }
            }
        } catch (Exception $ex) {
            $error = "Erro $ex ao remover a(s) candidatura(s)";
        }
    }

    private function removeFile(array $fileNames) {
        $folder = $this->container->getParameter('uploaded_files');
        foreach ($fileNames as $filename) {
            @unlink($folder . '/' . $filename);
        }
    }

    public function exportToCsvAction() {
        $em = $this->getDoctrine()->getManager();
        $candidaturas = $em->getRepository('SalesEngineOnlineDesafioBundle:Candidato')->findAll();

        header("Content-type: text/csv");
        header("Content-Disposition: attachment; filename=candidaturas.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        $locations = new FetchLocations();
        $listOfLocations = $locations->fetch();

        $choices = array();
        foreach ($listOfLocations as $location) {
            $key = $location[0];
            $value = $location[1];
            $choices[$key] = $value;
        }

        $outstream = fopen("php://output", 'w');
        fwrite($outstream, "Nome , Telefone, Data de Nascimento, Localidade, E-mail, E-mail Amigo,");
        fwrite($outstream, "\n");
        foreach ($candidaturas as $candidatura) {
            fwrite($outstream, $candidatura->getNome() . "," .
                    $candidatura->getTelefone() . "," .
                    $candidatura->getNascimento()->format('d-m-Y') . "," .
                    $choices[$candidatura->getLocalidade()] . "," .
                    $candidatura->getEmail() . "," .
                    $candidatura->getEmailAmigo() . ",");
            fwrite($outstream, "\n");
        }
        fclose($outstream);

        return new Response('', Response::HTTP_OK);
    }

    public function exportToExcelAction() {
        $em = $this->getDoctrine()->getManager();
        $candidaturas = $em->getRepository('SalesEngineOnlineDesafioBundle:Candidato')->findAll();

        $locations = new FetchLocations();
        $listOfLocations = $locations->fetch();

        $choices = array();
        foreach ($listOfLocations as $location) {
            $key = $location[0];
            $value = $location[1];
            $choices[$key] = $value;
        }

        $phpExcelObject = $this->get('phpexcel')->createPHPExcelObject();

        $phpExcelObject->getProperties()->setCreator("SEO")
                ->setLastModifiedBy("SEO")
                ->setTitle("Candidatos")
                ->setSubject("Candidatos")
                ->setDescription("List de candidatos")
                ->setKeywords("candidatos")
                ->setCategory("candidator");
        $phpExcelObject->setActiveSheetIndex(0)
                ->setCellValue('A1', 'Nome')
                ->setCellValue('B1', 'Telefone')
                ->setCellValue('C1', 'Data de Nascimento')
                ->setCellValue('D1', 'Localidade')
                ->setCellValue('E1', 'E-mail')
                ->setCellValue('F1', 'E-mail Amigo');
        $line = 2;
        foreach ($candidaturas as $candidatura) {
            $phpExcelObject->setActiveSheetIndex(0)
                    ->setCellValue('A'.$line, $candidatura->getNome())
                    ->setCellValue('B'.$line, $candidatura->getTelefone())
                    ->setCellValue('C'.$line, $candidatura->getNascimento()->format('d-m-Y'))
                    ->setCellValue('D'.$line, $choices[$candidatura->getLocalidade()])
                    ->setCellValue('E'.$line, $candidatura->getEmail())
                    ->setCellValue('F'.$line, $candidatura->getEmailAmigo());
            $line++;
        }
        $phpExcelObject->getActiveSheet()->setTitle('Simple');
        // Set active sheet index to the first sheet, so Excel opens this as the first sheet
        $phpExcelObject->setActiveSheetIndex(0);

        // create the writer
        $writer = $this->get('phpexcel')->createWriter($phpExcelObject, 'Excel5');
        // create the response
        $response = $this->get('phpexcel')->createStreamedResponse($writer);
        // adding headers
        $response->headers->set('Content-Type', 'text/vnd.ms-excel; charset=utf-8');
        $response->headers->set('Content-Disposition', 'attachment;filename=candidatos.xls');
        $response->headers->set('Pragma', 'public');
        $response->headers->set('Cache-Control', 'maxage=1');

        return $response;
    }

}
