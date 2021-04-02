<?php
error_reporting(E_ALL);
ini_set('display_errors',true);
ini_set('display_startup_errors',true);

require('./vendor/autoload.php');

include('./config/env.php');

foreach (glob('./data/*.php') as $file) {
    include($file);
}
foreach (glob('./control/*.php') as $file) {
    include($file);
}

include ('./page/fct_date.php');

$faker = Faker\Factory::create('fr_FR');

switch ($argv[1]) {
    case "migrate" :
        artisan_migrate();
        break;
    case "seed" :
        artisan_seed();
        break;
    default :
        var_dump(" This option doesn't exist !");
        break;
}


function artisan_migrate() {
    artisan_migrate_minimum();
    artisan_migrate_project();
}


function artisan_seed() {
    artisan_seed_minimum();
    artisan_seed_project();
}

function get_to_remove() {
    return [
        'property',
        'propertyType',
        'mandate',
        'mandateType',
        'mandateFile',
        'owner',
        'town',
        'country',
        'diagnosis',
        'diagnosisType',
        'picture'
    ];
}

function artisan_migrate_project() {
    // Here is the begining of your project. You can create all the tables you need
    // A example is made with a table named "example"

    // First :  drop the tables if exists
    echo "DROPPING ALL YOUR TABLES : ";
    echo (0 ==Connection::exec('SET FOREIGN_KEY_CHECKS=0;')) ? '-' : 'x';
    foreach (get_to_remove() as $del) {
        echo (0 ==Connection::exec('DROP TABLE IF EXISTS '.$del.';')) ? '-' : 'x';
    };
    echo (0 ==Connection::exec('SET FOREIGN_KEY_CHECKS=1;')) ? '-' : 'x';

    // Second : create your tables
    echo "\nCREATING ALL YOUR TABLES : ";
    $requests = [
        'CREATE TABLE property (
            id int AUTO_INCREMENT PRIMARY KEY,
            ref VARCHAR(255),
            title VARCHAR(255),
            description TEXT,
            address VARCHAR(255),
            area float,
            livingRoomsNumber int
        );',
        'CREATE TABLE propertyType (
            id int AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255)
        );',
        'CREATE TABLE mandate (
            id int AUTO_INCREMENT PRIMARY KEY,
            ref VARCHAR(255),
            price float,
            agencyFees float,
            consultantBenefit float,
            signatureDate date,
            status int
        );',
        'CREATE TABLE mandateType (
            id int AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255)
        );',
        'CREATE TABLE mandateFile (
            id int AUTO_INCREMENT PRIMARY KEY,
            filePath VARCHAR(255),
            name VARCHAR(255)
        );',
        'CREATE TABLE owner (
            id int AUTO_INCREMENT PRIMARY KEY,
            firstName VARCHAR(255),
            lastName VARCHAR(255),
            email VARCHAR(255),
            phoneNumber VARCHAR(255),
            address VARCHAR(255)
        );',
        'CREATE TABLE town (
            id int AUTO_INCREMENT PRIMARY KEY,
            postalCode CHAR(5),
            name VARCHAR(255)
        );',
        'CREATE TABLE country (
            id int AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255)
        );',
        'CREATE TABLE diagnosis (
            id int AUTO_INCREMENT PRIMARY KEY,
            filePath VARCHAR(255),
            establishDate date
        );',
        'CREATE TABLE diagnosisType (
            id int AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255)
        );',
        'CREATE TABLE picture (
            id int AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            filePath VARCHAR(255)
        );'
    ];

    foreach ($requests as $request) {
        echo (0==Connection::exec($request)) ? '-' : 'x';
    };

    /// third: foreign key
    echo "\nCREATING FOREIGN KEYS : ";

    $foreign_keys = [
        "property" => [
            "town_id" => "town",
            "mandate_id" => "mandate"
        ],
        "diagnosis" => [
            "property_id" => "property",
            "propertyType_id" => "propertyType"
        ],
        "diagnosisType" => [
            "diagnosis_id" => "diasgnosis"
        ],
        "picture" => [
            "property_id" => "property"
        ],
        "mandate" => [
            "owner_id" => "owner",
            "mandateType_id" => "mandateType",
            "entererUser_id" => "user"
        ],
        "owner" => [
            "liveTown_id" => "town"
        ],
        "town" => [
            "country_id" => "country"
        ]

    ];

    foreach ($foreign_keys as $source_table => $links) {
        foreach ($links as $source_column => $distant_table) {
            echo (0==Connection::safeExec(
                "ALTER TABLE ".$source_table." ADD ".$source_column." INT;
                ALTER TABLE ".$source_table." ADD FOREIGN KEY (".$source_column.") REFERENCES ".$distant_table."(id);",
                []
            )) ? "-" : "x";
        }
    }



    echo "\n";

}

