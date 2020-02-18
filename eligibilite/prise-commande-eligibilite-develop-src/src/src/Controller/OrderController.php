<?php

namespace App\Controller;

use App\Entity\BankCheck;
use App\Entity\Customer;
use App\Entity\Image;
use App\Entity\Justification;
use App\Entity\Location;
use App\Entity\Offer;
use App\Entity\OOrder;
use App\Entity\Payment;
use App\Entity\Process;
use App\Form\CustomerType;
use App\Form\JustificationType;
use App\Form\LocationType;
use App\Form\OrderFlow;
use App\Repository\CustomerRepository;
use App\Repository\JustificationRepository;
use App\Repository\LocationRepository;
use App\Repository\OfferRepository;
use App\Repository\OptionRepository;
use App\Repository\OrderRepository;
use App\Repository\PaymentChoiceRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response as ResponseAlias;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Annotation\Route;

class OrderController extends AbstractController
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->redirectToRoute('app_login');
    }

    /**
     * Pour choisir soit une nouvelle commande soit une commande existante
     * @Route("/step1", name="step1")
     * @param SessionInterface $session
     * @return ResponseAlias
     */
    public function home(SessionInterface $session)
    {

        if ($session->has('process'))
            $session->remove('process');

        return $this->render('order/step1.html.twig');
    }


    /**
     * @Route("/process", name="process")
     * @Route("/process/{id}", name="process_update")
     * @param OrderFlow $flow
     * @param FileUploader $fileUploader
     * @param Request $request
     * @param EntityManagerInterface $manager
     * @param CustomerRepository $customerRepository
     * @param SessionInterface $session
     * @param LocationRepository $locationRepository
     * @param JustificationRepository $justificationRepository
     * @param OfferRepository $offerRepository
     * @param OptionRepository $optionRepository
     * @param PaymentChoiceRepository $choiceRepository
     * @param OrderRepository $orderRepository
     * @param int $id
     * @return RedirectResponse|ResponseAlias
     * @throws \Exception
     */
    public function newProcess(
        OrderFlow $flow,
        FileUploader $fileUploader,
        Request $request,
        EntityManagerInterface $manager,
        CustomerRepository $customerRepository,
        SessionInterface $session,
        LocationRepository $locationRepository,
        JustificationRepository $justificationRepository,
        OfferRepository $offerRepository,
        OptionRepository $optionRepository,
        PaymentChoiceRepository $choiceRepository,
        OrderRepository $orderRepository,
        $id = 0
    ) {
        //Gerer exception si ya pas de id correct
        $process = new Process();

        $order = $orderRepository->find($id);

        if (!is_null($order)) {
            $store = [];
            $process->setStep($order->getStep());
            $store['orderId'] = $order->getId();
            $store['customerId'] = $order->getCustomer()->getId();

            if ($process->getStep() > 2) {
                $locationId = $order->getCustomer()->getLocations()->toArray()[0]->getId();
                $store['locationId'] = $locationId;
//
//                if ($process->getStep() > 5) {
//                    dd($order->getOffer());
//                }
            }

            $session->set('process', $store);

        }

        //toutes les donnees du processus
        //liaison avec le formulaire
        $flow->bind($process);

        // form of the current step
        $form = $flow->createForm();

        if ($flow->isValid($form)) {
            $saveForm = null;
            //preparation de la session
            if (!$session->has('process')) {
                $session->set('process', []);
            }

            $store = $session->get('process');

            switch ($flow->getCurrentStepNumber()) {

                //infos client
                case 1:
                    if (isset($store['customerId'])) {
                        $toSave = $customerRepository->find($store['customerId']);
                    }
                    else {
                        $user = $this->getUser();
                        $toSave = new Customer();
                        $toSave->setUser($user);
                    }
                    $saveForm = $this->createForm(CustomerType::class, $toSave);
                    break;

                // localisation
                case 2 :
                    if (isset($store['locationId'])) {
                        $toSave = $locationRepository->find($store['locationId']);
                        $saveForm = $this->createForm(LocationType::class, $toSave);
                    } else {
                        $toSave = new Location();
                        $customer = $customerRepository->find($store['customerId']);
                        $toSave->setCustomer($customer);
                        $saveForm = $this->createForm(LocationType::class, $toSave);
                    }
                    break;

                // Eligibilite
                case 3:
                    $toSave = $locationRepository->find($store['locationId']);
                    $toSave->setLatitude($flow->getFormData()->getLatitude());
                    $toSave->setLongitude($flow->getFormData()->getLongitude());
                    $toSave->setAddition($flow->getFormData()->getLocation());
                    $saveForm = $this->createForm(LocationType::class, $toSave);
                    break;

                // Justification
                case 4:

                    if (isset($store['justificationId'])) {
                        $toSave = $justificationRepository->find($store['justificationId']);
                    } else {
                        $toSave = new Justification();
                        $customer = $customerRepository->find($store['customerId']);
                        $toSave->setCustomer($customer);
                    }
                    $saveForm = $this->createForm(JustificationType::class, $toSave);
                    break;

                // Offres
                case 5:
                    $data = $request->request->get("offer");
                    $offerData = $data['offer'];
                    $optionsData = $data['options'];
                    $offer = $offerRepository->find($offerData);
                    $customer = $customerRepository->find($store['customerId']);
                    $totalAmount = $offer->getAmount();
//                    dd($store);
                    $order = $this->updateStepOrder($store, $orderRepository, 6);


                    foreach ($optionsData as $option) {
                        $opt = $optionRepository->find($option);
                        $totalAmount+= $opt->getPrice();
                        $order->addOptionsChoosen($opt);
                    }

                    $order
                        ->setOffer($offer)
                        ->setCustomer($customer)
                        ->setReference(500)
                        ->setAmount($totalAmount);

                    $manager->persist($order);

                    break;

                //Paiement
                case 6:
                    $choice = $choiceRepository->find($process->getPaymentChoice()->getId());
                    if (is_null($choice) && $choice->getLabel() == $process->getPaymentChoice()->getLabel()) {
                        // error
                    }
                    else {

                        //recuperation de la commande en cours
                        $order = $orderRepository->find($store['orderId']);

                        //creation facture de paiement
                        $payment = new Payment();

                        //attributions de valeurs
                        $payment->setPaymentChoice($choice)
                            ->setLabel('')
                            ->setCreatedAt(new \DateTime('now'));

                        if (strtolower($choice->getLabel()) == 'especes') {
                            $order->setStep(6);

                            $payment
                                ->setOrderId($order)
                                ->setAmount($process->getAmount())
                                ->setCreatedAt(new \DateTime('now'))
                                ->setStatus(true)
                            ;

                            $manager->persist($payment);
                            $manager->persist($order);

                        } else {
                            if (count($process->getCheck()) > 0) {
                                $pay = $process->getCheck()['__name__'];
                            } else {
                                $pay = $process->getMobileMoney()['__name__'];
                            }
                            $payment->setAmount($pay->getAmount())
                                ->setOrderId($order)
                                ->setStatus(true)
                            ;

                            $manager->persist($payment);
                            $pay->setPayment($payment);
                            $manager->persist($pay);
                        }
                    }
                    break;
            }


            if ($flow->getCurrentStepNumber() < 5) {
//                dd($request->request->all());
//                dd($request->request->all());
                $saveForm->handleRequest($request);
                $manager->persist($toSave);

                if ($flow->getCurrentStepNumber() == 4) {
                    $filenames = [];
                    $images = $request->files->get('justification')['file'];
 
                    foreach ($images as $image) {
                        $imageToSave = new Image();
                        $filename = $fileUploader->upload($image);

                        $imageToSave->setFilename($filename)
                               ->setJustification($toSave);
                        $manager->persist($imageToSave);
                        $filenames[]= $filename;

                        $process->setFile($filenames);


                    }
                }
            }
//            dd($process);
            //sauvegarde de l'etat du processus
            $flow->bind($process);

//            dd($request->files->get('justification')['file'][0]->setPathname('dssdsdd'));
//            dd($flow);

            $form = $flow->createForm();
            $flow->saveCurrentStepData($form);

            //persistance de donnees du formulaire en base de donnees

            $manager->flush();

            if (isset($order)) {
                $store['orderId'] = $order->getId();
            }

            //mise en sessions pour persistance de donnees
            switch ($flow->getCurrentStepNumber()) {

                //Infos client
                case 1:
                    $store['customerId'] = $toSave->getId();
                    if (!isset($store['orderId'])) {
                        $order = new OOrder();
                        $order->setCustomer($toSave)
                            ->setReference(1500)
                            ->setAmount(0)
                            ->setStep(2);
                    } else {
                        $order = $orderRepository->find($store['orderId']);
                    }
                    break;
                // localisation
                case  2 :
                    $store['locationId'] = $toSave->getId();
                    $order = $this->updateStepOrder($store, $orderRepository, 3);
                    break;
                case 3:
                    $order = $this->updateStepOrder($store, $orderRepository, 4);
                    break;
                case 4:
                    $store['justificationId'] = $toSave->getId();
                    $order = $this->updateStepOrder($store, $orderRepository, 5);
                    break;
                case 5:
                    // voir dans la logique plus haut
                case 6:
                    $order = $this->updateStepOrder($store, $orderRepository, 7);
                    break;
                case 7:
                    $order = $this->updateStepOrder($store, $orderRepository, 8);
                    break;
            }


            $manager->persist($order);

            $manager->flush();

            if ($flow->getCurrentStepNumber() == 1) {
                $store['orderId'] = $order->getId();
            }

            $session->set('process', $store);



            //prochaine etape ou pas
            if ($flow->nextStep()) {
                // form for the next step
                $form = $flow->createForm();
            } else {
                $this->addFlash('success', 'Processus termine avec succes');
//                 flow finished
//                $em = $this->getDoctrine()->getManager();
//                $em->persist($formData);
//                $em->flush();
                $session->remove('process');
                $flow->reset(); // remove step data from the session
                return $this->redirectToRoute('step1'); // redirect when done
            }
        }

        return $this->render('order/step2.html.twig', [
            'form' => $form->createView(),
            'flow' => $flow,
        ]);
    }


    private function updateStepOrder(array $store, OrderRepository $orderRepository, int $step) {
        if (isset($store['orderId'])) {
            $order = $orderRepository->find($store['orderId']);
            $order->setStep($step);
            return $order;
        }
    }


    /**
     * @Route("/continue", name="un_process")
     * @param OrderRepository $orderRepository
     * @return ResponseAlias
     */
    public function continueProcess(OrderRepository $orderRepository) {
        $orders = $orderRepository->findUncompleted();
        return $this->render('order/continue.html.twig', compact('orders'));
    }

    /**
     * @Route("/options/{id}")
     * @param Offer $offer
     * @return JsonResponse
     */
    public function getOptions(Offer $offer) {
        $options = $offer->getOptions()->toArray();
        $newOptions = [];
        foreach ($options as $option) {
            $newOptions[]= [
                "id" => $option->getId(),
                "label" => $option->getLabel(),
                "price" => $option->getPrice(),
                "code" => $option->getCode()
            ];
        }
        return new JsonResponse($newOptions);
    }
}
