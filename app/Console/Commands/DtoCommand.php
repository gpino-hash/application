<?php

namespace App\Console\Commands;

use Illuminate\Console\GeneratorCommand;

class DtoCommand extends GeneratorCommand
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:dto {name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create a new DTO';


    /**
     * @return string
     */
    protected function getStub()
    {
        return __DIR__ . '/../../../stubs/dto.stub';
    }

    protected function getDefaultNamespace($rootNamespace)
    {
        return $rootNamespace . '\DataTransferObjects';
    }
}