function artisan_seed_project() {
    // Here is the beginning of your project. You can seed all the tables you need
    // A exemple is made with a table named "exemple"

    // First : empty your tables 
    echo "EMPTY ALL YOUR TABLES : ";
    echo (0 == Connection::exec('SET FOREIGN_KEY_CHECKS=0;')) ? '-' : 'x';
    foreach (get_to_remove() as $del) {
        echo (0 ==Connection::exec('TRUNCATE TABLE '.$del.';')) ? '-' : 'x';
    };
    echo (0 == Connection::exec('SET FOREIGN_KEY_CHECKS=1;')) ? '-' : 'x';
    echo "\n";

        function seedProperty($nbProperty){
        echo "ADD RECORDS TABLE property : ";
        for ($i=0;$i<$nbProperty;$i++) {
            gc_collect_cycles();
            $faker = \Faker\Factory::create('fr_FR');
            $ref = $faker->randomNumber(6);
            $title = $faker->realText(20, 1);
            $description = $faker->realText(50, 1);
            $address = $faker->streetAddress();
            $area = $faker->randomNumber($faker->randomNumber(1));
            $livingRoomsNumber = $faker->randomNumber(1);
            $property = [
                'ref' => $ref,
                'title' => $title,
                'description' => $description,
                'address' => $address,
                'area' => $area,
                'livingRoomsNumber' => $livingRoomsNumber,
                'town_id' => rand(0, 100),
            ];
            Connection::insert('property', $property);
            echo '-';
        }
    }
    function seedMandateType($nbMandateType) {
        echo "\nADD RECORDS INTO TABLE mandateType :";
        for ($i=0; $i < $nbMandateType; $i++) {
            Connection::insert(
                'mandateType',
                [
                    'name' => "mandate type n°".$i
                ]
                );
                echo '-';
        }
    }

    function seedMandate($nbMandate){
        echo "\nADD RECORDS INTO TABLE mandate : ";
        for ($i=0;$i<$nbMandate;$i++) {
            $faker = Faker\Factory::create();
            $ref = $faker->randomNumber(6);
            $price = $faker->numberBetween(0.1, 500000);
            $agencyFees = $price * 0.05;
            $consultantBenefit = 0;
            $signatureDate = $faker->dateTimeThisCentury->format('Y-m-d');
            if ($price < 29000) {
                $consultantBenefit = $agencyFees * 0.7;
            } elseif ($price < 49000) {
                $consultantBenefit = $agencyFees * 0.75;
            } elseif ($price < 69000) {
                $consultantBenefit = $agencyFees * 0.8;
            } elseif ($price < 89000) {
                $consultantBenefit = $agencyFees * 0.85;
            } elseif ($price < 149000) {
                $consultantBenefit = $agencyFees * 0.9;
            } elseif ($price < 189000) {
                $consultantBenefit = $agencyFees * 0.95;
            } elseif ($price > 189000) {
                $consultantBenefit = $agencyFees * 0.99;
            }
            $status = $faker->word();
            $mandate = [
                'ref' => $ref,
                'price' => $price,
                'agencyFees' => $agencyFees,
                'consultantBenefit' => $consultantBenefit,
                'signatureDate' => $signatureDate,
                'status' => rand(0, 10),
            ];
            Connection::insert('mandate', $mandate);
            echo '-';
        }
    }
    function seedOwner($nbOwner){
        echo "\nADD RECORDS INTO TABLE owner :";
        for ($i=0;$i<$nbOwner;$i++) {
            $faker = Faker\Factory::create('fr_FR');
            $firstName = $faker->firstName();
            $lastName = $faker->lastName();
            $email = strtolower(utf8_decode($firstName[0])) . '.' . strtolower(utf8_decode($lastName)) . '@test.fr';
            $phoneNumber = strval($faker->randomDigit(8));
            $address=$faker->address();
            $owner = [
                'firstName' => $firstName,
                'lastName' => $lastName,
                'email' => $email,
                'phoneNumber' => $phoneNumber,
                'address' => $address,
            ];
            Connection::insert('owner', $owner);
            echo '-';
        }
    }
    function seedTown($nbTown){
        echo "\nADD RECORDS INTO TABLE town :";
        for ($i=0;$i<$nbTown;$i++) {
            $faker = Faker\Factory::create();
            $postalCode = rand(0, 99999);
            $name = $faker->city();
            $town = [
                'postalCode' => $postalCode,
                'name' => $name,
                'country_id' => rand(1, 10)
            ];
            Connection::insert('town', $town);
            echo '-';
        }
    }
    function seedCountry($nbCountry){
        echo "ADD RECORDS TABLE country :";
        for ($i=0;$i<$nbCountry;$i++) {
            $faker = Faker\Factory::create();
            $name = $faker->state();
            $country = [
                'name' => $name,
            ];
            Connection::insert('country', $country);
            echo '-';
        }
    }
    function seedMandateFile($nbMandateFile){
        echo "ADD RECORDS TABLE mandateFile :";
        for ($i=0;$i<$nbMandateFile;$i++) {
            $faker = Faker\Factory::create();
            $name = $faker->word();
            $filePath = 'C:/file/Mandate/' . $faker->word();
            $mandateFile = [
                'filePath' => $filePath,
                'name' => $name,
            ];
            Connection::insert('mandateFile', $mandateFile);
            echo '-';
        }
    }
    function seedDiagnosis($nbDiagnosis){
        echo "ADD RECORDS TABLE diagnosis :";
        for ($i=0;$i<$nbDiagnosis;$i++) {
            $faker = Faker\Factory::create();
            $establishDate = $faker->dateTimeThisCentury->format('Y-m-d');
            $filePath = 'C:/file/Diagnosis/' . $faker->word();
            $diagnosis = [
                'filePath' => $filePath,
                'establishDate' => $establishDate,
            ];
            Connection::insert('diagnosis', $diagnosis);
            echo '-';
        }
    }
    function seedDiagnosisType($nbDiagnosisType){
        echo "ADD RECORDS TABLE diagnosisType :";
        for ($i=0;$i<$nbDiagnosisType;$i++) {
            $faker = Faker\Factory::create();
            $name = $faker->word();
            $diagnosisType = [
                'name' => $name,
            ];
            Connection::insert('diagnosisType', $diagnosisType);
            echo '-';
        }
    }

    //country
    seedCountry(10);
    //town
    seedTown(100);
    //property
    seedProperty(100);
    //mandateType
    seedMandateType(10);
    //mandate
    seedMandate(100);
    //owner
    seedOwner(100);
    //mandateFile
    seedMandateFile(100);
    //diagnosis
    seedDiagnosis(100);
    //diagnosisType
    seedDiagnosisType(100);
}


