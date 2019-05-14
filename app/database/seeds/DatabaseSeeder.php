<?php

class DatabaseSeeder extends Seeder {

	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		Eloquent::unguard();

		$this->call('HiphubSeeder');
	}

}

class HiphubSeeder extends Seeder {

    public function run()
    {
        DB::table('users')->delete();
        $superadmin = User::create(array(
                'id' => 1,
                'fullname' => 'superadmin User',
                'email' => 'superadmin@hipzone.com',
                'password' => Hash::make('hipzone'),
                'level_code' => 'superadmin',
                'remember_token' => false,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $admin = User::create(array(
                'id' => 2,
                'fullname' => 'Admin User',
                'email' => 'adminuser@hipzone.com',
                'password' => Hash::make('hipzone'),
                'level_code' => 'admin',
                'remember_token' => false,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));    
        $reseller = User::create(array(
                'id' => 3,
                'fullname' => 'Reseller User',
                'email' => 'reselleruser@hipzone.com',
                'password' => Hash::make('hipzone'),
                'level_code' => 'reseller',
                'remember_token' => false,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));  
        $brandadmin = User::create(array(
                'id' => 4,
                'fullname' => 'HipRM Brand Admin User',
                'email' => 'hiprmbrandadminuser@hipzone.com',
                'password' => Hash::make('hipzone'),
                'level_code' => 'brandadmin',
                'remember_token' => false,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));   

        DB::table('permissions')->delete();
        $ques_rw = Permission::create(array(
                'id' => 1,
                'name' => 'ques_rw',
                'description' => 'Add/remove Questions',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $media_rw = Permission::create(array(
                'id' => 2,
                'name' => 'media_rw',
                'description' => 'Manage media backgrounds',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $uru_rw = Permission::create(array(
                'id' => 3,
                'name' => 'uru_rw',
                'description' => 'Change User Redirect URL',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $rep_rw = Permission::create(array(
                'id' => 4,
                'name' => 'rep_rw',
                'description' => 'Access Reports Server',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));

        

        // $firstuser->permissions()->attach($ques_rw->id);
        // $firstuser->permissions()->attach($media_rw->id);
        // $seconduser->permissions()->attach($ques_rw->id);

        DB::table('brands')->delete();
        $heinek = Brand::create(array(
                'id' => 1,
                'name' => 'Heineken',
                'description' => 'Heineken Brand',
                'code' => 'HEINEK',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $hansa = Brand::create(array(
                'id' => 2,
                'name' => 'SAB Hansa',
                'description' => 'SAB Hansa Brand',
                'code' => 'XHANSA',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $castle = Brand::create(array(
                'id' => 3,
                'name' => 'SAB Castle',
                'description' => 'SAB Castle Brand',
                'code' => 'CASTLE',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $pernod = Brand::create(array(
                'id' => 4,
                'name' => 'Pernod',
                'description' => 'Pernod Brand',
                'code' => 'PERNOD',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));


        // $firstuser->brands()->attach($heinek->id);
        // $seconduser->brands()->attach($hansa->id);
        // $seconduser->brands()->attach($castle->id);


        DB::table('products')->delete();
        $hipwifi = Product::create(array(
                'id' => 1,
                'name' => 'HipWifi',
                'description' => 'HipWifi Desc',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $hiprm = Product::create(array(
                'id' => 2,
                'name' => 'HipRM',
                'description' => 'HipRM Desc',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $hipjam = Product::create(array(
                'id' => 3,
                'name' => 'HipJAM',
                'description' => 'HipJAM Desc',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));

        $brandadmin->products()->attach($hipwifi->id);

        // $firstuser->products()->attach($hipwifi->id);
        // $seconduser->products()->attach($hiprm->id);
        // $seconduser->products()->attach($hipjam->id);

        DB::table('countries')->delete();
        $sa = Countrie::create(array(
                'id' => 1,
                'name' => 'South Africa',
                'code' => 'ZA',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $ku = Countrie::create(array(
                'id' => 2,
                'name' => 'Kuwait',
                'code' => 'KW',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $uk = Countrie::create(array(
                'id' => 3,
                'name' => 'United Kingdom',
                'code' => 'UK',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));

        DB::table('provinces')->delete();
        $p1 = Province::create(array(
                'id' => 1,
                'name' => 'Gauteng',
                'code' => 'XGT',
                'countrie_id' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $p2 = Province::create(array(
                'id' => 2,
                'name' => 'KZN',
                'code' => 'XKZ',
                'countrie_id' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $p3 = Province::create(array(
                'id' => 3,
                'name' => 'Western Cape',
                'code' => 'XWC',
                'countrie_id' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $p4 = Province::create(array(
                'id' => 4,
                'name' => 'Greater London',
                'code' => 'XGL',
                'countrie_id' => 3,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $p5 = Province::create(array(
                'id' => 5,
                'name' => 'West Midlands',
                'code' => 'XWM',
                'countrie_id' => 3,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $p6 = Province::create(array(
                'id' => 6,
                'name' => 'North East',
                'code' => 'XNE',
                'countrie_id' => 3,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));

        DB::table('cities')->delete();
        $c1 = Citie::create(array(
                'id' => 1,
                'name' => 'Johannesburg',
                'code' => 'JNB',
                'province_id' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $c2 = Citie::create(array(
                'id' => 2,
                'name' => 'Pretoria',
                'code' => 'PTA',
                'province_id' => 1,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $c3 = Citie::create(array(
                'id' => 3,
                'name' => 'Durban',
                'code' => 'DBN',
                'province_id' => 2,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $c4 = Citie::create(array(
                'id' => 4,
                'name' => 'Cape Town',
                'code' => 'CPT',
                'province_id' => 3,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $c5 = Citie::create(array(
                'id' => 5,
                'name' => 'London',
                'code' => 'LHR',
                'province_id' => 4,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $c6 = Citie::create(array(
                'id' => 6,
                'name' => 'Birmingham',
                'code' => 'BHX',
                'province_id' => 5,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $c7 = Citie::create(array(
                'id' => 7,
                'name' => 'Newcastle',
                'code' => 'BHX',
                'province_id' => 6,
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));

        DB::table('servers')->delete();
        $s1 = Server::create(array(
                'id' => 1,
                'hostname' => 'server1.hipzone.co.za',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $s2 = Server::create(array(
                'id' => 2,
                'hostname' => 'server2.hipzone.co.za',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $s3 = Server::create(array(
                'id' => 3,
                'hostname' => 'server3.hipzone.co.za',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));


        DB::table('levels')->delete();
        $l1 = Level::create(array(
                'id' => 1,
                'code' => 'superadmin',
                'name' => 'superadmin',
                'description' => 'Has full rights to administer system',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $l2 = Level::create(array(
                'id' => 2,
                'code' => 'admin',
                'name' => 'Admin',
                'description' => 'Has rights to administer ...',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $l3 = Level::create(array(
                'id' => 3,
                'code' => 'reseller',
                'name' => 'Reseller',
                'description' => 'Has rights to administer ...',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $l4 = Level::create(array(
                'id' => 4,
                'code' => 'brandadmin',
                'name' => 'Brand Admin',
                'description' => 'Has  rights to administer brands',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));

        DB::table('venues')->delete();
        $v1 = Venue::create(array(
                'id' => 1,
                'fullsitename' => 'Kauai Bayside',
                'sitename' => 'Bayside',
                'location' => 'HIPXKAUAIXXXBAYSIDECPTWCZA',
                'macaddress' => '00-1E-E5-84-A4-18',
                'latitude' => '-33.823651',
                'longitude' => '    18.489668',
                'address' => 'Shop E3, Bayside Centre, Table View 7441',
                'server_id' => '2',
                'contact' => '0828888888',
                'notes' => 'Kauai Bayside Notes',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
        $v2 = Venue::create(array(
                'id' => 2,
                'fullsitename' => 'Kauai Brooklyn',
                'sitename' => 'Brooklyn',
                'location' => 'HIPXKAUAIXXBROOKLYNPTAGTZA',
                'macaddress' => '00-21-29-A2-50-8B',
                'latitude' => '',
                'longitude' => '',
                'address' => 'Brooklyn Square, Corner Middle & Veale Street, Pretoria',
                'server_id' => '3',
                'contact' => '0822222222',
                'notes' => 'Kauai Brooklyn Notes',
                'created_at' => new DateTime,
                'updated_at' => new DateTime
        ));
    }


}