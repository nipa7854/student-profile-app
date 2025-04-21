<?php

namespace App\Controller;

use App\Entity\Admin;
use App\Entity\Student;
use App\Form\AdminType;
use App\Form\StudentType;
use App\Repository\StudentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class AdminController extends AbstractController
{
    #[Route('/admin/register', name: 'admin_register')]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $hasher
    ): Response {
        $admin = new Admin();
        $form = $this->createForm(AdminType::class, $admin);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $hashedPassword = $hasher->hashPassword($admin, $admin->getPassword());
            $admin->setPassword($hashedPassword);
            $admin->setRole('ROLE_ADMIN');
            $em->persist($admin);
            $em->flush();

            $this->addFlash('success', 'Admin registered successfully!');
            return $this->redirectToRoute('admin_login');
        }

        return $this->render('admin/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/dashboard', name: 'admin_dashboard')]
    public function dashboard(StudentRepository $studentRepository): Response
    {
        $students = $studentRepository->findAll();

        return $this->render('admin/dashboard.html.twig', [
            'students' => $students,
        ]);
    }

    #[Route('/admin/student/{id}/edit', name: 'admin_student_edit')]
    public function editStudent(int $id, Request $request, EntityManagerInterface $em): Response
    {
        $student = $em->getRepository(Student::class)->find($id);
        if (!$student) {
            throw $this->createNotFoundException('Student not found');
        }

        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();
            $this->addFlash('success', 'Student updated successfully!');
            return $this->redirectToRoute('admin_dashboard');
        }

        return $this->render('admin/edit_student.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/student/{id}/delete', name: 'admin_student_delete')]
    public function deleteStudent(int $id, EntityManagerInterface $em): Response
    {
        $student = $em->getRepository(Student::class)->find($id);
        if ($student) {
            $em->remove($student);
            $em->flush();
            $this->addFlash('success', 'Student deleted successfully!');
        }

        return $this->redirectToRoute('admin_dashboard');
    }
}
