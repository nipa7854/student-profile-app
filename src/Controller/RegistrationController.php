<?php

namespace App\Controller;

use App\Entity\Student;
use App\Form\StudentType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'student_register')]
    public function register(
        Request $request,
        EntityManagerInterface $em,
        UserPasswordHasherInterface $passwordHasher
    ): Response {
        $student = new Student();
        $form = $this->createForm(StudentType::class, $student);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $student->setRole('STUDENT');

            $hashedPassword = $passwordHasher->hashPassword($student, $student->getPassword());
            $student->setPassword($hashedPassword);

            $em->persist($student);
            $em->flush();

            $this->addFlash('success', 'Registration successful! Please login.');
            return $this->redirectToRoute('app_login');
        }

        return $this->render('registration/register.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
