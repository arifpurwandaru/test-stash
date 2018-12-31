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
            'imgLink' => 'carousel1.png',
        ]);

        
        Carousel::create([
            'imgLink' => 'carousel2.jpg',
        ]);
        
        Carousel::create([
            'imgLink' => 'carousel3.jpg',
        ]);
        
        Carousel::create([
            'imgLink' => 'carousel4.jpg',
        ]);
         
    }

}
