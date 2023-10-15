<?php

namespace App\Controller;

use App\Entity\Alumno;
use App\Form\AlumnoType;
use Doctrine\ORM\EntityManagerInterface;
use Exception;
use PhpParser\Node\Scalar\MagicConst\File;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\File\Exception\FileException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\String\Slugger\SluggerInterface;

class AlumnoController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    public function getAlumnos()
    {
        $listAlumnos =  $this->em->getRepository(Alumno::class)->findBy([], ['nombres' => 'ASC']);
        return $this->render('alumno/alumnos.html.twig', [
            'listAlumnos' => $listAlumnos
        ]);
    }

    public function createAlumno(Request $request, SluggerInterface $slugger)
    {
        $alumno = new Alumno();
        $formAlumno = $this->createForm(AlumnoType::class, $alumno);
        $formAlumno->handleRequest($request);

        if ($formAlumno->isSubmitted() && $formAlumno->isValid()) {
            $file = $formAlumno->get('foto')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('fotos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $alumno->setFoto($newFilename);
            }
            try {
                $this->em->persist($alumno);
                $this->em->flush();
                $this->addFlash('success', 'Se ha guardado éxitosamente!');
                return $this->redirectToRoute('getAlumnos');
            } catch (Exception $e) {
                $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
            }
        }

        return $this->render('alumno/alumno_create.html.twig', [
            'formAlumno' => $formAlumno->createView()
        ]);
    }

    public function updateAlumno(Request $request, $id,SluggerInterface $slugger)
    {
        $alumno = $this->em->getRepository(Alumno::class)->find($id);
        $formAlumno = $this->createForm(AlumnoType::class, $alumno);
        $formAlumno->handleRequest($request);

        if ($formAlumno->isSubmitted() && $formAlumno->isValid()) {
            $file = $formAlumno->get('foto')->getData();

            if ($file) {
                $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
                // this is needed to safely include the file name as part of the URL
                $safeFilename = $slugger->slug($originalFilename);
                $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();

                // Move the file to the directory where brochures are stored
                try {
                    $file->move(
                        $this->getParameter('fotos_directory'),
                        $newFilename
                    );
                } catch (FileException $e) {
                }

                $alumno->setFoto($newFilename);
            }
            try {
                $this->em->persist($alumno);
                $this->em->flush();
                $this->addFlash('success', 'Se ha guardado éxitosamente!');
                return $this->redirectToRoute('getAlumnos');
            } catch (Exception $e) {
                $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
            }
        }

        return $this->render('alumno/alumno_update.html.twig', [
            'formAlumno' => $formAlumno->createView()
        ]);
    }

    public function deleteAlumno($id)
    {
        $alumno = $this->em->getRepository(Alumno::class)->find($id);
        try {
            $this->em->remove($alumno);
            $this->em->flush();
            $this->addFlash('success', 'Se ha eliminado éxitosamente!');
        } catch (Exception $e) {
            $this->addFlash('error', 'Ha ocurrido un error, contacta con soporte!');
        }
        return $this->redirectToRoute('getAlumnos');
    }
}
