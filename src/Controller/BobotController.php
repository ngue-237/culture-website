<?php

namespace App\Controller;

use App\Repository\StadeRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use BotMan\BotMan\BotMan;
use BotMan\BotMan\BotManFactory;
use BotMan\BotMan\Drivers\DriverManager;


class BobotController extends AbstractController{

    /**
     * @Route("/message", name="message")
     */
    function messageAction(Request $request)
    {
        DriverManager::loadDriver(\BotMan\Drivers\Web\WebDriver::class);

        // Configuration for the BotMan WebDriver
        $config = [];

        // Create BotMan instance
        $botman = BotManFactory::create($config);

        // Give the bot some things to listen for.
        $botman->hears('(hello|hi|hey)', function (BotMan $bot) {
            $bot->reply('Hello , How can I help you!');
        });

        $botman->hears('(Eren)', function (BotMan $bot) {
            $bot->reply('Yeager!');
        });

        $botman->hears('(Can I delete my reservation)', function (BotMan $bot) {
            $bot->reply('Sorry you Cannot!');
        });




        // Set a fallback
        $botman->fallback(function (BotMan $bot) {
            $bot->reply('Sorry, I did not understand.');
        });

        // Start listening
        $botman->listen();

        return new Response();
    }

    /**
     * @Route("/stade/{slug}-{idStade}", name="homepage")
     * @param StadeRepository $repository
     */
    public function indexAction(StadeRepository $repository,Request $request)
    {
        $Stades= $repository->findAll();


        return $this->render('stade/show.html.twig',[
            'Stades'=>$Stades
        ]);
    }

    /**
     * @Route("/chatframe", name="chatframe")
     */
    public function chatframeAction(Request $request)
    {
        return $this->render('chat_frame.html.twig');
    }
}