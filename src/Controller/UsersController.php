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
use Doctrine\Common\Collections\Collection;

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

    public function create (User $data)
    {
        $this->validator->validate($data);
        
        $customer = $this->security->getUser();

        $user = $data->setCustomer($customer);

        $user = $this->userRepository->add($user, true);

        return $user;
    }

    public function delete (User $data)
    {
        $customer = $this->security->getUser();

        $userId = $data->getId();

        $user = $this->userRepository->findOneBy(['id' => $userId, 'customer' => $customer]);

        if (!$user) {
            return $this->json(['error' => 'User not found for id (' . $userId . ').'], 404);
        } else {
            $this->userRepository->delete($user);
            return $this->json(['success' => 'User deleted'], 200);
        }
    }
}
