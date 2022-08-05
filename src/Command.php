<?php

namespace DownloadMp3;

use Symfony\Component\Console\Command\Command as BaseCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class Command extends BaseCommand
{
    protected static $defaultName = 'download:mp3';

    protected function configure(): void
    {
        $this->addArgument('name', InputArgument::REQUIRED, 'download music name.');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {

        $downloader = new Downloader();
        $save = $downloader->downloadMusic($input->getArgument('name'));
        $output->writeln("成功下载文件保存至：{$save}");

        return Command::SUCCESS;
    }
}