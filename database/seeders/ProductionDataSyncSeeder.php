<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Tenant;
use App\Models\Warehouse;
use App\Models\User;
use App\Models\Customer;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class ProductionDataSyncSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Sync Roles and Permissions (Global/System)
        $this->call(PermissionSeeder::class);

        // 2. Main Tenant: Logy Express
        $tenant1 = Tenant::updateOrCreate(
            ['subdomain' => 'logiexpress'],
            [
                'uuid' => 'b2f91dec-c418-4d6b-ad46-73038127ba12',
                'name' => 'Logy Express',
                'domain' => 'curriersite-production.up.railway.app', // Added for Railway
                'status' => 'active',
                'plan_id' => 5,
                'locale' => 'es',
                'settings_json' => [
                    'currency' => 'USD',
                    'points_per_pound' => 5,
                    'loyalty_enabled' => true,
                    'box_number_prefix_air' => 'LGX',
                    'box_number_template_air' => '{PREFIX}{ID} {NAME}',
                    'service_air_enabled' => true,
                    'air_address' => '2610 NW 89th CT',
                    'air_city' => 'Doral',
                    'air_state' => 'Florida',
                    'air_zip_code' => '33172-1615',
                    'air_phone' => '+1 (305) 848-1127',
                    'service_maritime_enabled' => true,
                    'maritime_address' => '2610 NW 89th CT',
                    'maritime_city' => 'Doral',
                    'maritime_state' => 'Florida',
                    'maritime_zip_code' => '33172-1615',
                    'maritime_phone' => '+1 (305) 848-1127',
                    'box_number_counter' => 1236,
                    'force_password_change' => true,
                ],
                'theme_config_json' => [
                    'primary_color' => '#3b7ddd',
                    'theme_mode' => 'light',
                ],
                'enabled_reports_json' => ['inventory_stock', 'revenue_daily', 'customer_debt', 'package_status', 'volume_weight', 'stagnant_cargo', 'driver_efficiency', 'tax_collection'],
            ]
        );

        // 3. Main Admin User
        $adminUser = User::updateOrCreate(
            ['email' => 'admin@empresa1.com'],
            [
                'tenant_id' => $tenant1->id,
                'name' => 'Admin Logy Express',
                'password' => bcrypt('password'),
                'role' => 'admin',
            ]
        );

        // 3b. Create Admin Role for Tenant and Assign to User
        $adminRole = Role::updateOrCreate(
            ['tenant_id' => $tenant1->id, 'name' => 'Administrador'],
            ['description' => 'Acceso total al sistema']
        );

        // Assign all current permissions to this role
        $allPermissions = Permission::all();
        $adminRole->permissions()->sync($allPermissions->pluck('id'));

        // Link User to Role
        $adminUser->update(['role_id' => $adminRole->id]);

        // 4. Warehouses for Logy Express
        Warehouse::updateOrCreate(
            ['code' => 'MIA-01', 'tenant_id' => $tenant1->id],
            [
                'name' => 'Miami Main Hub',
                'address' => '8400 NW 25th St',
                'city' => 'Miami',
                'state' => 'FL',
                'zip_code' => '33178',
                'country' => 'USA',
                'service_type' => 'both',
                'is_active' => true,
            ]
        );

        // 5. CLEAN UP EXISTING CUSTOMERS (Requested by user)
        echo "Limpiando base de datos de clientes...\n";
        Customer::where('tenant_id', $tenant1->id)->delete();
        User::where('tenant_id', $tenant1->id)->where('role', 'customer')->delete();

        // 6. FULL REAL CUSTOMER LIST
        $dataRaw = "LGX319 David Arauz;David Arauz;davidarauz@cableonda.net;6930-5350 ;
