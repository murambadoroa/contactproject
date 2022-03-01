<?php

namespace App\Controller;

use App\Entity\Contact;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class DefaultController extends AbstractController
{
    /**
     * @Route("/", name="app_default")
     */
    public function index(): Response
    {
        return $this->render('home.html.twig');
    }

    /**
     * @Route("/contact-us", name="app_contact_us")
     */
    public function contact(Request $request, EntityManagerInterface $manager, ValidatorInterface $validator): Response
    {
        if ($request->request->getBoolean('submit')) {
            $name = $request->request->get('name');
            $email = $request->request->get('email');

            $contact = new Contact();
            $contact->setName($name);
            $contact->setEmail($email);

            // todo: validate
            $errors = $validator->validate($contact);

            if (count($errors)) {
                dd((string)$errors);
            }

            // todo: save to db
            $manager->persist($contact);
            $manager->flush();

            // todo: send email


            return $this->redirectToRoute("app_default");
        }

        return $this->render('contact.html.twig');
    }
}
