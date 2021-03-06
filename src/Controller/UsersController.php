<?php

namespace App\Controller;

use ApiPlatform\Core\Bridge\Doctrine\Orm\Paginator;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// import UsersRepository
use App\Repository\UserRepository;

// import User entity
use App\Entity\User;
use Symfony\Component\HttpFoundation\Request;
// import Security component
use Symfony\Component\Security\Core\Security;

use ApiPlatform\Core\Validator\ValidatorInterface;

class UsersController extends AbstractController
{
    private $userRepository;

    private $security;

    private $validator;

    public function __construct(UserRepository $userRepository, Security $security, ValidatorInterface $validator)
    {
        $this->userRepository = $userRepository;
        $this->security = $security;
        $this->validator = $validator;
    }

    public function index (): Paginator
    {
        $request = Request::createFromGlobals();
        $page = $request->query->getInt('page', 1);

        $customer = $this->security->getUser();
        return $this->userRepository->getUsersByPage($customer, $page);
    }

    // function to retrieve a user
    public function show (User $data)
    {
        $customer = $this->security->getUser();

        $userId = $data->getId();

        $user = $this->userRepository->findOneBy(['id' => $userId, 'customer' => $customer]);

        if (!$user) {
            return $this->json(['message' => 'User not found for id (' . $userId . ")."], 404);
        } else {
            return $user;
        }
    }

    public function create ()
    {
        $request = Request::createFromGlobals();
        
        $data = json_decode($request->getContent(), true);

        $user = new User();

        $user->setFirstName($data['first_name']);
        $user->setLastName($data['last_name']);
        $user->setEmail($data['email']);

        $customer = $this->security->getUser();

        $user->setCustomer($customer);

        $errors = $this->validator->validate($user);

        if (isset($errors) && count($errors) > 0) {
            return $this->json($errors, 400);
        }

        $this->userRepository->add($user, true);

        return $user;
    }

    public function delete (User $data)
    {
        try {
            $customer = $this->security->getUser();
    
            $userId = $data->getId();
    
            $user = $this->userRepository->findOneBy(['id' => $userId, 'customer' => $customer]);

            if (!$user) {
                return $this->json(['message' => 'User not found for id (' . $userId . ")."], 404);
            } else {
                $this->userRepository->removeById($userId);
                return $this->json(['message' => 'User deleted.'], 200);
            }

        } catch (\Exception $e) {
            return $this->json(['error' => $e->getMessage()], 500);
        }
    }
}
