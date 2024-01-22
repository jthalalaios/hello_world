<?php

namespace App\Command;

use App\Repository\DatabaseStorage;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;


class HelloWorldCommand extends Command
{

    private $databaseStorage;

    public function __construct(DatabaseStorage $DatabaseStorage)
    {
        parent::__construct();
        $this->databaseStorage = $DatabaseStorage;
    }

    protected function configure(): void
    {
        $this
            ->setName('HelloWorld')
            ->setDescription('Run the hello world project')
            ->addArgument('amount', InputArgument::REQUIRED, 'Give the amount that you want to initializate  the atm cash')
            ->addArgument('amount_to_draw', InputArgument::REQUIRED, 'Give the amount to draw from atm');
        parent::configure();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $amount_to_insert = $input->getArgument('amount');

        $this->databaseStorage->insertNotesToATM($amount_to_insert);

        $amount_to_draw = $input->getArgument('amount_to_draw');

        $current_atm_amount = $this->databaseStorage->getCurrentNotes()->amount;
        $current_atm_id = $this->databaseStorage->getCurrentNotes()->id;

        echo ("The current ATM notes:" . $current_atm_amount . "\n");
        echo ("The amount notes wanted to be withdraw from ATM notes:" . $amount_to_draw . "\n");

        $amount_to_draw_from_atm = $current_atm_amount - $amount_to_draw;

        if ($amount_to_draw % 20 != 0  && $amount_to_draw % 50 != 0) {
            echo ("The amount of notes that it wanted to be withdraw are not possible!\n");
            $this->databaseStorage->removeAtmNotes($current_atm_id);
            return 0;
        }

        $notes_to_20 = intdiv($amount_to_draw, 20);
        $notes_to_50 = intdiv($amount_to_draw, 50);

        $notes_20_or_50 = rand(0, 1) ? $notes_to_20 : $notes_to_50;
        if (($notes_to_20 > 0  || $notes_to_50 > 0) && $amount_to_draw_from_atm > 0) {
            if ($notes_to_20 == $notes_20_or_50 && $notes_to_20 > 0) {
                echo ("The ATM notes divided by 20 are:" . $notes_to_20 . "\n");
            }
            if ($notes_to_50 == $notes_20_or_50 && $notes_to_50 > 0) {
                echo ("The ATM notes divided by 50 are:" . $notes_to_50 . "\n");
            }
            $this->databaseStorage->updateCurrentNotes($current_atm_id, $amount_to_draw_from_atm);
            $current_atm_amount = $this->databaseStorage->getCurrentNotes()->amount;

            echo ("The remaining ATM notes:" . $current_atm_amount . "\n");
        } else {
            echo ("The amount of notes that it wanted to be withdraw are more than the current's ATM notes!\n");

            return 0;
        }

        $this->databaseStorage->removeAtmNotes($current_atm_id);

        return 0;
    }
}
