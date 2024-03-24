<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Service;

class ServicesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // انشاء عشرة خدمات عشوائية باستخدام الـ factory
        // \App\Models\Service::factory(10)->create();
        $services = [
                  [ "id"=> "1", "name"=> "الصيانة المتنقلة" ],
                [ "id"=> "2", "name"=> "السحب والنقل" ],
                [ "id"=> "3", "name"=> "تعبئة الوقود" ],
                [ "id"=> "4", "name"=> "شحن البطارية" ],
                [ "id"=> "5", "name"=> "فتح الأقفال" ],
            ];
            foreach($services as $service){
                $ser = new Service();
                $ser->ServiceID = $service['id'];
                $ser->Name = $service['name'];
                $ser->save();
            };
        }
}
