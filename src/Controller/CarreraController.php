<?php

namespace App\Controller;

use App\Entity\Carrera;
use App\Form\CarreraType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;

class CarreraController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getCarreras()
    {
        $listCarreras =  $this->em->getRepository(Carrera::class)->findBy([], ['nombre' => 'ASC']);
        return $this->render('carrera/carreras.html.twig', [
            'listCarreras' => $listCarreras
        ]);
    }

    public function createCarrera(Request $request)
    {
        $carrera = new Carrera();
        $formCarrera = $this->createForm(CarreraType::class, $carrera);
        $formCarrera->handleRequest($request);

        if ($formCarrera->isSubmitted() && $formCarrera->isValid()) {
            try {
                $this->em->persist($carrera);
                $this->em->flush();
                $this->addFlash('success', 'Se ha guardado éxitosamente!');
                return $this->redirectToRoute('getCarreras');
            } catch (Exception $e) {
                $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
            }
        }

        return $this->render('carrera/carrera_create.html.twig', [
            'formCarrera' => $formCarrera->createView()
        ]);
    }

    public function updateCarrera(Request $request, $id)
    {
        $carrera = $this->em->getRepository(Carrera::class)->find($id);
        $formCarrera = $this->createForm(CarreraType::class, $carrera);
        $formCarrera->handleRequest($request);

        if ($formCarrera->isSubmitted() && $formCarrera->isValid()) {
            try {
                $this->em->persist($carrera);
                $this->em->flush();
                $this->addFlash('success', 'Se ha guardado éxitosamente!');
                return $this->redirectToRoute('getCarreras');
            } catch (Exception $e) {
                $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
            }
        }

        return $this->render('carrera/carrera_update.html.twig', [
            'formCarrera' => $formCarrera->createView()
        ]);
    }

    public function deleteCarrera($id)
    {
        $carrera = $this->em->getRepository(Carrera::class)->find($id);
        try {
            $this->em->remove($carrera);
            $this->em->flush();
            $this->addFlash('success', 'Se ha eliminado éxitosamente!');
            return $this->redirectToRoute('getCarreras');
        } catch (Exception $e) {
            $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
        }
    }
}
