<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder {
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()	{
		DB::table('users')->insert([
			'name' => 'Sander Buruma',
			'email' => 'sanderburuma@gmail.com',
			'password' => '$2y$10$jQY5ljXUPHkKlkREkFhC8eJh6FiDSgo/tGFspsC.1phnt.UgcQhp.', //see the default laravel password entry in Bitwarden
			'created_at' => '2018-12-03 09:49:03',
			'updated_at' => '2018-12-03 09:49:03',
			'email_verified_at' => '2018-12-03 09:50:33',
		]);
		
		DB::table('roles')->insert([
			'name' => 'Approved',
		]);
		DB::table('roles')->insert([
			'name' => 'Moderator',
		]);
		DB::table('roles')->insert([
			'name' => 'Admin',
		]);
		DB::table('roles')->insert([
			'name' => 'Banned',
		]);
		
		DB::table('role_user')->insert([
			'user_id' => '1',
			'role_id' => '1',
		]);
		DB::table('role_user')->insert([
			'user_id' => '1',
			'role_id' => '2',
		]);
		DB::table('role_user')->insert([
			'user_id' => '1',
			'role_id' => '3',
		]);
		$this->call([
			TextsTableSeeder::class,
		]);

		foreach(explode(',',"All,Bible,Pope,Saint,Missal,Prayer") as $v) {
			DB::table('categories')->insert([
				'name' => $v,
			]);
		}

		$count = 0;
		foreach(
			[null, // All
			"Genesis,Exodus,Leviticus,Numbers,Deuteronomy,Joshua,Judges,Ruth,1 Kings,2 Kings,3 Kings,4 Kings,1 Paralipomenon,2 Paralipomenon,1 Esdras,2 Esdras (Nehemiah),Tobias,Judith,Esther,Job,Psalms,Proverbs,Ecclesiastes,Canticle of Canticles,Wisdom,Sirach (Ecclesiasticus),Isaias,Jeremias,Lamentations,Baruch,Ezechiel,Daniel,Osee,Joel,Amos,Abdias,Jonas,Micheas,Nahum,Habacuc,Sophonias,Aggeus,Zacharias,Malachias,1 Machabees,2 Machabees,Matthew,Mark,Luke,John,Acts of Apostles,Romans,1 Corinthians,2 Corinthians,Galatians,Ephesians,Philippians,Colossians,1 Thessalonians,2 Thessalonians,1 Timothy,2 Timothy,Titus,Philemon,Hebrews,James,1 Peter,2 Peter,1 John,2 John,3 John,Jude,Apocalypse", // Bible Books
			"Peter,Linus,Anacletus,Clement I,Evaristus,Alexander I,Sixtus I,Telephorus,Hyginus,Pius I,Anicetus,Soter,Eleutherius,Victor,Zephyrinus,Callistus I,Urban,Anterus,Fabian,Cornelius,Lucius I,Stephen I,Sixtus II,DionysiusJan,Felix I,Eutychian,Caius,Marcellinus308,Marcellus I,Eusebius,MiltiadesJan,Sylvester I,MarkFeb,Julius I,Liberius,Damasus I,Siricius,Anastasius I,Innocent I,Zosimus,Boniface I,Celestine I,Sixtus III,Leo I,Hilarius,Simplicius,Felix III,Gelasius I,Anastasius II,SymmachusJul,HormisdasAug,John I,Felix IV,Boniface II,John II,Agapetus I,Silverius,Vigilius,Pelagius I,John III,Benedict I,Pelagius II,Gregory I,SabinianFeb,Boniface III,Boniface IV,Adeodatus I,Boniface V,Honorius I,Severinus,John IV,Theodore I,Martin I,Eugene I,Vitalian,Adeodatus II,DonusJun,Agatho,Leo II,Benedict II,John V,Conon,Sergius I,John VI,John VII,Sisinnius,Constantine,Gregory II,Gregory III,Zachary,Pope-elect Stephen,Stephen II,Paul I,Stephen III,Adrian I,Leo III,Stephen IV,Paschal I,Eugene II,Valentine,Gregory IV,Sergius II,Leo IV,Benedict III,Nicholas I,Adrian II,John VIII,Marinus I,Adrian III,Stephen V,Formosus,Boniface VI,Stephen VI,Romanus,Theodore II,John IX,Benedict IV,Leo V,Sergius III,Anastasius III,Lando,John X,Leo VI,Stephen VII,John XI,Leo VII,Stephen VIII,Marinus II,Agapetus II,Benedict V,Leo VIII,John XIII,Benedict VI,John XIV,John XV,Gregory V,Sylvester II,John XVII,John XVIII,Sergius IV,Benedict VII,John XIX,Benedict IX,Sylvester III,Benedict IX,Gregory VI,Clement II,Benedict IX,Damasus II,Leo IX,Stephen IX,Nicholas II,Alexander II,Gregory VII,Victor III,Urban II,Paschal II,Gelasius II,Calllixitus II,Honorius II,Innocent II,Celestine II,Lucius II,Eugene III,Anastasius IV,Adrian IV,Alexander III,Lucius III,Urban III,Gregory VIII,Clement III,Celestine III,Innocent III,Honorius III,Gregory IX,Celestine IV,Innocent IV,Alexander IV,Urban IV,Clement IV,Gregory X,Adrian V,John XXI,Nicholas III,Martin IV,Honorius IV,Nicholas IV,Celestine V,Boniface VIII,Benedict XI,Clement V,John XXII,Benedict XII,Clement VI,Innocent VI,Urban V,Gregory XI,Urban VI,Boniface IX,Innocent VII,Gregory XII,Martin V,Eugene IV,Nicholas V,Callixtus III,Pius II,Paul II,Sixtus IV,Innocent VIII,Pius III,Julius II,Leo X,Adrian VI,Clement VII,Paul III,Julius III,Marcellus II,Paul IV,Pius IV,St Pius V,Gregory XIII,Sixtus V,Urban VII,Gregory XIV,Innocent IX,Clement VIII,Leo XI,Paul V,Gregory XV,Urban VIII,Innocent X,Alexander VII,Clement IX,Clement X,Innocent XI,Alexander VIII,Innocent XII,Clement XI,Innocent XIII,Benedict XIII,Clement XII,Benedict XIV,Clement XIII,Clement XIV,Pius VI,Pius VII,Leo XII,Pius VIII,Gregory XVI,Pius IX,Leo XIII,St. Pius X,Benedict XV,Pius XI,Pius XII,John XXIII,Paul VI,John Paul I,John Paul II,Benedict XVI,Francis", // Popes
			"Mother Mary,John the Baptist,Peter the Rock,John the Beloved,Mary Magdalene,Michael,Longinus,Dismus", // Saints
			"Foot Altar,Offertory,Canon,Communion,Pre-Communion,Post-Communion,Introit,Epistle,Gospel,Sanctus,The Last Gospel", // Missal
			"Litany of Loretto,Litany of the Precious Blood"// Prayer
			] as $v) {
			$count++;
			DB::table('subcategories')->insert([
				'name' => 'All',
				'category_id' => $count,
			]);
			foreach (explode(',',$v) as $vv) {
				if (strlen($vv) > 0) {
					DB::table('subcategories')->insert([
						'name' => $vv,
						'category_id' => $count,
					]);
				}
			}
		}

		
		$this->call([
			BibleTextsSeeder::class,
		]);
	}
}
