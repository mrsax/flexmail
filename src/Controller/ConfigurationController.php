<?php

namespace App\Controller;

use App\Service\ApiParameters;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ApiConfigurationFormType;

class ConfigurationController extends Controller
{
    /**
     * This controller will show form to set parameters and let user set them at submit.
     *
     * @Route("/configuration", name="configuration")
     *
     * @param Request $request
     * @param ApiParameters $apiParams
     *
     * @return Response
     */
    public function index(Request $request, ApiParameters $apiParams): Response
    {
        $form = $this->generateApiConfigurationForm();

        $form->handleRequest($request);

        $errors = $form->getErrors();

        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $api = $apiParams->setApiParameters($data);



            //$this->container()->setParameter('mailer.transport', 'sendmail');
            //$geolocation->loadGeolocationToDatabase($data['city']);
            //$this->container->setParameter('api.key', $data['apiKey']);
           dd($api);

        }
        //dd($this->container->getParameter('api.key'));
        return $this->render('configuration/config.html.twig', ['form' => $form->createView()]);
    }


    /**
     * @return FormInterface
     */
    private function generateApiConfigurationForm(): FormInterface
    {
        return $this->createForm(ApiConfigurationFormType::class);
    }

}
