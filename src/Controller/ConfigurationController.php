<?php

namespace App\Controller;

use App\Form\PrognosisToRealSaveType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Form\ApiConfigurationType;

class ConfigurationController extends AbstractController
{
    /**
     * @Route("/configuration", name="configuration")
     */
    public function index(Request $request): Response
    {
        $form = $this->generateApiConfigrationForm();


        return $this->render('configuration/config.html.twig', ['form' => $form->createView()]);
    }


    private function generateApiConfigrationForm()
    {
        return $this->createForm(ApiConfigurationType::class);
    }

}
