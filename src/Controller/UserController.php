<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/users", name="users_list")
     */
    public function index()
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        return $this->render('users/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }

    /**
     * @Route("/users/create", name="user_create")
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function create(Request $request)
    {
        $form = $this->createFormBuilder(new User())
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('password', PasswordType::class)
            ->add('create', SubmitType::class, ['label' => 'Create user'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $manager = $this->getDoctrine()->getManager();

            $manager->persist($user);
            $manager->flush();

            return $this->redirectToRoute('users_list');
        }

        return $this->render('users/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/{id}/update", name="user_update")
     * @param Request $request
     * @param User $user
     * @return Response
     */
    public function update(Request $request, User $user)
    {
        $form = $this->createFormBuilder($user)
            ->add('username', TextType::class)
            ->add('email', EmailType::class)
            ->add('create', SubmitType::class, ['label' => 'Update user'])
            ->getForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('users_list');
        }

        return $this->render('users/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/users/{id}/remove", name="user_remove")
     * @param User $user
     * @return Response
     */
    public function remove(User $user)
    {
        $manager = $this->getDoctrine()->getManager();

        $manager->remove($user);
        $manager->flush();

        return $this->redirectToRoute('users_list');
    }
}
