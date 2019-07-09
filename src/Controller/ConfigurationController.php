<?php

namespace App\Controller;

use App\Form\PrognosisToRealSaveType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ApiConfigurationFormType;

class ConfigurationController extends AbstractController
{
    /**
     * This controller will show form to set parameters and let user set them at submit.
     *
     * @Route("/configuration", name="configuration")
     *
     * @param Request $request
     *
     * @return Response
     */
    public function index(Request $request): Response
    {
        $form = $this->generateApiConfigurationForm();

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid())
        {
            $data = $form->getData();
            $container->setParameter('mailer.transport', 'sendmail');
        }

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
