<?php

namespace App\Controller;

use App\Entity\Contact;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use App\Form\ContactType;

/**
 * Class IndexController
 * @package App\Controller
 */
class IndexController extends AbstractController
{
    /**
     * @return Response
     */
    public function index(Request $request)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        return $this->render('contact/add.html.twig', [
            'form'                => $form->createView(),
            'phoneNumberTemplate' => $this->getParameter('phoneNumberTemplate'),
            'addContactPath'      => $this->generateUrl('addContact')
        ]);
    }

    /**
     * @param Request $request
     * @return JsonResponse|\Symfony\Component\HttpFoundation\RedirectResponse
     */
    public function addContact(Request $request)
    {
        $contact = new Contact();

        $form = $this->createForm(ContactType::class, $contact);

        $data = [
            'result' => false,
        ];

        $form->handleRequest($request);

        if (
            $form->isSubmitted() &&
            $request->isXmlHttpRequest() &&
            $form->isValid()
        ) {

            $contactData = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($contactData);
            $entityManager->flush();

            $data['result']  = true;
            $data['message'] = str_replace(
                '<username>',
                $contactData->getName(),
                $this->getParameter('contactAddErrorMessage')
            );
        }
        else {
            $errors = $form->getErrors(true, true);

            if ($errors) {
                foreach ($errors as $error) {
                    $data['message'] = $error->getMessage();

                    break;
                }
            }
        }

        return new JsonResponse($data);
    }
}
