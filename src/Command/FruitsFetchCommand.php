<?php

namespace App\Command;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

use App\Entity\{Fruit, Nutrition};

#[AsCommand(
    name: 'fruits:fetch',
    description: 'fetch foods and populate the database',
)]
class FruitsFetchCommand extends Command
{
    public function __construct(
        private HttpClientInterface $client,
        private EntityManagerInterface $entityManager,
        private MailerInterface $mailer
    ) {
        parent::__construct();
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        $response = $this->client->request(
            'GET',
            'https://fruityvice.com/api/fruit/all'
        );

        $statusCode = $response->getStatusCode();
        // $statusCode = 200
        if($statusCode >= 200 && $statusCode < 300) {
            $content = $response->getContent();
            $remoteFruits = json_decode($content);
            
            $io->note(sprintf('Total %d fruits found', count($remoteFruits)));

            $fruitRepo = $this->entityManager->getRepository(Fruit::class);
            foreach($remoteFruits as $remoteFruit) {
                $existingFruit = $fruitRepo->findOneBy(['remote_id' => $remoteFruit->id]);
                if(!$existingFruit) {
                    $io->note(sprintf("Fruit %s don't exists in database, inserting now.", $remoteFruit->name));
                    $fruitEntity = new Fruit();
                    $fruitEntity->setRemoteId($remoteFruit->id);
                    $fruitEntity->setName($remoteFruit->name);
                    $fruitEntity->setFamily($remoteFruit->family);
                    $fruitEntity->setTaxoOrder($remoteFruit->order);
                    $fruitEntity->setGenus($remoteFruit->genus);

                    $fruitNutritionEntity = new Nutrition();
                    $fruitNutritionEntity->setCarbohydrates($remoteFruit->nutritions->carbohydrates);
                    $fruitNutritionEntity->setProtein($remoteFruit->nutritions->protein);
                    $fruitNutritionEntity->setFat($remoteFruit->nutritions->fat);
                    $fruitNutritionEntity->setCalories($remoteFruit->nutritions->calories);
                    $fruitNutritionEntity->setSugar($remoteFruit->nutritions->sugar);

                    $fruitEntity->setNutrition($fruitNutritionEntity);

                    $this->entityManager->persist($fruitEntity);
                    $this->entityManager->flush();

                    $io->success(sprintf("Fruit %s inserted into database", $remoteFruit->id));
                }
            }

            $email = (new Email())
                ->from($_ENV['FROM_EMAIL'])
                ->to($_ENV['ADMIN_EMAIL'])
                ->subject('Fruit database population')
                ->html('Fruit database populated successfully');
            
            $this->mailer->send($email);

            $io->success('Database successfully populated and email is sent!');
        }else{
            $io->error('Err');
        }
        

        return Command::SUCCESS;
    }
}
