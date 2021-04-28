<?php

namespace App\Service;

use App\Entity\Points;
use App\Entity\Units\Animal;
use App\Entity\Units\PlantKingdom;
use App\Entity\Units\Visitor;
use App\Entity\VisitorLogs;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\Exception\FileException;

class VisitorLogsService
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    private function newMessage(Visitor $visitor, string $message): void
    {
        $visitorLog = new VisitorLogs();
        $visitorLog->setMessage($message);
        $visitor->addLog($visitorLog);
    }

    public function moveUnitMessage(Visitor $visitor, Points $pointTo): void
    {
        $this->newMessage($visitor,
            'Пришел из точки (' . $visitor->getPoint()->getX() . ', ' . $visitor->getPoint()->getY() . ')'
            . ' в (' . $pointTo->getX() . ', ' . $pointTo->getY() . ')'
        );
    }

    public function collectPlantsMessage(Visitor $visitor, PlantKingdom $plant): void
    {
        $this->newMessage($visitor, 'Собрал ' . $plant->getName() . ' питательность - ' . $plant->getFoodPower());
    }

    public function meetVisitorMessage(Visitor $visitorWalker, Visitor $visitor): void
    {
        $this->newMessage($visitorWalker,'Встретил ' . $visitor->getName());
    }

    public function emptyPointMessage(Visitor $visitor): void
    {
        $this->newMessage($visitor, "Похоже, здесь ничего нет...");
    }

    public function meetAnimalMessage(Visitor $visitor, Animal $animal): void
    {
        $this->newMessage($visitor, 'Заметил ' . $animal->getName() . ' сила - ' . $animal->getStrength());
    }

    public function unknownUnitMessage(Visitor $visitor): void
    {
        $this->newMessage($visitor,'Встретил неизвестного юнита');
    }

    public function saveToFile(Visitor $visitor): void
    {
        $fileName = __DIR__ . '\..\..\var\log\visitor_logs\log_visitor_' . $visitor->getIndex() . ".txt";
        /** @var VisitorLogs $log */
        foreach ($visitor->getLogs() as $log) {
            $message = $log->getMessage() . PHP_EOL;
            $success = file_put_contents($fileName, $message, FILE_APPEND | LOCK_EX);

            if ($success === false) {
                throw new FileException("Не удалось осуществить запись в log-файл наблюдателя");
            }
        }
    }
}
