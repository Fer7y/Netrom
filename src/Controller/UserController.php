<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use phpDocumentor\Reflection\Types\Integer;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    #[Route('/HomePage', name: 'app_user')]
    public function index(): Response
    {
        return $this->render('user/index.html.twig', [
            'controller_name' => 'UserController',
        ]);
    }

    #[Route('/user/register', name: 'register_user')]
    public function store(Request $request, UserRepository $userRepository,UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();

        $form = $this->createForm(UserType::class, $user);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // $form->getData() holds the submitted values
            // but, the original `$task` variable has also been updated
            $user = $form->getData();
            $password=$passwordHasher->hashPassword($user , $user->getPassword());
            $user->setPassword($password);
            $user->setRoles(['ROLE_USER']);
            $userRepository->saveUser($user);
            // ... perform some action, such as saving the task to the database

            return $this->redirectToRoute('app_user');
        }

        return $this->render('user/register.html.twig', [
            'form' => $form,
        ]);


    }

    #[Route('/user/{id}', name: 'user_show', requirements: ['id' => '\d+'])]
    public function show(UserRepository $entityManager, $id): Response
    {
        $id = (int)$id;
        $user = $entityManager->find($id);

        if (!$user) {
            throw $this->createNotFoundException(
                'No User found for id ' . $id
            );
        }

        return new Response('Hello user: ' . $user->getEmail());
    }

    #[Route('/user/users', name: 'users_show')]
    public function users(UserRepository $entityManager): Response
    {
        $users = $entityManager->findAll();


        return $this->render('user/users.html.twig', ["users"=>$users]);
    }
    #[Route('/user/users/{userId}', name: 'user_details')]
    public function details(UserRepository $entityManager,$userId): Response
    {
        $user = $entityManager->find($userId);


        return $this->render('user/details.html.twig', ["user"=>$user]);
    }

}
