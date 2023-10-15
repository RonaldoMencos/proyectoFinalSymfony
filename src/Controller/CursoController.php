<?php

namespace App\Controller;

use App\Entity\Curso;
use App\Form\CursoType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CursoController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getCursos()
    {
        $listCursos =  $this->em->getRepository(Curso::class)->findBy([], ['nombre' => 'ASC']);
        return $this->render('curso/cursos.html.twig', [
            'listCursos' => $listCursos
        ]);
    }

    public function createCurso(Request $request)
    {
        $curso = new Curso();
        $formCurso = $this->createForm(CursoType::class, $curso);
        $formCurso->handleRequest($request);

        if ($formCurso->isSubmitted() && $formCurso->isValid()) {
            try {
                $this->em->persist($curso);
                $this->em->flush();
                $this->addFlash('success', 'Se ha guardado éxitosamente!');
                return $this->redirectToRoute('getCursos');
            } catch (Exception $e) {
                $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
            }
        }

        return $this->render('curso/curso_create.html.twig', [
            'formCurso' => $formCurso->createView()
        ]);
    }

    public function updateCurso(Request $request, $id)
    {
        $curso = $this->em->getRepository(Curso::class)->find($id);
        $formCurso = $this->createForm(CursoType::class, $curso);
        $formCurso->handleRequest($request);

        if ($formCurso->isSubmitted() && $formCurso->isValid()) {
            try {
                $this->em->persist($curso);
                $this->em->flush();
                $this->addFlash('success', 'Se ha guardado éxitosamente!');
                return $this->redirectToRoute('getCursos');
            } catch (Exception $e) {
                $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
            }
        }

        return $this->render('curso/curso_update.html.twig', [
            'formCurso' => $formCurso->createView()
        ]);
    }

    public function deleteCurso($id)
    {
        $curso = $this->em->getRepository(Curso::class)->find($id);
        try {
            $this->em->remove($curso);
            $this->em->flush();
            $this->addFlash('success', 'Se ha eliminado éxitosamente!');
            return $this->redirectToRoute('getCursos');
        } catch (Exception $e) {
            $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
        }
    }
}
