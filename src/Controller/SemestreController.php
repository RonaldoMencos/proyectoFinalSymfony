<?php

namespace App\Controller;

use App\Entity\Semestre;
use App\Form\SemestreType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SemestreController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getInicio()
    {
        return $this->render('inicio.html.twig');
    }

    public function getSemestres()
    {
        $listSemestres =  $this->em->getRepository(Semestre::class)->findBy([], ['nombre' => 'ASC']);
        return $this->render('semestre/semestres.html.twig', [
            'listSemestres' => $listSemestres
        ]);
    }

    public function createSemestre(Request $request)
    {
        $semestre = new Semestre();
        $formSemestre = $this->createForm(SemestreType::class, $semestre);
        $formSemestre->handleRequest($request);

        if ($formSemestre->isSubmitted() && $formSemestre->isValid()) {
            try {
                $this->em->persist($semestre);
                $this->em->flush();
                $this->addFlash('success', 'Se ha guardado éxitosamente!');
                return $this->redirectToRoute('getSemestres');
            } catch (Exception $e) {
                $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
            }
        }

        return $this->render('semestre/semestre_create.html.twig', [
            'formSemestre' => $formSemestre->createView()
        ]);
    }

    public function updateSemestre(Request $request, $id)
    {
        $semestre = $this->em->getRepository(Semestre::class)->find($id);
        $formSemestre = $this->createForm(SemestreType::class, $semestre);
        $formSemestre->handleRequest($request);

        if ($formSemestre->isSubmitted() && $formSemestre->isValid()) {
            try {
                $this->em->persist($semestre);
                $this->em->flush();
                $this->addFlash('success', 'Se ha guardado éxitosamente!');
                return $this->redirectToRoute('getSemestres');
            } catch (Exception $e) {
                $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
            }
        }

        return $this->render('semestre/semestre_update.html.twig', [
            'formSemestre' => $formSemestre->createView()
        ]);
    }

    public function deleteSemestre($id)
    {
        $semestre = $this->em->getRepository(Semestre::class)->find($id);
        try {
            $this->em->remove($semestre);
            $this->em->flush();
            $this->addFlash('success', 'Se ha eliminado éxitosamente!');
            return $this->redirectToRoute('getSemestres');
        } catch (Exception $e) {
            $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
        }
    }
}
