<?php

namespace App\Controller;

use App\Entity\Civility;
use App\Entity\Country;
use App\Entity\User;
use App\Entity\Customer;
use App\Entity\Justification;
use App\Entity\Location;
use Doctrine\ORM\ORMException;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;

class ApiController extends AbstractController
{
    /**
     * @Route("/api/auth", name="auth")
     */
    public function index(Request $request, UsersRepository $user, UserPasswordEncoderInterface $encoder)
    {
        // data = {"login":"xxxxx@email.ci","password":"xxxxxx"}
        // wrong login code 901
        // worng password code 902
        // connexion code 200


        try {
            if ($request->getMethod() != 'POST') {
                return $this->json([
                    'status' => Response::HTTP_METHOD_NOT_ALLOWED,
                    'message' => 'Méthode non autorisée'
                ], Response::HTTP_METHOD_NOT_ALLOWED);
            }

            try {
                $data = json_decode($request->getContent(), true);
            } catch (JsonException $exception) {
                return $this->json(
                    [
                        "hasError" => true,
                        "count" => 0,
                        'status' => [
                            "code" => "500",
                            "message" => "wrong syntaxe"
                        ],
                        "item" => []
                    ]
                );
            }

            $csrf = $this->container->get('security.csrf.token_manager');
            $token = $csrf->refreshToken('csrf_token');
            $data += [$token->getId() => $token->getValue()];

            $databaseUser = $user->findOneBy(['login' => $data['login']]);


            $response = [
                "hasError" => false,
                "count" => 0,
                'status' => [
                    "code" => "901",
                    "message" => "wrong login"
                ],
                "item" => []
            ];

            if (!$databaseUser) return $this->json($response);


            $isPassword = $encoder->isPasswordValid($databaseUser, $data['password']);

            $response = [
                "hasError" => false,
                "count" => 0,
                'status' => [
                    "code" => "902",
                    "message" => "wrong password"
                ],
                "item" => []
            ];

            if (!$isPassword) return $this->json($response);



            $response = [
                "hasError" => false,
                "count" => 0,
                "status" => [
                    "code" => "200",
                    "message" => "welcome"
                ],
                "item" => [
                    "id" => $databaseUser->getId(),
                    "login" => $databaseUser->getLogin(),
                    "lastname" => $databaseUser->getFirstname(),
                    "email" => $databaseUser->getMail(),
                    "phone" => $databaseUser->getPhone(),
                    "roles" => $databaseUser->getRoles()
                ],
            ];

            return $this->json($response);
        } catch (NotEncodableValueException $e) {
            return  $response = [
                "hasError" => true,
                "count" => 0,
                'status' => [
                    "code" => "404",
                    "message" => "wrong json format"
                ],
                "item" => []
            ];
        }
    }

