<?php
declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Routing\Annotation\Route;

class UserController extends Controller
{
    /**
     * @Route("/users")
     */
    public function index()
    {
        $userRepository = $this->getDoctrine()->getRepository(User::class);

        return $this->render('users/index.html.twig', [
            'users' => $userRepository->findAll()
        ]);
    }
}
