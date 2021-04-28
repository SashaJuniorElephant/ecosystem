<?php

namespace App\Controller;

use App\Form\ContinueMenuType;
use App\Form\CsvMenuType;
use App\Form\ManualMenuType;
use App\Service\GameService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Security;
use Symfony\Contracts\Translation\TranslatorInterface;


class MenuController extends AbstractController
{
    /**
     * @Route(
     *     "/menu",
     *     name="menu",
     *     methods={"GET"},
     * )
     * @Security("is_granted('ROLE_USER')")
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function startMenu(TranslatorInterface $translator)
    {
//        throw new \InvalidArgumentException("Тестовое исключение");

        $manualForm = $this->createForm(ManualMenuType::class);
        $csvForm = $this->createForm(CsvMenuType::class);
        $continueForm = $this->createForm(ContinueMenuType::class);

        return $this->render('ecosystem/menu/menu.html.twig', [
            'title' => $translator->trans('menu'),
            'manualForm' => $manualForm->createView(),
            'csvForm' => $csvForm->createView(),
            'continueForm' => $continueForm->createView(),
        ]);
    }

    /**
     * @Route(
     *     "/menu",
     *     name="request_menu",
     *     methods={"POST"}
     * )
     * @Security("is_granted('ROLE_USER')")
     * @param Request $request
     * @param GameService $gameService
     * @param TranslatorInterface $translator
     * @return RedirectResponse|Response
     */
    public function menu(Request $request, GameService $gameService, TranslatorInterface $translator)
    {
        $manualForm = $this->createForm(ManualMenuType::class);
        $csvForm = $this->createForm(CsvMenuType::class);
        $continueForm = $this->createForm(ContinueMenuType::class);

        $manualForm->handleRequest($request);
        if ($manualForm->isSubmitted() && $manualForm->isValid()) {
            $gameService->processingForms($manualForm);

            return $this->redirectToRoute('observation');
        }

        $csvForm->handleRequest($request);
        if ($csvForm->isSubmitted() && $csvForm->isValid()) {
            $gameService->processingForms($csvForm);

            return $this->redirectToRoute('observation');
        }

        $continueForm->handleRequest($request);
        if ($continueForm->isSubmitted() && $continueForm->isValid()) {
            $gameService->processingForms($continueForm);

            return $this->redirectToRoute('observation');
        }

        return $this->render('ecosystem/menu/menu.html.twig', [
            'title' => $translator->trans('menu'),
            'manualForm' => $manualForm->createView(),
            'csvForm' => $csvForm->createView(),
            'continueForm' => $continueForm->createView(),
        ]);
    }
}
