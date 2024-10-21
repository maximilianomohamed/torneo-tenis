<?php
namespace App\Command;

use OpenApi\Generator;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SwaggerCommand extends Command
{
    protected static $defaultName = 'app:generate-swagger';

    protected function configure()
    {
        $this->setDescription('Genera la documentación de Swagger');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $swagger = Generator::scan([
            dirname(__DIR__) . '/Controller',
            dirname(__DIR__) . '/Documentation'
        ]);
        
        file_put_contents('public/swagger.json', $swagger->toJson());
        $output->writeln('Documentación de Swagger generada en swagger.json');
        return Command::SUCCESS;
    }
}