function artisan_migrate_minimum() {
    
    // First : drop tables if exists
    echo "DROPPING ALL MINIMUM TABLES : ";
    echo (0 ==Connection::exec('SET FOREIGN_KEY_CHECKS=0;')) ? '-' : 'x';
    echo (0 ==Connection::exec('DROP TABLE IF EXISTS user;')) ? '-' : 'x';
    echo (0 ==Connection::exec('DROP TABLE IF EXISTS role;')) ? '-' : 'x';
    echo (0 ==Connection::exec('DROP TABLE IF EXISTS can;')) ? '-' : 'x';
    echo (0 ==Connection::exec('DROP TABLE IF EXISTS permission;')) ? '-' : 'x';
    echo (0 ==Connection::exec('SET FOREIGN_KEY_CHECKS=1;')) ? '-' : 'x';
    
    // Second : Create tables
    echo "\nCREATING ALL MINIMUM TABLES : ";
    $request =  'CREATE TABLE IF NOT EXISTS user (
        id int AUTO_INCREMENT PRIMARY KEY,
        firstName VARCHAR(255),
        lastName VARCHAR(255),
        email VARCHAR(255),
        password VARCHAR(255),
        isAdmin int,
        role_id int
        );';
    echo (0 ==Connection::exec($request)) ? '-' : 'x';

    $request =  'CREATE TABLE IF NOT EXISTS role (
                id int AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(50)
                );';
    echo (0 ==Connection::exec($request)) ? '-' : 'x';
    
    $request=   'CREATE TABLE IF NOT EXISTS can (
                id int AUTO_INCREMENT PRIMARY KEY,
                role_id int,
                permission_id int
                );';
    echo (0 ==Connection::exec($request)) ? '-' : 'x';

    $request =  'CREATE TABLE IF NOT EXISTS permission (
                id int AUTO_INCREMENT PRIMARY KEY,
                control VARCHAR(50),
                action VARCHAR(50)
                );';
    echo (0 ==Connection::exec($request)) ? '-' : 'x';

    // Third : Alter tables to add Foreign Keys
    echo "\nADDING ALL MINIMUM FOREIGN KEYS : ";
    $request =  'ALTER TABLE user 
                ADD CONSTRAINT fk1_role_id
                FOREIGN KEY (role_id) REFERENCES role(id)
                ON DELETE RESTRICT
                ON UPDATE RESTRICT;';
    echo (0 ==Connection::exec($request)) ? '-' : 'x';

    $request =  'ALTER TABLE can 
                ADD CONSTRAINT fk2_role_id
                FOREIGN KEY (role_id) REFERENCES role(id)
                ON DELETE RESTRICT
                ON UPDATE RESTRICT;';
    echo (0 ==Connection::exec($request)) ? '-' : 'x';

    $request =  'ALTER TABLE can 
                ADD CONSTRAINT fk_permission_id
                FOREIGN KEY (permission_id) REFERENCES permission(id)
                ON DELETE RESTRICT
                ON UPDATE RESTRICT;';
    echo (0 ==Connection::exec($request)) ? '-' : 'x';
    echo "\n";

}