    /**
     * @Route("/api/customer", name="customer")
     */
    public function customer(Request $request, ValidatorInterface $validator, EntityManagerInterface $manager)
    {
        //auth fail code 401
        //wrong json format code 400
        //wrong user code 401
        //wrong civility code 402
        //error de la validation des datas code 500
        //error de persistance des data code 800


        if ($request->getMethod() != 'POST') {
            return $this->json([
                'status' => Response::HTTP_METHOD_NOT_ALLOWED,
                'message' => 'Méthode non autorisée'
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = json_decode($request->getContent());
        } catch (JsonException $exception) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "400",
                        "message" => "wrong syntaxe"
                    ],
                    "item" => []
                ]
            );
        }

        $userId = $data->userId;

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $userId]);


        if (!$user) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "401",
                        "message" => "authenfication fail"
                    ],
                    "item" => []
                ]
            );
        }

        $civilityId = $data->civilityId;
        $civility = $this->getDoctrine()->getRepository(Civility::class)->findOneBy(["id" => $civilityId]);
        
        if (!$civility) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "402",
                        "message" => "civility not exist"
                    ],
                    "item" => []
                ]
            );
        }
        
        

        $customer = new Customer();
        $customer->setFirstname($data->firstname)
                 ->setLastname($data->lastname)
                 ->setBirth(new \DateTime(strtotime($data->birth)))
                 ->setBirthPlace($data->birthPlace)
                 ->setNationality($data->nationality)
                 ->setCivility($civility)
                 ->setUser($user);

        $errors = $validator->validate($customer);

        if (count($errors) > 0) {
            $response = [

                "hasError" => true,
                "count" => 0,
                "status" => [
                    "code" => "500",
                    "message" => "erreur de validation des données"
                ],
                "item" => []
            ];
            return $this->json($response);
        }

        try {
            $status = $manager->persist($customer);
            $manager->flush();
            $customerId = $customer->getId();
        } catch (ORMException $error) {
            $response = [
                "hasError" => true,
                "count" => 0,
                'status' => [
                    "code" => "800",
                    "message" => "erreur d'enregistrement en base de données"
                ],
                "item" => []
            ];
            return $this->json($response);
        }

        $response = [
            "hasError" => false,
            "count" => 0,
            'status' => [
                "code" => "200",
                "message" => "custumer create",
            ],
            "item" => [
                "customerId" => $customerId
            ]
        ];
        return $this->json($response);
    }

    /**
     * @Route("/api/location", name="location")
     */
    public function location(Request $request, ValidatorInterface $validator, EntityManagerInterface $manager)
    {
        //auth fail code 401
        //wrong json format code 400
        //wrong user code 401
        //wrong customer code 402
        //wrong country code 403
        //error data validation code 500
        //error data flush code 800

        if ($request->getMethod() != 'POST') {
            return $this->json([
                'status' => Response::HTTP_METHOD_NOT_ALLOWED,
                'message' => 'Méthode non autorisée'
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = json_decode($request->getContent());
        } catch (JsonException $exception) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "400",
                        "message" => "wrong syntaxe"
                    ],
                    "item" => []
                ]
            );
        }

        $userId = $data->userId;

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $userId]);


        if (!$user) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "401",
                        "message" => "authenfication fail"
                    ],
                    "item" => []
                ]
            );
        }

        $customerId = $data->customerId;

        $customer = $this->getDoctrine()->getRepository(Customer::class)->findOneBy(["id" => $customerId]);


        if (!$customer) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "402",
                        "message" => "Customer not exist"
                    ],
                    "item" => []
                ]
            );
        }

        $countryId = $data->countryId;
        $country = $this->getDoctrine()->getRepository(Country::class)->findOneBy(['id'=>$countryId]);

        if (!$country) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "403",
                        "message" => "Country not exist"
                    ],
                    "item" => []
                ]
            );
        }


        $location = new Location();
        $location->setCountry($country)
                 ->setCity($data->city)
                 ->setTown($data->town)
                 ->setDistrict($data->district)
                 ->setAddition($data->addition)
                 ->setCustomer($customer);

        $errors = $validator->validate($location);

        if (count($errors) > 0) {
            $response = [
                "hasError" => true,
                "count" => 0,
                'status' => [
                    "code" => "700",
                    "message" => "error data validation"
                ],
                "item" => []
            ];
            return $this->json($response);
        }

        try {
            $status = $manager->persist($location);
            $manager->flush();
        } catch (ORMException $error) {
            $response = [
                "hasError" => true,
                "count" => 0,
                'status' => [
                    "code" => "800",
                    "message" => "error data flush"
                ],
                "item" => []
            ];
            return $this->json($response);
        }

        $response = [
            "hasError" => false,
            "count" => 0,
            'status' => [
                "code" => "200",
                "message" => "customer location created"
            ],
            "item" => [
                "locationId" => $location->getId()
            ]
        ];
        return $this->json($response);
    }

    /**
     * @Route("/api/eligible", name="eligible")
     */
    public function eligible(Request $request, ValidatorInterface $validator, EntityManagerInterface $manager)
    {
        //auth fail code 401
        //wrong json format code 400
        //wrong user code 401
        //wrong location code 402
        //error data validation code 500
        //error data flush code 800

        if ($request->getMethod() != 'POST') {
            return $this->json([
                'status' => Response::HTTP_METHOD_NOT_ALLOWED,
                'message' => 'Méthode non autorisée'
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = json_decode($request->getContent());
        } catch (JsonException $exception) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "400",
                        "message" => "wrong syntaxe"
                    ],
                    "item" => []
                ]
            );
        }

        $userId = $data->userId;

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $userId]);


        if (!$user) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "401",
                        "message" => "authenfication fail"
                    ],
                    "item" => []
                ]
            );
        }

        $customerId = $data->customerId;

        $customer = $this->getDoctrine()->getRepository(Customer::class)->findOneBy(["id" => $customerId]);

        if (!$customer) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "404",
                        "message" => "Customer not exist"
                    ],
                    "item" => []
                ]
            );
        }

        $locationId = $data->locationId;

        $location = $this->getDoctrine()->getRepository(Location::class)->findOneBy(["id" => $locationId]);

        if (!$location) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "402",
                        "message" => "Location not exist"
                    ],
                    "item" => []
                ]
            );
        }

        $location->setLatitude($data->latitude)
                 ->setLongitude($data->longitude)
                 ->setEligible($data->eligible);

        $errors = $validator->validate($location);

        if (count($errors) > 0) {
            $response = [
                "hasError" => true,
                "count" => 0,
                'status' => [
                    "code" => "500",
                    "message" => "error datas validation"
                ],
                "item" => []
            ];
            return $this->json($response);
        }

        try {
            $status = $manager->persist($location);
            $manager->flush();
        } catch (ORMException $error) {
            $response = [
                "hasError" => false,
                "count" => 0,
                'status' => [
                    "code" => "800",
                    "message" => "error data flush"
                ],
                "item" => []
            ];
            return $this->json($response);
        }

        $response = [
            "hasError" => false,
            "count" => 0,
            'status' => [
                "code" => "200",
                "message" => "customer location created"
            ],
            "item" => []
        ];
        return $this->json($response);
    }

    /**
     * @Route("/api/justification", name="justification")
     */
    public function justification(Request $request, ValidatorInterface $validator, EntityManagerInterface $manager)
    {
        if ($request->getMethod() != 'POST') {
            return $this->json([
                'status' => Response::HTTP_METHOD_NOT_ALLOWED,
                'message' => 'Méthode non autorisée'
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        try {
            $data = json_decode($request->getContent());
        } catch (JsonException $exception) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "500",
                        "message" => "wrong syntaxe"
                    ],
                    "item" => []
                ]
            );
        }

        $userId = $data->userId;

        $user = $this->getDoctrine()->getRepository(User::class)->findOneBy(["id" => $userId]);


        if (!$user) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "404",
                        "message" => "authenfication fail"
                    ],
                    "item" => []
                ]
            );
        }

        $customerId = $data->customerId;

        $customer = $this->getDoctrine()->getRepository(Customer::class)->findOneBy(["id" => $customerId]);

        if (!$customer) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "404",
                        "message" => "Customer not exist"
                    ],
                    "item" => []
                ]
            );
        }

        $justification =  new Justification();
        // $justification->setType($data->type)
        //     ->setIdentifier($data->identifier)
        //     ->setEmission(new \DateTime(strtotime($data->emission)))
        //     ->setExpiration(new \DateTime(strtotime($data->expiration)))
        //     ->setCountry($data->country)
        //     ->setFile($data->file)
        //     ->setCustomer($customer);

        try {
            $status = $manager->persist($justification);
            $manager->flush();
        } catch (ORMException $error) {
            $response = [
                "hasError" => true,
                "count" => 0,
                'status' => [
                    "code" => "800",
                    "message" => "erreur d'enregistrement en base"
                ],
                "item" => []
            ];
            return $this->json($response);
        }


        $response = [
            "hasError" => false,
            "count" => 0,
            'status' => [
                "code" => "200",
                "message" => "customer justification create"
            ],
            "item" => [
                "justificationId" => $justification->getId()
            ]
        ];
        return $this->json($response);
    }
}
