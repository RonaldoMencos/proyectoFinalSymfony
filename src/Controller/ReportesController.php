<?php

namespace App\Controller;

use App\Entity\Alumno;
use App\Entity\AlumnoNota;
use App\Form\SeachAlumnByCarreraType;
use App\Form\SearchAlumnoType;
use App\Form\SearchNotasType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Fill;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class ReportesController extends AbstractController
{
    private EntityManagerInterface $em;

    public function __construct(EntityManagerInterface $em, private RequestStack $requestStack)
    {
        $this->em = $em;
    }

    public function getAllAlumnos(Request $request, SessionInterface $session)
    {
        $formAlumno = $this->createForm(SeachAlumnByCarreraType::class);
        $formAlumno->handleRequest($request);
        $idCarrera = $formAlumno->get('carrera')->getViewData();
        if ($idCarrera != '') {
            $listaAlumnos = $this->em->getRepository(Alumno::class)->findBy(['carrera' => $idCarrera], ['nombres' => 'ASC']);
            $session = $this->requestStack->getSession();
            $session->set('list_alumnos', $listaAlumnos);
            return $this->render('reportes/report-alumnos.html.twig', [
                'listAlumnos' => $listaAlumnos,
                'formAlumno' => $formAlumno->createView()
            ]);
        } else {
            $listaAlumnos = $this->em->getRepository(Alumno::class)->findBy([], ['nombres' => 'ASC']);
            $session = $this->requestStack->getSession();
            $session->set('list_alumnos', $listaAlumnos);
            return $this->render('reportes/report-alumnos.html.twig', [
                'listAlumnos' => $listaAlumnos,
                'formAlumno' => $formAlumno->createView()
            ]);
        }
    }

    public function getExcelAlumnos(SessionInterface $session)
    {
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->setCellValue("A1", "#");
        $sheet->setCellValue("B1", "NOMBRES");
        $sheet->setCellValue("C1", "APELLIDOS");
        $sheet->setCellValue("D1", "FECHA NACIMIENTO");
        $sheet->setCellValue("E1", "CARRERA");

        $style = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
                'name' => 'Century Gothic'
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' =>  Alignment::HORIZONTAL_CENTER
            ]
        ];

        $sheet->getStyle("A1:E1")->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle("A1:E1")->getFill()->getStartColor()->setRGB("012756");
        $session = $this->requestStack->getSession();
        $listaAlumnos = $session->get('list_alumnos');

        for ($i = 0; $i < count($listaAlumnos); $i++) {
            $counter = $i + 2;
            $sheet->setCellValue("A" . $counter, $i + 1);
            $sheet->getStyle("A" . $counter)->getFill()->setFillType(Fill::FILL_SOLID);
            $sheet->getStyle("A" . $counter)->getFill()->getStartColor()->setRGB("012756");
            $sheet->getStyle("A" . $counter)->applyFromArray($style);
            $sheet->setCellValue("B" . $counter, $listaAlumnos[$i]->getNombres());
            $sheet->setCellValue("C" . $counter, $listaAlumnos[$i]->getApellidos());
            $sheet->setCellValue("D" . $counter, $listaAlumnos[$i]->getFechaNacimiento());
            $sheet->setCellValue("E" . $counter, $listaAlumnos[$i]->getCarrera()->getNombre());
        }
        $sheet->getStyle("A1:E1")->applyFromArray($style);
        $sheet->setTitle("Alumnos");
        $sheet->getColumnDimension("B")->setWidth(30);
        $sheet->getColumnDimension("C")->setWidth(30);
        $sheet->getColumnDimension("D")->setWidth(30);
        $sheet->getColumnDimension("E")->setWidth(30);

        $writer = new Xlsx($excel);
        $actualDate = (new \DateTime())->format('d-m-Y');
        $filename = $actualDate . '_reporte_alumnos.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);
        return $this->file($temp_file, $filename, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    public function getAllNotas(Request $request, SessionInterface $session)
    {
        $formNotas = $this->createForm(SearchNotasType::class);
        $formNotas->handleRequest($request);
        $idSemestre = $formNotas->get('semestre')->getViewData();
        $idCurso = $formNotas->get('curso')->getViewData();
        $idSeccion = $formNotas->get('seccion')->getViewData();
        if ($idSemestre != '' || $idCurso != '' || $idSeccion != '') {
            $qb = $this->em->createQueryBuilder();
            $qb->select('u')
                ->from('App\Entity\AlumnoNota', 'u')
                ->where('u.semestre = ' . ($idSemestre != '' ? $idSemestre : '0'))
                ->orWhere('u.curso = ' . ($idCurso != '' ? $idCurso : ' 0'))
                ->orWhere('u.seccion = ' . ($idCurso != '' ? $idCurso : ' 0'));
            $listNotas = $qb->getQuery()->getResult();
            $session = $this->requestStack->getSession();
            $session->set('list_notas', $listNotas);
            return $this->render('reportes/report-notas.html.twig', [
                'listNotas' => $listNotas,
                'formNotas' => $formNotas->createView()
            ]);
        } else {
            $listNotas =  $this->em->getRepository(AlumnoNota::class)->findBy([], ['nota' => 'ASC']);
            $session = $this->requestStack->getSession();
            $session->set('list_notas', $listNotas);
            return $this->render('reportes/report-notas.html.twig', [
                'listNotas' => $listNotas,
                'formNotas' => $formNotas->createView()
            ]);
        }
    }

    public function getExcelNotas(SessionInterface $session)
    {
        $excel = new Spreadsheet();
        $sheet = $excel->getActiveSheet();
        $sheet->setCellValue("A1", "#");
        $sheet->setCellValue("B1", "NOMBRES");
        $sheet->setCellValue("C1", "APELLIDOS");
        $sheet->setCellValue("D1", "SEMESTRE");
        $sheet->setCellValue("E1", "CURSO");
        $sheet->setCellValue("F1", "SECCION");
        $sheet->setCellValue("G1", "NOTA");

        $style = [
            'font' => [
                'bold' => true,
                'color' => ['rgb' => 'FFFFFF'],
                'size' => 12,
                'name' => 'Century Gothic'
            ],
            'alignment' => [
                'vertical' => Alignment::VERTICAL_CENTER,
                'horizontal' =>  Alignment::HORIZONTAL_CENTER
            ]
        ];

        $sheet->getStyle("A1:G1")->getFill()->setFillType(Fill::FILL_SOLID);
        $sheet->getStyle("A1:G1")->getFill()->getStartColor()->setRGB("012756");
        $session = $this->requestStack->getSession();
        $listaNotas = $session->get('list_notas');

        for ($i = 0; $i < count($listaNotas); $i++) {
            $counter = $i + 2;
            $sheet->setCellValue("A" . $counter, $i + 1);
            $sheet->getStyle("A" . $counter)->getFill()->setFillType(Fill::FILL_SOLID);
            $sheet->getStyle("A" . $counter)->getFill()->getStartColor()->setRGB("012756");
            $sheet->getStyle("A" . $counter)->applyFromArray($style);
            $sheet->setCellValue("B" . $counter, $listaNotas[$i]->getAlumno()->getNombres());
            $sheet->setCellValue("C" . $counter, $listaNotas[$i]->getAlumno()->getApellidos());
            $sheet->setCellValue("D" . $counter, $listaNotas[$i]->getSemestre()->getNombre());
            $sheet->setCellValue("E" . $counter, $listaNotas[$i]->getCurso()->getNombre());
            $sheet->setCellValue("F" . $counter, $listaNotas[$i]->getSeccion()->getNombre());
            $sheet->setCellValue("G" . $counter, $listaNotas[$i]->getNota());
        }
        $sheet->getStyle("A1:G1")->applyFromArray($style);
        $sheet->setTitle("Notas");
        $sheet->getColumnDimension("B")->setWidth(30);
        $sheet->getColumnDimension("C")->setWidth(30);
        $sheet->getColumnDimension("D")->setWidth(30);
        $sheet->getColumnDimension("E")->setWidth(30);
        $sheet->getColumnDimension("F")->setWidth(30);
        $sheet->getColumnDimension("G")->setWidth(30);

        $writer = new Xlsx($excel);
        $actualDate = (new \DateTime())->format('d-m-Y');
        $filename = $actualDate .  '_reporte_notas.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $filename);
        $writer->save($temp_file);
        return $this->file($temp_file, $filename, ResponseHeaderBag::DISPOSITION_INLINE);
    }

    public function getAlumnoByFilters(Request $request)
    {
        $formAlumno = $this->createForm(SearchAlumnoType::class);
        $formAlumno->handleRequest($request);
        $idAlumno = $formAlumno->get('alumno')->getViewData();
        if ($idAlumno != '') {
            $alumno = $this->em->getRepository(Alumno::class)->find($idAlumno);
            $listNotas = $this->em->getRepository(AlumnoNota::class)->findBy(['alumno' => $idAlumno], ['nota' => 'ASC']);
            return $this->render('reportes/report-alumno.html.twig', [
                'listNotas' => $listNotas,
                'alumno' => $alumno,
                'formAlumno' => $formAlumno->createView()
            ]);
        } else {
            $alumno = null;
            $listNotas = null;
            return $this->render('reportes/report-alumno.html.twig', [
                'listNotas' => $listNotas,
                'alumno' => $alumno,
                'formAlumno' => $formAlumno->createView()
            ]);
        }
    }
}
