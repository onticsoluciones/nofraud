<?php

namespace Ontic\NoFraud\Commands;

use Ontic\NoFraud\Repositories\UserRepository;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\QuestionHelper;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class CreateUserCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('nofraud:create-user')
            ->setDescription('Creates a new user');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        /** @var QuestionHelper $helper */
        $helper = $this->getHelper('question');

        $question = new Question('Username: ');
        $username = $helper->ask($input, $output, $question);
        if(!$username)
        {
            return;
        }

        $question = new Question('Password: ');
        $question->setHidden(true);
        $question->setHiddenFallback(false);
        $password = $helper->ask($input, $output, $question);
        if(!$password)
        {
            return;
        }

        $repo = new UserRepository();
        $repo->save($username, $password);
        $output->writeln('User added.');
    }
}