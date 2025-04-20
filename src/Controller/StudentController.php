<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use App\Form\ChangePasswordType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class StudentController extends AbstractController
{
    #[Route('/dashboard', name: 'student_dashboard')]
    public function dashboard(): Response
    {
        /** @var Student $student */
        $student = $this->getUser();

        return $this->render('student/dashboard.html.twig', [
            'student' => $student,
        ]);
    }

    #[Route('/profile/edit', name: 'student_edit_profile')]
    public function editProfile(Request $request, EntityManagerInterface $entityManager): Response
    {
        /** @var Student $student */
        $student = $this->getUser();

        $form = $this->createForm(StudentType::class, $student);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash('success', 'Profile updated successfully!');
            return $this->redirectToRoute('student_dashboard');
        }

        return $this->render('student/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/profile/password', name: 'student_change_password')]
    public function changePassword(
        Request $request,
        EntityManagerInterface $entityManager,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        /** @var Student $student */
        $student = $this->getUser(); // ✅ টাইপ হিন্ট করা হয়েছে

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currentPassword = $form->get('currentPassword')->getData();
            $newPassword = $form->get('newPassword')->getData();

            if (!$passwordHasher->isPasswordValid($student, $currentPassword)) {
                $this->addFlash('error', 'Current password is incorrect.');
            } else {
                $hashedPassword = $passwordHasher->hashPassword($student, $newPassword);
                $student->setPassword($hashedPassword);
                $entityManager->flush();

                $this->addFlash('success', 'Password updated successfully!');
                return $this->redirectToRoute('student_dashboard');
            }
        }

        return $this->render('student/change_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
