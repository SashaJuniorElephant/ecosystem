<?php

namespace App\EventListener;

use Psr\Log\LoggerAwareTrait;
use Symfony\Bundle\FrameworkBundle\Templating\EngineInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseForExceptionEvent;
use Symfony\Component\HttpKernel\Exception\HttpExceptionInterface;

class ExceptionListener
{

    use LoggerAwareTrait;

    /**
     * @var \Swift_Mailer
     */
    private $mailer;

    /**
     * @var EngineInterface
     */
    private $templating;

    public function __construct(\Swift_Mailer $mailer, EngineInterface $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function onKernelException(GetResponseForExceptionEvent $event)
    {
        // You get the exception object from the received event
        $exception = $event->getException();
        $exceptionMessage = $exception->getMessage();

        // Отправка письма
        $mailMessage = (new \Swift_Message($exceptionMessage))
            ->setFrom('narcode.vologzhanin@gmail.com')
            ->setTo('avologzhanin@htc-cs.ru')
            ->setBody(
                $this->templating->render('ecosystem/emails/test.html.twig', ['message' => $exceptionMessage]),
                'text/html'
            );
        $this->mailer->send($mailMessage);

        // Customize your response object to display the exception details
        $response = new Response();
        $response->setContent($this->templating->render('bundles/TwigBundle/Exception/error.html.twig', [
            'title' => 'Ошибка 500',
            'message' => $exceptionMessage,
            ])
        );

        // HttpExceptionInterface is a special type of exception that
        // holds status code and header details
        if ($exception instanceof HttpExceptionInterface) {
            $response->setStatusCode($exception->getStatusCode());
            $response->headers->replace($exception->getHeaders());
        } else {
            $response->setStatusCode(Response::HTTP_INTERNAL_SERVER_ERROR);
        }

        // sends the modified response object to the event
        $event->setResponse($response);
    }
}
