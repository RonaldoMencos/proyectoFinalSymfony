<?php

namespace App\Controller;

use App\Entity\AlumnoNota;
use App\Form\AlumnoNotaType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class AlumnoNotaController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getNotas()
    {
        $listNotas =  $this->em->getRepository(AlumnoNota::class)->findBy([], ['nota' => 'ASC']);
        return $this->render('nota/notas.html.twig', [
            'listNotas' => $listNotas
        ]);
    }

    public function createNota(Request $request)
    {
        $nota = new AlumnoNota();
        $formAlumnoNota = $this->createForm(AlumnoNotaType::class, $nota);
        $formAlumnoNota->handleRequest($request);

        if ($formAlumnoNota->isSubmitted() && $formAlumnoNota->isValid()) {
            try {
                $this->em->persist($nota);
                $this->em->flush();
                $this->addFlash('success', 'Se ha guardado éxitosamente!');
                return $this->redirectToRoute('getNotas');
            } catch (Exception $e) {
                $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
            }
        }

        return $this->render('nota/nota_create.html.twig', [
            'formAlumnoNota' => $formAlumnoNota->createView()
        ]);
    }

    public function updateNota(Request $request, $id)
    {
        $nota = $this->em->getRepository(AlumnoNota::class)->find($id);
        $formAlumnoNota = $this->createForm(AlumnoNotaType::class, $nota);
        $formAlumnoNota->handleRequest($request);

        if ($formAlumnoNota->isSubmitted() && $formAlumnoNota->isValid()) {
            try {
                $this->em->persist($nota);
                $this->em->flush();
                $this->addFlash('success', 'Se ha guardado éxitosamente!');
                return $this->redirectToRoute('getNotas');
            } catch (Exception $e) {
                $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
            }
        }

        return $this->render('nota/nota_update.html.twig', [
            'formAlumnoNota' => $formAlumnoNota->createView()
        ]);
    }

    public function deleteNota($id)
    {
        $nota = $this->em->getRepository(AlumnoNota::class)->find($id);
        try {
            $this->em->remove($nota);
            $this->em->flush();
            $this->addFlash('success', 'Se ha eliminado éxitosamente!');
        } catch (Exception $e) {
            $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
        }
        return $this->redirectToRoute('getNotas');
    }
}