LGX522 Euris Ortega;Euris Ortega;Info@detalleslace.com;6112-1957;
LGX174 Pma Logistic ;Yaneth Dominguez;Jdominguez@pmalogistics.com;6747-1880;
LGX172 Yarelis Jhonson;Yarelis Jhonson;Naomycastillo1609@gmail.com;6654-9112;
LGX220 Campatech;Victor Chu;vicjian@gmail.com;6676-1977;
LGX426 Margarita Shops;Estefania Ortiz;Estefania-268@hotmail.com;6399-6530;
LGX496 Vsautoworks;Edgar Delgado;vsautoworkspty@gmail.com;6320-8074;
LGX100 Paul Bustavino ;Paul Bustavino;pjenner22@live.com;6600-0084;
LGX422 Javier Romero;Javier Romero;Jdrm2525@gmail.com;6215-8317;
LGX504 Andrea Pinto;Andrea Pinto;Andreapm2593@gmail.com;6480-7912;
LGX370 Zhuo Min Chen;Cristobal Chen;cristobalchen@gmail.com;6623-8988;
LGX534 Fernando Salazar;Fernando Salazar;Sandrix989@hotmail.com;6032-1574;
LGX378 Ana de Urriola;Ana de Urriola;anadeurriola@gmail.com;6061-9759;
LGXTB Fernando Samaniego;Fernando Samaniego;fsamaniego1982@gmail.com;6618-8593;
LGXCG Colon Global Holding;Jonathan Burillo;info@cargoairpanama.com;6631-2101;
LGX297 Brayan Carbajal;Brayan Carbajal;carbajalbrayan@gmail.com;6346-3034;
LGX Purepro S.A.;Purepro S.A.;Ventas@purepro.com.pa;6230-2370;
LGX535 Gian Gfeller;Gian Gfeller;giangfeller@gmail.com;6980-9212;
LGX100 Maria Alexandra;Maria Alexandra;Malexvergaratello@gmail.com;6487-2525;
LGX289 Dixiana Candanedo;Dixiana Candanedo;Didicandanedo@hotmail.com;6776-3921;
LGX436 Eric Castillo;Eric Castillo;Erictitocastillo@gmail.com;6007-1914;
LGX368 Ana Mendieta;Ana Mendieta;annacarolina0612@gmail.com;6889 9958;
LGX473 Relda Diaz;Relda Diaz;reldadiaz@gmail.com;6230-8886;
LGX462 MG Impresiones;Raul Moran;raulmoran@mgimpresiones.com;6677-0380 ;
LGX486 Juan Alberto Chong;Juan Alberto Chong;juan@promomails.com;6616-8181 ;
LGX479 Red Claud;Red Claud;contabilidad@redcloud.la;6679-1611;
LGX100 Cosugesa;Orlando Calvo;Ocalvo@consugesa.com;6929-6387;
LGX528 Juan Parra;Juan Parra;Tallereuroamericapty@gmail.com;6521-9550;
LGX508 Esteban Lemus;Esteban Lemus;ejl5880@hotmail.com;6230-2370;
LGX444 Jesus Peraza;Jesus Peraza;jdperaza1@hotmail.com;6661-0639;
LGXLC Gabriel Sanchez;Gabriel Sanchez;airpostlachorrera@gmail.com;6270-3946;
LGXL&J Yesiree Sevillanos ;Yesiree Sevillanos;ljsolucionesintg@gmail.com;6163-5952;
LGX192 Mitzel Carrera;Mitzel Carrera;mitzel_carrera@hotmail.com;6108-5482;
LGX237 Thelma Guardia;Thelma Guardia;kuri_27@hotmail.com;6536-2625;
LGX353 Jorge Ruedas;Jorge Ruedas;jorgerueda2929@gmail.com;6242-1542;
LGX515 Maria Villarroel;Maria Villarroel;mariaale@gmail.com;6126-5256;
LGX427 Alejandra Williams;Alejandra Williams;Apwg20@gmail.com;6633-1625;
LGX100 Cristhian Leonard;Cristhian Leonard;;6006-6561;
LGX464 Victor Valdez;Victor Valdez;vvaldez.solano@gmail.com;6639-3768;
LGX100 Oriel Perez;Oriel Perez;Orielperez2289@gmail.com;6119-8604;
LGX100 Carolina Rios;Carolina Rios;carolina.321.cr@gmail.com;6980-0947;
LGX247 Patricia Pino;Patricia Pino;Patriciapino@hotmail.com;6070-6215;
LGX242 Victor Chipantiza;Victor Chipantiza;chiprins1974@gmail.com;6501-7994;
LGX328 Miriam Tejeira;Miriam Tejeira;miriam.tejeira@gmail.com;6796-4639;
LGX324 Mohit Mayani;Mohit Mayani;mohit.mayani@gmail.com;6616-0435;
LGX524 Juan Creigthon;Juan Creigthon;Juancreighto7@gmail.com;6577-7371;
LGX526 María José Santil;María José Santil;mariajosesantilmarquina@gmail.com;6507-3514;
LGX100 Alejandro Baez;Alejandro Baez;alejandro-baez06@hotmail.com;6679-8196;
LGX154 Jairo Rodriguez;Jairo Rodriguez;jairo.rodriguez21584@gmail.com;6780-9021;
LGX152 Giovanny Merizalde;Giovanny Merizalde;gmb_ecu@hotmail.com;6227-7949;
LGX490 Isabel Justiniani ;Isabel Justiniani ;Justinisabel10@gmail.com;6738-7535;
LGX100 Marianny Lemus;Marianny Lemus;Marianny.lemus@gmail.com;6232-9251;
LGXPFL Javier Figueroa;Javier Figueroa;navaselias29@gmail.com;6257-9891;
LGX204 Ariel Suira;Ariel Suira;arielsn3@hotmail.com;6871-2130;
LGX533 Angel Camargo;Angel Camargo;Infodiazg@gmail.com;61006948;
LGX532 Oliver Martin;Oliver Martin;Ojmn1977@gmail.com;6983-1397;
LGX131 Alison Lozano;Alison Lozano;alilozano8@hotmail.com;6026-1458;
LGX314 Sandra Miro;Sandra Miro;Sandymiro24@gmail.com;69803460;
LGX531 Euclides Serracin;Euclides Serracin;EUSER1981@GMAIL.COM;64747010;
LGX380 Jacobdo Warszawczyk;Jacobdo Warszawczyk;jacobowars@hotmail.com;6674-0022;
LGX446 Grupo HR, S.A.;Grupo HR, S.A.;ventas@grupohr.com.pa;6617-48115;
LGX529 Alvin Hou Gan;Alvin Hou Gan;alvinhougancompanyc@gmail.com;60254438;
LGX530 Robinson Alfonso;Robinson Alfonso;mensajeriaalfonso@gmail.com;66570644 ;
LGXWF Well Fast;Zoraya Macias;maciasrommel8@gmail.com;6242-201;
LGX279 Mariling Tamoy;Mariling Tamoy;marilingtv14@gmail.com;6452-4561;
LGX353 Jorge Rueda;Jorge Ruedas;jorgerueda2929@gmail.com;6242-1542;
LGX447 Jesus Quiel ;Jesus Quie;Jesusquiel0909@gmail.com;67332293 ;
LGX504 Jeremiah Williams;Jeremiah Williams;jeree_14dt@hotmail.com;6047-3070;
LGX471 Kayla Almanza;Kayla Almanza;kalmanzac@yahoo.com;60040143;
LGX480 Nestor Ureña;Nestor Ureña;nestor.urena@gmail.com;61414423;
LGX259 Diana Rodriguez;Diana Rodriguez;www.dianarod@gmail.com;64539783;
LGX456 Orlando Sanchez;Orlando Sanchez;Soyorlandosanchez@gmail.com;62031202;
LGX216 Cristian Alyiber ;Cristian Alyiber;alyibercasaslozano@gmail.com;63012313 ;
LGX488 Mayuli Guizado;Mayuli Guizado;mayulig0513@gmail.com;6754-4961;
LGX355 Rodrigo Rodriguez;Rodrigo Rodriguez;rodrygo19r@gmail.com;67748477;
LGX416 Jason Jeannette;Jason Jeannette;jasonjeannette03@gmail.com;60471758;
LGX465 Wazari Soto;Wazari Soto;Viazarypanama@gmail.com;6043-2030;
LGX423 Gizmo Tech S.A.;Gizmo Tech S.A.;ambararosemena@gmail.com;6671-3344;
LGX100 Yosimar Samaniego;Yosimar Samaniego;Yosimarpty@gmail.com;6565-1598;
LGX388 Jeremy Alvarado;Jeremy Alvarado;jeremyav@msspty.com;60415540;
LGX507 Edgar Arango;Edgar Arango;cocobote26@gmail.com;6987-9710;
LGX100 Amable Saavedra;Saavedra ;plomeriamable@hotmail.com;6571-5352;
LGX512 Ricardo Rico;Ricardo Rico;ricoservice13@yahoo.com;68911622;
LGX461 Kiara Osorio;Kiara Osorio;kiraodeb@hotmail.com;6716-5746;
LGX527 Alan Tejeira;Alan Tejeira;mrgreen507pa@gmail.com;6074-9077 ;
LGX432 Jose Santamaria;Jose Santamaria;jsantamaria533@gmail.com;6618-7304;
LGX519 Carmela Pino;Carmela Pino;ac_pinod@icloud.com;6780-6429 ;
LGX514 Jabri Arauz;Jabri Arauz;jabriara01@gmail.com;67904961 ;
LGX475 Aura Castro de Amador;Aura Castro de Amador;amacas88@gmail.com;68148237;
LGX525 Julian Caro;Julian Caro;Juliancpty@gmail.com;6200-8434;
LGX404 Luis Cuervo;Luis Cuervo;lccot07.lc@gmail.com; 6963 1632 ;
LGX523 Erick Viquez;Erick Viquez;erick.viquez2016@gmail.com;66119293;
LGX521 Yalenis Sanchez;Yalenis Sanchez;yalenis15@icloud.com;68907092;
LGX440 Edwin Muñoz;Edwin Muñoz;Erguinmunoz2020@gimail.com;6973 2389;
LGX469 Juan Zhu;Juan Zhu;juanzhu04@hotmail.com;6536-2499;
LGX383 Judy Warszawczyk;Judy Warszawczyk;judyannw15@gmail.com;6612-9119;
LGX510 Astrid Chirinos;Astrid Chirinos;astrid1211@hotmail.com;6837-5920  ;
LGX215 Eduard Orlando Casas ;Eduard Orlando Casas;eduarcasaslo2@gmail.com;6015-9699;
LGX100 Gilberto Guardia;Gilberto Guardia;gilbertoguardia507@gmail.com;6894-0036;
LGX100 Saul Romero;Saul Romero;fromero560@hotmail.com;6417-7967;
LGX264 Luis Velsco;Luis Velasco;luisvcelta2021@gmail.com;6882-7281;
LGX381 Jenniffer Warszawczyk;Jenniffer Warszawczyk;jenywar@hotmail.com;6674-0043;
LGX100 Jean Carlos Moreno;Jean Carlos Moreno;jeancarlosmoreno41@gmail.com;6305-2169;
LGX121 Luis Gonzalez;Luis Gonzalez;gofarmpma@gmail.com;6678-4310;
LGX100 Hugo Cobos;Hugo Cobos;;6612-8962;
LGX520 María Elena Ureña;Maria Elena Ureña;urena.mari@gmail.com;6613-6060;
LGX409 Antonio Herrera;Antonio Herrera;toninjr18@gmail.com;63208976;
LGX499 Hellen Azcarate;Hellen Azcarate;azcaratehellen@gmail.com;6878-7949;
LGX497 Carolina Alvarez;Carolina Alvarez;;6681-7130;
LGX424 Javier Guevara;Javier Guevara;Blackchapi8@gmail.com;6664-8322;
LGX518 Yoveris Portugal;Yoveris Portugal;Yoveris19@gmail.com;6677-4557;
LGX503 Aaron Gonzalez;Aaron Gonzalez;irra1622@gmail.com;64531072;
LGX500 Lisa Bermudez;Liza Bermudez;LDBG26@GMAIL.COM;62563054;
LGX517 Angel Mitre;Angel Mitre;angelmitre23@gmail.com;6457-1700;
LGX403 Lenissell Hidalgo;Lenissell Hidalgo;Lehidalg12@gmail.com;6704-8611;
LGX377 Karla Ramos;Karla Ramos;Karladeniiser@gmail.com;62837368;
LGX516 Luis Ellis;Luis Ellis;luigiellis@hotmail.com;60903851;
LGX389 Jaime Meneses;Jaime Meneses;jaime.m.meneses@gmail.com;67253704;
LGX100 Gustavo Dominguez;Gustavo Dominguez;;6671-3982;
LGX100 Julianny Tabares;Julianny Tabares;any.tabares@hotmail.com;6965-7889;
LGX491 Raul Moreno;Raul Moreno;raulmoreno50707@gmail.com;6276-4109;
LGX481 Kayra Pachon;Kayra Pachon;Kayra.pachon@gmail.com;66172725;
LGX150 Fernando Pinto;Fernando Pinto;netboxpty@gmail.com;6130-2067;
LGX399 Johana Alvarado;Johana Alvarado;abog.johanaalvarado@gmail.com;6280-9793;
LGX308 Williams Avila;Williams Avila;sametwilliams@gmail.com;507 6146-9728;
LGX384 Jaisell Warszawczyk;Jaisell Warszawczyk;jaisellwr@gmail.com;6674-6505;
LGX162 Delle Giampiero;Delle Giampiero;Giampiero99@yahoo.com;6663-3498;
LGX148 Jose Ramirez;Jose Ramirez;joseramirezserrano@gmail.com;6073-0291;
LGX513 Dazne Gomez;Dazne Gomez;gomezdazne@gmail.com;63476181;
LGX100 Aaron Romero;Aaron Romero;aronazael560@gmail.com;6575-5486;
LGX511 Cicino Delgado;Cicino Delgado;Delgado.04@icloud.com;6383-4237;
LGX396 Omayra Vincent;Omayra Vincent;omayravincent08@gmail.com;6616-0775;
LGX100 Susivel Vargas;Susivel Vargas;Susibellv14@gmail.com;6151-0996;
LGX100 Daniel Arujo;Daniel Arujo;Belkime@hotmail.es;6606-1968;
LGX492 Miguel Zamora;Miguel Zamora;migzam15@gmail.com;67811095;
LGX489 Keisy Arauz;Keisy Arauz;KEISYARAUZ21@GMAIL.COM;60254162;
LGX509 Fernando Rodríguez;Fernando Rodriguez;fernandoarielrodriguezmojica@gmail.com;6729-1460;
LGX100 Luis Barria;Luis Barria;Luisbar74@yahoo.com;;
LGX423 Ambar Arosemena;Ambar Arosemena;ambararosemena@gmail.com;6671-3344;
LGX506 Ali Omais;Ali Omais;Aliomais.1693@gmail.com;6150-8315;
LGX347 Jorge Fisher;Jorge Fisher;fisherjorge7@gmail.com;6612-6023;
LGX 276 Iris Rodriguez;Iris Rodriguez ;Irisjj99@hotmail.com;6615-5280;
LGX100 Virgilio Saavedra;Virgilio Saavedra;transp.victoria@gmail.com;6677-6963;
LGX382 Joanie Warszawczyk;Joanie Warszawczyk;joanie.ward@gmail.com;6781-1999;
LGX505 Yohilan González;Yohilan González;Yohilangt31@gmail.com;;
LGX482 Rolando Riquelme;Rolando Riquelme;rolando19971@hotmail.com;63615425;
LGX255 Gilberto Guardia;Gilberto Guardia;Gilbertoguardia507@gmail.com;6747-9544;
LGX470 Giovani Mahecha;Giovani Mahecha;giovmahu@gmail.com;6295-4158;
LGX466 Jennifer Graham; Jennifer Graham;jannet0031@hotmail.com;6659-5353;
LGX372 Marubenys Pinto;Marubenys Pinto;Marubenysdc@gmail.com;64591333;
LGX501 Amor Rodriguez;Amor Rodriguez;amor02ayshel@gmail.com;64108532;
LGX Send Box Logistic;Send Box Logistic;sales@senboxpty.com;6538-7788;
LGX502 Efrain Bonilla;Efrain Bonilla;efrain@byapa.com;6747-9937;
LGX498 Winfried Mundl;Winfried Mundl;winfried.mundl@gmail.com;49 173 4567894;
LGX493 Luis Gonzalez;Luis Gonzalez;luisesteban-89@outlook.com;65963287;
LGX474 Oscar Landecho;Oscar Landecho;Olandechodeleon@gmail.com;60329227;
LGX100 Carlos Correa;Carlos Correa;carcorrea_08@hotmail.com;;
LGX191 Milagros Lesmany;Milagros Lesmany;milagros.lesmany@gmail.com;6389-9074;
LGX100 Edwin Medina;Edwin Medina;edwin_medina31@hotmail.com;6678-6406;
LGX100 Cesar Saenz;Cesar Saenz;cesar03csz@gmail.com;60018775;
LGX100 Isaac Tarazi ;Isaac Tarazi ;isaactarazi@hotmail.com;6673-1587 ;
LGX467 Ionattan Delgado;Ionattan Delgado;ionattan-delgado@outlook.com;6326-2458;
LGX100 Yazmin Evans;Yazmin Evans;crismeilyserrano@gmail.com;6752-0514;
LGX100 Karina Romero;Karina Romero;karinnaromero16@yahoo.com;6930-5350;
LGX281 Rider Jose;Rider Jose;Riderram91@gmail.com;6073-0291;
LGX100 Dalila Suarez ;Dalila Suarez ;dalila.suarez7@gmail.com;;
LGX478 Jorge Caballos;Jorge Caballos;Jorgepan200074@gmail.com;6117-7099;
LGX477 Yoveris Portugal;Yoveris Portugal;Yoveris19@gmail.com;67664557;
LGX476 Emanuel Martinez;Emanuel Martinez;wolgem1616@gmail.com;68079451;
LGX258 Brenda Carcamo;Brenda Carcamo;b_johana6@hotmail.com;6617-4627;
LGX160 Marcia Herazo;Marcia Herazo;marcia.herazo@gmail.com;6767-2733;
LGX 282 Wilmedis Zambrano;Wilmedis Zambrano;Wilmediszambrano@gmail.com;6219-9297;
LGX430 Andrea Martinez;Andrea Martinez;Andre0-7@hotmail.com;63485595;
LGX413 Oscar Sheu;Oscar Sheu;Sheu077@gmail.com;6017-7155;
LGX100 Isaac Barrios  ;Isaac Barrios;Ibarrios550@hotmail.com;;
LGX269 Yunier Casimiri;Yunier Casimiri;Casimiriyunier@gmail.com;6031-0067;
LGX472 Laura Torrealba;Laura Torrealba;Fc424751@gmail.com;6496-5039;
LGX316 Carlos Luzcando;Carlos Luzcando;Luzcandocarlos2@gmail.com;6115-7420;
LGX468 Silva Ramos;Silva Ramos;Silvialineth2@gmail.com;6906-0170;
LGX439 Oscar Campos;Oscar Campos;sederiaandrea@gmail.com;6040-8430;
LGX428 Sandra Hurtado;Sandra Hurtado;sandra_0622@hotmail.com;6874-4871;
LGX100 Jesus Peraza;Jesus Peraza;jdavidperaza90@gmail.com;6410-4904;
LGX292 Pierina Solorzano;Pierina Solorzano;Pierinasolorzano@gmail.com;61376207;
LGX390 Alden Ñurida;Alden Ñurida;aldenyohanell2011@hotmail.com;62966017;
LGXAP Carlos Rodriguez;Carlos Rodriguez;facturas@airpostpty.com;6270-3946;
LGX459 Billy Parisca;Billy Parisca;billyparisca214@gmail.com;62277347;
LGX351 Gper4mance;Gper4mance;Gper4mance@gmail.com;6727-0151;
LGX418 Rodrigo Alfaro;Rodrigo Alfaro;rodrigo.alfarolm@gmail.com;62518116;
LGX455 Anthony Pile;Anthony Pile;apile0621@gmail.com;6910-4122;
LGX458 David Cisneros;David Cisneros;daviditmk@gmail.com;6583-8477;
LGX457 Ana Garijo;Ana Garijo;Agarijo@amgpanana.com;6673-5144;
LGX207 Ramon Quintero ; Ramon Quintero;rquintero18@gmail.com;;
LGX454 Maria Reyes;Maria Reyes;mary31reyesmedrano@gmail.com;64089517;
LGX453 Ricauter Moreno;Ricauter Moreno;Ricaurtemoreno03@gmail.com;6038-3688;
LGX437 Ana Castillo;Ana Castillo;Anacastillo1399@gmail.com;69869343;
LGX452 Jamar Moore;Jamar Moore;moorejamar0128@outlook.com;6325-9514;
LGX451 Delsy Sanchez;Delsy Sanchez;kttysanchez@gmail.com;66941895;
LGX450 Keisy Alvarez;Keisy Alvarez;keisygomez7557@gmail.com;63567048;
LGX449 Stephanie Alvarez;Stephanie Alvarez;fefaalgo1998@gmail.com;63567096;
LGX448 Josue Villarreta;Josue Villarreta;Josue15vt@gmail.com;6933-0164;
LGX165 Rosanny Pereira;Rosanny Pereira;rosanny.pereyra@gmail.com;6674-0062;
LGX445 Ian Ostrander;Ian Ostrander;ian.ostrander@tetrapak.com;680-56-413;
LGX358 Xavier Vargas;Xavier Vargas;Xavierv128213@gmail.com;62329885;
LGX174 Zabdiel Rodriguez ;Zabdiel Rodriguez;Zabdiel.e.rodriguez.a@gmail.com;6748-7931;
LGX443 Rosmery Sandoval;Rosmery Sandoval;rosmeryjoelianis1906@gmail.com;65900330;
LGX100 Abraham Mendez ;Abraham Mendez;aryensinamhir2117@gmail.com;6101-1801;
LGX438 Josue Mancilla;Josue Mancilla;jdmrcv@gmail.com;66214284;
LGX100 Emilio Juarez;Emilio Juarez;ejuarez@cca-apolo.com.mx;;
LGX433 Yamileth Cardenas;Yamileth Cardenas;Amih121314@gmail.com;64128842;
LGX428 Martina Atencio;Martina Atencio;jessikashley@hotmail.com;63643319;
LGX431 Karolinne Biso;Karolinne Biso;karolinnebiso@gmail.com;61624204;
LGX429 Maria Cortez;Maria Cortez;maria.ecortez28@gmail.com;6399-2010;
LGX100 Isaac Marin ;Isaac Marin;Imarin960@outlook.com;;
LGX417 Marcos Chavez;Marcos Chavez;EmpOttoStore@gmail.com;63896911;
LGX408 Jose Esteves;Jose Esteves;estevesjose1987@gmail.com;61078528;
LGX425 Jaremis Moran;Jaremis Moran;JASIEL3021@GMAIL.COM;6574-1204;
LGX420 Luis Pirela;Luis Pirela;Luis.pirela78@gmail.com;6370 0384;
LGX317 Orlando Brown;Orlando Brown;Obrown@cwpanama.net;63947072;
LGX407 Romel Sanchez;Romel Sanchez;Rommelsan65@gmail.com;6722-1376;
LGX353 Luis Lopez;Luis Lopez;Luislopezunefa01@gmail.com;6320-4930;
LGX244 Lilibeth Castro;Lilibeth Castro;lilibethcastro0@gmail.com;61067254;
LGX139 Isaac Mendoza; Isaac Mendoza;hiulett@gmail.com;6159-8812;
LGX338 Nilka Salinas;Nilka Salinas;Salonday67@gmail.com;+1 (786) 499-5724;
LGX325 Jenny Urrego;Jenny Urrego;jennyurrego712@gmail.com;6115-7420;
LGX100 Harvey Green ;Harvey Green;soldadura507@gmail.com;6484-9128;
LGX100 Waldemar Cordoba;Waldemar Cordoba;wrcc05@hotmail.com;6247-6682;
LGX342 Dostin Gonzalez;Dostin Gonzalez;Andresdos01@gmail.com;6315-7487;
LGX100 Dimex Panama S.A.;Dimex Panama S.A.;compras.panama@grupodimex.com;6581-6033;
LGX322 Tatiana Samaniego ;Tatiana Samaniego ;thaty28@hotmail.com;6325 1615;
LGX271 Yovanis Hernandez;Yovanis Hernandez;yovannia15@gmail.com;6007 - 5738;
LGX100 Milagros Lesmany;Milagros Lesmany;milagros.lemasny@gmail.com;6389-9074;
LGX100 Kenny Gutierrez;Kenny Gutierrez;kennysting15@gmail.com;65156707;
LGX100 Marilyn Miller;Miller;millermarilyn09@gmail.com;68956870;
LGX100 Luis Bravo ;Luis Bravo;loco@gmail.com;65220600 ;
LGX100 Jorge Rueda;Jorge Rueda;jorgerueda2929@gmail.com;6041-8739";

        $rows = explode("\n", $dataRaw);
        foreach ($rows as $row) {
            $cols = explode(';', $row);
            if (count($cols) < 3) continue;

            $fullNameRaw = trim($cols[0]);
            $email = trim($cols[2]);
            $phone = isset($cols[3]) ? trim($cols[3]) : '';

            if (empty($email)) continue;

            $boxParts = explode(' ', $fullNameRaw);
            $boxNumber = $boxParts[0];

            if ($boxNumber === 'LGX' && isset($boxParts[1]) && is_numeric($boxParts[1])) {
                $boxNumber = 'LGX' . $boxParts[1];
                $name = trim(str_replace($boxParts[0] . ' ' . $boxParts[1], '', $fullNameRaw));
            } else {
                $name = trim(str_replace($boxNumber, '', $fullNameRaw));
            }

            if (empty($name)) {
                $name = trim($cols[1]);
            }

            // 1. Create User account
            $user = User::updateOrCreate(
                ['email' => $email],
                [
                    'tenant_id' => $tenant1->id,
                    'name' => $name,
                    'password' => Hash::make('password123'),
                    'role' => 'customer',
                    'email_verified_at' => now(),
                ]
            );

            // 2. Create Customer Profile (Identical Box IDs for all services)
            Customer::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'tenant_id' => $tenant1->id,
                    'box_number' => $boxNumber,
                    'box_number_air' => $boxNumber,
                    'box_number_maritime' => $boxNumber,
                    'phone' => $phone,
                    'balance' => 0,
                    'points' => 0,
                ]
            );
        }
    }
}
