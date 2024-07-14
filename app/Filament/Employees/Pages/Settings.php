<?php

namespace App\Filament\Employees\Pages;

use Filament\Pages\Page;
use Mockery\Undefined;

class Settings extends Page
{

    protected static ?string $navigationIcon = 'heroicon-o-cog';

    protected static string $view = 'filament.employees.pages.settings';


    public int $count = 1;

    public function incrementBy(int $value)
    {
        $this->count += $value;
    }

    public function increment(){
        $this->count++;
    }

    public function decrement(){
        $this->count--;
    }


}
