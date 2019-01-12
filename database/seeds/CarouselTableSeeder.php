<?php

use Illuminate\Database\Seeder;
use App\Carousel;

class CarouselTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run() {
        Carousel::truncate();

        Carousel::create([
            'imgLink' => 'azzaky1.jpeg',
        ]);

        
        Carousel::create([
            'imgLink' => 'azzaky2.jpeg',
        ]);
        
        Carousel::create([
            'imgLink' => 'azzaky3.jpeg',
        ]);
        
        Carousel::create([
            'imgLink' => 'azzaky4.jpeg',
        ]);
         
    }

}
