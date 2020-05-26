<?php

namespace App\Command;

use App\Service\Greeting;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

//php bin/console debug:container App\Command\HelloCommand
class HelloCommand extends Command {

    private $greeting;

    public function __construct(Greeting $greeting)
    {
        $this->greeting = $greeting;
        parent::__construct();
    }

    protected function configure()
    {
        $this->setName('app:say-hello')
            ->setDescription('This command says hello!!')
            ->addArgument('name', InputArgument::REQUIRED);
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $name = $input->getArgument('name');
        $output->writeln([
            'Hello from the app',
            '==================',
            ''
        ]);
        $output->writeln($this->greeting->greet($name));

        return 1;
    }
}