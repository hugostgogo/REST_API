<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// import UsersRepository
use App\Repository\UserRepository;

// import User entity
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
// import Security component
use Symfony\Component\Security\Core\Security;


class UsersController extends AbstractController
{
    private $userRepository;

    private $security;

    public function __construct(UserRepository $userRepository, Security $security)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
    }


    public function index ()
    {
        $customer = $this->security->getUser();

        $users = $this->userRepository->findBy(['customer' => $customer]);
        
        return $users;
    }

    // function to retrieve a user
    public function show (Request $request)
    {
        // get authenticated customer
        $customer = $this->security->getUser();

        // get user id from request
        $userId = $request->get('id');

        // get user from repository
        $user = $this->userRepository->findOneBy(['id' => $userId, 'customer' => $customer]);

        // if user is not found, return 404
        // else return user
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        } else {
            return $user;
        }
    }

    public function create (Request $request)
    {
        $body = $request->toArray();

        $customer = $this->security->getUser();

        $body['customer'] = $customer;

        $user = $this->userRepository->create($body);

        return $user;
    }

    public function delete (Request $request)
    {
        $id = $request->get('id');

        // get authenticated customer
        $customer = $this->security->getUser();

        // get user from repository
        $user = $this->userRepository->findOneBy(['id' => $id, 'customer' => $customer]);

        // if user is not found, return 404
        // else remove user
        if (!$user) {
            return $this->json(['error' => 'User not found'], 404);
        } else {
            $this->userRepository->removeById($id);

            return $this->json(['success' => 'User deleted'], 200);
        }
    }
}
