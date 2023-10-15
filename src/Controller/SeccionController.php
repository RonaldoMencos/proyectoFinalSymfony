<?php

namespace App\Controller;

use App\Entity\Seccion;
use App\Form\SeccionType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class SeccionController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getSecciones()
    {
        $listSecciones =  $this->em->getRepository(Seccion::class)->findBy([], ['nombre' => 'ASC']);
        return $this->render('seccion/secciones.html.twig', [
            'listSecciones' => $listSecciones
        ]);
    }

    public function createSeccion(Request $request)
    {
        $seccion = new Seccion();
        $formSeccion = $this->createForm(SeccionType::class, $seccion);
        $formSeccion->handleRequest($request);

        if ($formSeccion->isSubmitted() && $formSeccion->isValid()) {
            try {
                $this->em->persist($seccion);
                $this->em->flush();
                $this->addFlash('success', 'Se ha guardado éxitosamente!');
                return $this->redirectToRoute('getSecciones');
            } catch (Exception $e) {
                $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
            }
        }

        return $this->render('seccion/seccion_create.html.twig', [
            'formSeccion' => $formSeccion->createView()
        ]);
    }

    public function updateSeccion(Request $request, $id)
    {
        $seccion = $this->em->getRepository(Seccion::class)->find($id);
        $formSeccion = $this->createForm(SeccionType::class, $seccion);
        $formSeccion->handleRequest($request);

        if ($formSeccion->isSubmitted() && $formSeccion->isValid()) {
            try {
                $this->em->persist($seccion);
                $this->em->flush();
                $this->addFlash('success', 'Se ha guardado éxitosamente!');
                return $this->redirectToRoute('getSecciones');
            } catch (Exception $e) {
                $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
            }
        }

        return $this->render('seccion/seccion_update.html.twig', [
            'formSeccion' => $formSeccion->createView()
        ]);
    }

    public function deleteSeccion($id)
    {
        $seccion = $this->em->getRepository(Seccion::class)->find($id);
        try {
            $this->em->remove($seccion);
            $this->em->flush();
            $this->addFlash('success', 'Se ha eliminado éxitosamente!');
            return $this->redirectToRoute('getSecciones');
        } catch (Exception $e) {
            $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
        }
    }
}
