<?php

namespace App\EventSubscriber;

use App\Repository\TournoisRepository;
use CalendarBundle\CalendarEvents;
use CalendarBundle\Entity\Event;
use CalendarBundle\Event\CalendarEvent;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class CalendarSubscriber implements EventSubscriberInterface
{
    private $TournoisRepository;
    private $router;

    public function __construct(
        TournoisRepository  $TournoisRepository,
        UrlGeneratorInterface $router
    ) {

        $this->TournoisRepository = $TournoisRepository;
        $this->router = $router;
    }

    public static function getSubscribedEvents()
    {
        return [
            CalendarEvents::SET_DATA => 'onCalendarSetData',
        ];
    }

    public function onCalendarSetData(CalendarEvent $calendar)
    {
        $start = $calendar->getStart();
        $end = $calendar->getEnd();
        $filters = $calendar->getFilters();

        // Modify the query to fit to your entity and needs
        // Change booking.beginAt by your start date property
        $tournois = $this->TournoisRepository
            ->createQueryBuilder('tournois')
            ->where('Tournois.Datedebut BETWEEN :start and :end OR Tournois.Datefin BETWEEN :start and :end')
            ->setParameter('start', $start->format('Y-m-d H:i:s'))
            ->setParameter('end', $end->format('Y-m-d H:i:s'))
            ->getQuery()
            ->getResult()
        ;

        foreach ($tournois as $tournois) {
            // this create the events with your data (here booking data) to fill calendar
            $tournoisEvent = new Event(
                $tournois->getTitle(),
                $tournois->getBeginAt(),
                $tournois->getEndAt() // If the end date is null or not defined, a all day event is created.
            );

            /*
             * Add custom options to events
             *
             * For more information see: https://fullcalendar.io/docs/event-object
             * and: https://github.com/fullcalendar/fullcalendar/blob/master/src/core/options.ts
             */

            $tournoisEvent->setOptions([
                'backgroundColor' => 'red',
                'borderColor' => 'red',
            ]);
            $tournoisEvent->addOption(
                'url',
                $this->router->generate('tournois_show', [
                    'Id' => $tournois->getId(),
                ])
            );

            // finally, add the event to the CalendarEvent to fill the calendar
            $calendar->addEvent($tournoisEvent);
        }
    }
}