function artisan_seed_minimum() {
    
    // First : empty tables
    echo "EMPTY ALL MINIMUM TABLES : ";
    echo (0 == Connection::exec('SET FOREIGN_KEY_CHECKS=0;')) ? '-' : 'x';
    echo (0 == Connection::exec('TRUNCATE user')) ? '-' : 'x';
    echo (0 == Connection::exec('TRUNCATE role')) ? '-' : 'x';
    echo (0 == Connection::exec('TRUNCATE can')) ? '-' : 'x';
    echo (0 == Connection::exec('TRUNCATE permission')) ? '-' : 'x';
    echo (0 == Connection::exec('SET FOREIGN_KEY_CHECKS=1;')) ? '-' : 'x';
    echo "\n";
    
    // second : seed tables
    function seedRoles(){
        echo "ADD RECORDS IN TABLE role : ";
        $roles=['Directeur de service','Membre de service','Commercial'];
        foreach ($roles as $role) {
            Connection::insert('role',['name'=>$role]);
            echo '-';
        } 
        echo "\n";
    }

    function seedPermisions(){
        echo "ADD RECORDS IN TABLE permission : ";
        $permissions=[
                        ['control'=>'user','action'=>'default'],
                        ['control'=>'user','action'=>'details'],
                        ['control'=>'user','action'=>'modify'],
                        ['control'=>'user','action'=>'viewall'],
                    ];
        foreach ($permissions as $permission) {
            Connection::insert('permission',['control'=>$permission['control'],'action'=>$permission['action']]);
            echo '-';
        }
        echo "\n";
    }

    function seedCan(){
        echo "ADD RECORDS IN TABLE can : ";
        $cans=[
                ['role_id'=>1,'permission_id'=>1],
                ['role_id'=>1,'permission_id'=>2],
                ['role_id'=>1,'permission_id'=>3],
                ['role_id'=>1,'permission_id'=>4],
                ['role_id'=>2,'permission_id'=>1],
                ['role_id'=>2,'permission_id'=>2],
                ['role_id'=>2,'permission_id'=>3],
                ['role_id'=>3,'permission_id'=>1],
                ['role_id'=>3,'permission_id'=>2],
                ['role_id'=>3,'permission_id'=>3],
            ];
        foreach ($cans as $can) {
            Connection::insert('can',['role_id'=>$can['role_id'],'permission_id'=>$can['permission_id']]);
            echo '-';
        } 
        echo "\n";
    }

    function seedRandomUser($isAdmin){
        $faker = Faker\Factory::create('fr_FR');
        $firstName=$faker->firstName();
        $lastName=$faker->lastName();
        $email=strtolower(utf8_decode($firstName[0])).'.'.strtolower(utf8_decode($lastName)).'@test.fr';
        $user = [
            'firstName' => $firstName,
            'lastName' => $lastName, 
            'email' => $email, 
            'password' => sha1('pwsio'), 
            'role_id' => $faker->numberBetween(1,3),
            'isAdmin' => $isAdmin
        ];
        //var_dump($user);
        // Make sure it dosen't aleadry exists
        if(Connection::safeQuery('select count(*) as count from user where email=?', [$user['email']])[0]['count']==0) {
            Connection::insert('user', $user);
        }
    }

    function seedUserSIO(){
        $user = [
            'firstName' => 'user',
            'lastName' => 'SIO', 
            'email' => 'usersio@test.fr', 
            'password' => sha1('pwsio'), 
            'role_id' => 1,
            'isAdmin' => 1
        ];
       Connection::insert('user', $user);
    }

    function seedUsers($nbUsers)
    {
        echo "ADD RECORDS IN TABLE user : ";
        seedUserSIO();
        echo '-';
        for ($i=0;$i<$nbUsers;$i++){
            if ($i==0){
                seedRandomUser(1);
            }
            else{
                seedRandomUser(0);
            }
            echo '-';
        }
        echo "\n";      
    }

    //roles
    seedRoles();
    //permissions
    seedPermisions();
    //can
    seedCan();
    //users
    seedUsers(100);
}


