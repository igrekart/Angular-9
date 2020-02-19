<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Image;
use App\Entity\Offer;
use App\Entity\OOrder;
use App\Entity\Option;
use App\Entity\Country;
use App\Entity\Payment;
use App\Entity\Civility;
use App\Entity\Customer;
use App\Entity\Identity;
use App\Entity\Location;
use App\Entity\Authority;
use App\Entity\Justification;
use App\Entity\PaymentChoice;
use App\Service\FileUploader;
use Doctrine\ORM\ORMException;
use App\Repository\OrderRepository;
use App\Repository\UsersRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpClient\Exception\JsonException;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Serializer\Exception\NotEncodableValueException;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

class ApiController extends AbstractController
{
    //our order
    private $order;
    const STEP_1 = 1;
    const STEP_2 = 2;
    const STEP_3 = 3;
    const STEP_4 = 4;
    const STEP_5 = 5;
    const STEP_6 = 6;

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
            ->setCivility($civility)
            ->setLastname($data->lastname)
            ->setBirth(new \DateTime(strtotime($data->birth)))
            ->setBirthPlace($data->birthPlace)
            ->setNationality($data->nationality)
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
            return $this->json($response,'200',[], ["groups"=>"Customer::setting"]);
        }

        try {
            $manager->persist($customer);
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
            return $this->json($response,'200',[], ["groups"=>"Customer::setting"]);
        }

        
        try {
            $this->order = new OOrder();
            $this->order->setCustomer($customer)
                        ->setStep(self::STEP_1)
                        ->setReference("7843672108943491");
            $manager->persist($this->order);
            $manager->flush();
        } catch (ORMException $error) {
            $response = [
                "hasError" => true,
                "count" => 0,
                'status' => [
                    "code" => "800",
                    "message" => "Order not exist"
                ],
                "item" => []
            ];
            return $this->json($response, '200',[], ["groups"=>"Customer::setting"]);
        }

        $response = [
            "hasError" => false,
            "count" => 0,
            'status' => [
                "code" => "200",
                "message" => "custumer create",
            ],
            "item" => [
                "customerId" => $customerId,
                "orderId" => $this->order
            ]
        ];
        return $this->json($response,'200',[], ["groups"=>"Customer::setting"]);
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
        $country = $this->getDoctrine()->getRepository(Country::class)->findOneBy(['id' => $countryId]);

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
            $manager->persist($location);
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

        try {
            
            if($data->orderId)
            {
                $ourOrder = $this->getDoctrine()->getRepository(OOrder::class)->findOneBy(["id"=>$data->orderId]);
                $ourOrder->setStep(self::STEP_2);
                $manager->persist($ourOrder);
                $manager->flush();
            }
        } catch (ORMException $error) {
            $response = [
                "hasError" => true,
                "count" => 0,
                'status' => [
                    "code" => "800",
                    "message" => "Order not exist"
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
            $manager->persist($location);
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

        try {
            if($data->orderId)
            {
                $ourOrder = $this->getDoctrine()->getRepository(OOrder::class)->findOneBy(["id"=>$data->orderId]);
                $ourOrder->setStep(self::STEP_3);
                $manager->persist($ourOrder);
                $manager->flush();
            }
        } catch (ORMException $error) {
            $response = [
                "hasError" => true,
                "count" => 0,
                'status' => [
                    "code" => "800",
                    "message" => "Order not exist"
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

        $identityId = $data->identityId;

        $identity = $this->getDoctrine()->getRepository(Identity::class)->findOneBy(["id" => $identityId]);

        if (!$identity) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "404",
                        "message" => "Identity not exist"
                    ],
                    "item" => []
                ]
            );
        }

        $authorityId = $data->authorityId;

        $authority = $this->getDoctrine()->getRepository(Identity::class)->findOneBy(["id" => $authorityId]);

        if (!$authority) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "404",
                        "message" => "authority not exist"
                    ],
                    "item" => []
                ]
            );
        }

        $authorityId = $data->authorityId;

        $authority = $this->getDoctrine()->getRepository(Authority::class)->findOneBy(["id" => $authorityId]);

        if (!$authority) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "404",
                        "message" => "authority not exist"
                    ],
                    "item" => []
                ]
            );
        }

        $deliveryCountryId = $data->deliveryCountryId;

        $country = $this->getDoctrine()->getRepository(Country::class)->findOneBy(["id" => $deliveryCountryId]);

        if (!$country) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "404",
                        "message" => "Delivery country not exist"
                    ],
                    "item" => []
                ]
            );
        }


        $justification =  new Justification();
        $justification->setDeliveryCountry($country)
            ->setIdentity($identity)
            ->setAuthority($authority)
            ->setIdentifier($data->identifier)
            ->setEmission(new \DateTime(strtotime($data->emission)))
            ->setExpiration(new \DateTime(strtotime($data->expiration)))
            ->setCustomer($customer);

        try {
            $manager->persist($justification);
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

        try {
            if($data->orderId)
            {
                $ourOrder = $this->getDoctrine()->getRepository(OOrder::class)->findOneBy(["id"=>$data->orderId]);
                $ourOrder->setStep(self::STEP_4);
                $manager->persist($ourOrder);
                $manager->flush();
            }
        } catch (ORMException $error) {
            $response = [
                "hasError" => true,
                "count" => 0,
                'status' => [
                    "code" => "800",
                    "message" => "Order not exist"
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

    /**
     * @Route("/api/image", name="image")
     */
    public function image(Request $request, ValidatorInterface $validator, EntityManagerInterface $manager, FileUploader $fileUploader)
    {
        if ($request->getMethod() != 'POST') {
            return $this->json([
                'status' => Response::HTTP_METHOD_NOT_ALLOWED,
                'message' => 'Méthode non autorisée'
            ], Response::HTTP_METHOD_NOT_ALLOWED);
        }

        // try {
        //     $data = json_decode($request->getContent());
        // } catch (JsonException $exception) {
        //     return $this->json(
        //         [
        //             "hasError" => true,
        //             "count" => 0,
        //             'status' => [
        //                 "code" => "500",
        //                 "message" => "wrong syntaxe"
        //             ],
        //             "item" => []
        //         ]
        //     );
        // }

        $justificationId = $request->get('justificationId');

        $justification = $this->getDoctrine()->getRepository(Justification::class)->findOneBy(["id" => $justificationId]);

        if (!$justification) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "404",
                        "message" => "Delivery country not exist"
                    ],
                    "item" => []
                ]
            );
        }

        $images[] = !is_null($request->files->get('recto')) ? $request->files->get('recto') : "";
        $images[] = !is_null($request->files->get('verso')) ? $request->files->get('verso') : "";

        foreach ($images as $image) {

            if (gettype($image) == "object") {

                try {
                    $imageToSave = new Image();
                    $filename = $fileUploader->upload($image);

                    $imageToSave->setFilename($filename)
                        ->setJustification($justification);
                    $manager->persist($imageToSave);
                    $manager->flush();
                } catch (ORMException $error) {
                    $response = [
                        "hasError" => true,
                        "count" => 0,
                        'status' => [
                            "code" => "800",
                            "message" => "erreur des images en base de donnée"
                        ],
                        "item" => []
                    ];
                    return $this->json($response);
                }
            }
        }



        $response = [
            "hasError" => false,
            "count" => 0,
            'status' => [
                "code" => "200",
                "message" => "image is upload"
            ],
            "item" => []
        ];
        return $this->json($response);
    }


    /**
     * @Route("/api/choicePack", name="package")
     */
    public function option(Request $request, ValidatorInterface $validator, EntityManagerInterface $manager)
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

        try {
            if($data->orderId)
            {
                $ourOrder = $this->getDoctrine()->getRepository(OOrder::class)->findOneBy(["id"=>$data->orderId]);
                $ourOrder->setStep(self::STEP_5);
                $manager->persist($ourOrder);
                $manager->flush();
            }
        } catch (ORMException $error) {
            $response = [
                "hasError" => true,
                "count" => 0,
                'status' => [
                    "code" => "800",
                    "message" => "Order not exist"
                ],
                "item" => []
            ];
            return $this->json($response);
        }

        $orderId = $data->orderId;
        $offerData = $data->offer;
        $optionsData = $data->options;

        $offer = $this->getDoctrine()->getRepository(Offer::class)->find($offerData);
        $orderRepository = $this->getDoctrine()->getRepository(OOrder::class);
        $totalAmount = $offer->getAmount();
        $order = $this->updateStepOrder($orderId, $orderRepository);
        //dd($order);
    


        foreach ($optionsData as $option) {
            $opt = $this->getDoctrine()->getRepository(Option::class)->find($option);
            $totalAmount+= $opt->getPrice();
            //$order->addOptionsChoosen($opt);
        }

        $order
            ->setOffer($offer)
            ->setCustomer($customer)
            ->setReference(500)
            ->setAmount($totalAmount);

        $manager->persist($order);

        $response = [
            "hasError" => false,
            "count" => 0,
            'status' => [
                "code" => "200",
                "message" => "your package"
            ],
            "item" => []
        ];
        return $this->json($response);
    }

    private function updateStepOrder($store, OrderRepository $orderRepository) {
        if (isset($store)) { 
            $order = $orderRepository->find($store);
            return $order;
        }
    }


    /**
     * @Route("/api/payment", name="payment")
     */
    public function payment(Request $request, ValidatorInterface $validator, EntityManagerInterface $manager)
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

        $paymentChoiceId = $data->paymentChoiceId;

        $paymentChoice = $this->getDoctrine()->getRepository(PaymentChoice::class)->findOneBy(["id" => $paymentChoiceId]);

        if (!$paymentChoice) {
            return $this->json(
                [
                    "hasError" => true,
                    "count" => 0,
                    'status' => [
                        "code" => "404",
                        "message" => "Payment not exist"
                    ],
                    "item" => []
                ]
            );
        }

        // $orderId = $data->orderId;

        // $order = $this->getDoctrine()->getRepository(OOrder::class)->findOneBy(["id" => $orderId]);

        // if (!$order) {
        //     return $this->json(
        //         [
        //             "hasError" => true,
        //             "count" => 0,
        //             'status' => [
        //                 "code" => "404",
        //                 "message" => "Order not exist"
        //             ],
        //             "item" => []
        //         ]
        //     );
        // }
        $ourOrder = $this->getDoctrine()->getRepository(OOrder::class)->findOneBy(["id"=>$data->orderId]);

        $payment = new Payment();
        $payment->setCreatedAt(new \DateTime())
                ->setLabel($data->label)
                ->setOrderId($ourOrder)
                ->setPaymentChoice($paymentChoice)
                ->setStatus($data->status)
                ->setAmount($data->amount);

        try {
            $manager->persist($payment);
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

        try {
            if($data->orderId)
            {
                $ourOrder->setStep(self::STEP_6);
                $manager->persist($ourOrder);
                $manager->flush();
            }
        } catch (ORMException $error) {
            $response = [
                "hasError" => true,
                "count" => 0,
                'status' => [
                    "code" => "800",
                    "message" => "Order not exist"
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
                "message" => "your package"
            ],
            "item" => []
        ];
        return $this->json($response);
    }

    /**
     * @Route("/api/list", name="list")
     */
    public function list(Request $request, ValidatorInterface $validator, EntityManagerInterface $manager)
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
        $listOf = $data->listOf;
        
        switch ($listOf) {
            case 'pays':
                //pays
                $list = $countries = [
                        "AFGHANISTAN",
                        "AFRIQUE DU SUD",
                        "ALASKA",
                        "ALBANIE",
                        "ALGERIE",
                        "ALLEMAGNE",
                        "ANDORRE",
                        "ANGOLA",
                        "ANGUILLA",
                        "ANTIGUA",
                        "ANTILLES NEERLANDAISES",
                        "ARABIE SAOUDITE",
                        "ARGENTINE",
                        "ARMENIE",
                        "ARUBA",
                        "ASCENSION",
                        "AUSTRALIE",
                        "AUTRICHE",
                        "AZERBAIDJAN",
                        "BAHAMAS",
                        "BAHREIN",
                        "BANGLADESH",
                        "BARBADE",
                        "BELARUS",
                        "BELGIQUE",
                        "BELIZE",
                        "BENIN",
                        "BERMUDES",
                        "BHOUTAN",
                        "BOLIVIE",
                        "BOLIVIE",
                        "BOPHUTNATSWANA",
                        "BOSNIE-HERZEGOVINE",
                        "BOTSWANA",
                        "BOUTHAN",
                        "BRESIL",
                        "BRUNEI",
                        "BULGARIE",
                        "BURKINA FASO",
                        "BURUNDI",
                        "CAIMAN (ILES)",
                        "CAMBODGE",
                        "CAMEROUN",
                        "CANADA",
                        "CAP-VERT",
                        "CAYMAN (ILES)",
                        "CENTRAFRIQUE",
                        "CHILI",
                        "CHINE",
                        "CHRISTMAS",
                        "CHRISTMAS",
                        "CHYPRE",
                        "COCOS",
                        "COLOMBIE",
                        "COMORES",
                        "CONGO",
                        "COOK (ILES)",
                        "COREE DU NORD",
                        "COREE DU SUD",
                        "COSTA RICA",
                        "COTE D'IVOIRE",
                        "CROATIE",
                        "CUBA",
                        "DANEMARK",
                        "DIEGO-GARCIA",
                        "DJIBOUTI",
                        "DOMINIQUE",
                        "EGYPTE",
                        "EL SALVADOR",
                        "EMIRATS ARABES UNIS",
                        "EQUATEUR",
                        "ERYTHREE",
                        "ESPAGNE",
                        "ESTONIE",
                        "ETATS-UNIS",
                        "ETHIOPIE",
                        "FALKLAND (ILES)",
                        "FIDJI (ILES)",
                        "FINLANDE",
                        "FRANCE",
                        "GABON",
                        "GAMBIE",
                        "GEORGIE",
                        "GHANA",
                        "GRECE",
                        "GRENADE",
                        "GROENLAND",
                        "GUAM",
                        "GUATEMALA",
                        "GUINEE",
                        "GUINEE BISSAU",
                        "GUINEE EQUATORIALE",
                        "GUYANA HAITI",
                        "HONDURAS",
                        "HONG KONG",
                        "HONGRIE",
                        "ILES FEROE",
                        "ILES VIERGES",
                        "INDE",
                        "INDONESIE",
                        "IRAN",
                        "IRAQ",
                        "IRLANDE",
                        "ISLANDE",
                        "ISRAEL",
                        "ITALIE",
                        "JAMAIQUE",
                        "JAPON",
                        "JORDANIE",
                        "KAZAKHSTAN",
                        "KENYA",
                        "KIRGHIZISTAN",
                        "KIRIBATI",
                        "KOWEIT",
                        "LAOS",
                        "LESOTHO",
                        "LETTONIE",
                        "LIBAN",
                        "LIBERIA",
                        "LIBYE",
                        "LITUANIE",
                        "LUXEMBOURG",
                        "MACAO",
                        "MACEDOINE",
                        "MADAGASCAR",
                        "MALAISIE",
                        "MALAWI",
                        "MALDIVES (ILES)",
                        "MALI",
                        "MALTE",
                        "MAROC",
                        "MARSHALL (ILES)",
                        "MAURICE",
                        "MAURITANIE",
                        "MEXIQUE",
                        "MICRONESIE",
                        "MOLDAVIE",
                        "MONACO",
                        "MONGOLIE",
                        "MONTSERRAT",
                        "MOZAMBIQUE",
                        "NAMIBIE",
                        "NAURU",
                        "NEPAL",
                        "NICARAGUA",
                        "NIGER",
                        "NIGERIA",
                        "NORFOLK",
                        "NORFOLK",
                        "NORVEGE",
                        "NOUVELLE CALEDONIE",
                        "NOUVELLE-ZELANDE",
                        "OMAN",
                        "OUGANDA",
                        "OUZBEKISTAN",
                        "PAKISTAN",
                        "PALAU",
                        "PANAMA",
                        "PAPOUASIE NOUVELLE-GUINEE",
                        "PARAGUAY",
                        "PAYS-BAS",
                        "PEROU",
                        "PHILIPPINES",
                        "POLOGNE",
                        "PORTO RICO PORTUGAL",
                        "PORTUGAL",
                        "QATAR",
                        "REPUBLIQUE DOMINICAINE",
                        "REPUBLIQUE SLOVAQUE",
                        "REPUBLIQUE TCHEQUE",
                        "ROUMANIE",
                        "ROYAUME-UNI",
                        "RUSSIE",
                        "RWANDA",
                        "SAINT-CHRISTOPHE",
                        "SAINTE-HELENE",
                        "SAINTE-LUCIE",
                        "SAINT-MARIN",
                        "SAINT-VINCENT",
                        "SAIPAN",
                        "SALOMON (ILES)",
                        "SAMOA OCCIDENTAL",
                        "SAO TOME ET PRINCIPE",
                        "SENEGAL",
                        "SEYCHELLES",
                        "SIERRA LEONE",
                        "SINGAPOUR",
                        "SLOVAQUIE",
                        "SLOVENIE",
                        "SOMALIE",
                        "SOUDAN",
                        "SRI LANKA",
                        "SUEDE",
                        "SUISSE",
                        "SURINAM",
                        "SWAZILAND",
                        "SYRIE",
                        "TADJIKISTAN",
                        "TAIWAN",
                        "TANZANIE",
                        "TATARSTAN",
                        "TCHAD",
                        "THAILANDE",
                        "TOGO",
                        "TOKELAU",
                        "TONGA",
                        "TRANSKEI",
                        "TRINITE ET TOBAGO",
                        "TUNISIE",
                        "TURCKS ET CAICOS",
                        "TURKMENISTAN",
                        "TURQUES ET CAIQUES (ILES)",
                        "TURQUIE",
                        "TUVALU",
                        "UKRAINE",
                        "URUGUAY",
                        "VANUATU",
                        "VATICAN",
                        "VENEZUELA",
                        "VIETNAM",
                        "YEMEN ADEN",
                        "YEMEN DU NORD (REP. ARABE)",
                        "YEMEN DU SUD",
                        "YOUGOSLAVIE",
                        "ZAIRE",
                        "ZAMBIE",
                        "ZANZIBAR",
                        "ZIMBABWE"
                    ];
            break;
            case 'authorities':
                //authorities
                $list = $authorities = [
                        "Administration",
                        "Assemblée Générale Actionnaires",
                        "Centre Identification Sécuritaire",
                        "Direction Générale des Impôts",
                        "Min Affaires Etrangères",
                        "Min Défense Gendarmerie Armée",
                        "Min Intérieur",
                        "Min Sécurité Police",
                        "Tribunal",
                        "ONI"
                    ];
            break;
            case 'bank':
                //authorities
                $list =  //bank
                $banks = [
                    ["code" => 0,"label" => "BIAO"],
                    ["code" => 1,"label" => "BICI-CI"],
                    ["code" => 2,"label" => "SGBCI"],
                    ["code" => 3,"label" => "SIB"],
                    ["code" => 4,"label" => "CECP"],
                    ["code" => 5,"label" => "BCEAO"],
                    ["code" => 6,"label" => "COFIPA-INVEST"],
                    ["code" => 49,"label" => "CITIBANK"],
                    ["code" => 50,"label" => "COBACI/BARCLAYS BANK"],
                    ["code" => 51,"label" => "BANQUE ATLANTIQUE"],
                    ["code" => 52,"label" => "BHCI"],
                    ["code" => 53,"label" => "BANK OF AFRICA"],
                    ["code" => 54,"label" => "CAA"],
                    ["code" => 55,"label" => "ECOBANK"],
                    ["code" => 56,"label" => "BANQUE PARIBAS"],
                    ["code" => 57,"label" => "BANQUE DU TRESOR"],
                    ["code" => 58,"label" => "COFINCI"],
                    ["code" => 59,"label" => "STANDARD CHARTERED"],
                    ["code" => 60,"label" => "BANAFRIQUE"],
                    ["code" => 61,"label" => "ACCESS BANK ex OMNIF"],
                    ["code" => 62,"label" => "VERSUS BANQUE"],
                    ["code" => 63,"label" => "Banque Finan Agri"],
                    ["code" => 64,"label" => "BANQUE NATION.INVEST"],
                    ["code" => 65,"label" => "CECP"],
                    ["code" => 66,"label" => "Bque Regionale S.CI"],
                    ["code" => 67,"label" => "BRIDGE BANK"],
                    ["code" => 68,"label" => "COBACI"],
                    ["code" => 69,"label" => "UBA"],
                    ["code" => 70,"label" => "CITIBANK"],
                    ["code" => 71,"label" => "BQUE SAELO-SAHERIENE"],
                    ["code" => 72,"label" => "DIAMOND BANK"],
                    ["code" => 73,"label" => "BANK GABONNAISE F.I."],
                    ["code" => 74,"label" => "GUARANTY TRUST BANK"],
                    ["code" => 97,"label" => "Recette des Impôts"],
                    ["code" => 98,"label" => "BANQFICT.Reg.Enc.TVA"],
                    ["code" => 99,"label" => "X - BANQUE INCONNUE"]
                ];
            break;
        }

        $response = [
            "hasError" => false,
            "count" => 0,
            'status' => [
                "code" => "200",
                "message" => "your package"
            ],
            "item" => $list
        ];
        return $this->json($response);
    }
}
