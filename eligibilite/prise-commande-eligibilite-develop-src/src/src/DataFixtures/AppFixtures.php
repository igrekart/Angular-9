<?php

namespace App\DataFixtures;

use App\Entity\Authority;
use App\Entity\Bank;
use App\Entity\Civility;
use App\Entity\Country;
use App\Entity\Identity;
use App\Entity\PaymentChoice;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Common\Persistence\ObjectManager;

class AppFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        // $product = new Product();
        // $manager->persist($product);

        //civilities
        $mr = new Civility();
        $mr->setLabel('Monsieur')
            ->setCode(11);

        $mme = new Civility();
        $mme->setLabel('Madame')
            ->setCode(12);

        $mrs = new Civility();
        $mrs->setLabel('Mademoiselle')
            ->setCode(13);

        $manager->persist($mr);
        $manager->persist($mme);
        $manager->persist($mrs);


        //Identity
        $identities = [
            "Attestation d'identité", "Autre pièce d'identité",
            "Carte consulaire ou titre de séjour",
            "Carte de réfugié",
            "Carte Nationale d'Identité", "Carte professionnelle", "Carte scolaire ou d'étudiant",
            "Passeport",
            "Permis de conduire",
        ];

        foreach ($identities as $key => $identity) {
            $toSave = new Identity();
            $toSave->setLabel($identity)
                ->setCode($key + 1);

            $manager->persist($toSave);
        }


        //pays
        $countries = [
            "AFGHANISTAN",
            "AFRIQUE DU SUD",
            "ALASKA",
            "ALBANIE",
            "ALGERIE",
            "ALLEMAGNE",
            "ANDORRE",
            "ANGOLA",
            "ANGUILLA",
            "ANTIGUA",
            "ANTILLES NEERLANDAISES",
            "ARABIE SAOUDITE",
            "ARGENTINE",
            "ARMENIE",
            "ARUBA",
            "ASCENSION",
            "AUSTRALIE",
            "AUTRICHE",
            "AZERBAIDJAN",
            "BAHAMAS",
            "BAHREIN",
            "BANGLADESH",
            "BARBADE",
            "BELARUS",
            "BELGIQUE",
            "BELIZE",
            "BENIN",
            "BERMUDES",
            "BHOUTAN",
            "BOLIVIE",
            "BOLIVIE",
            "BOPHUTNATSWANA",
            "BOSNIE-HERZEGOVINE",
            "BOTSWANA",
            "BOUTHAN",
            "BRESIL",
            "BRUNEI",
            "BULGARIE",
            "BURKINA FASO",
            "BURUNDI",
            "CAIMAN (ILES)",
            "CAMBODGE",
            "CAMEROUN",
            "CANADA",
            "CAP-VERT",
            "CAYMAN (ILES)",
            "CENTRAFRIQUE",
            "CHILI",
            "CHINE",
            "CHRISTMAS",
            "CHRISTMAS",
            "CHYPRE",
            "COCOS",
            "COLOMBIE",
            "COMORES",
            "CONGO",
            "COOK (ILES)",
            "COREE DU NORD",
            "COREE DU SUD",
            "COSTA RICA",
            "COTE D'IVOIRE",
            "CROATIE",
            "CUBA",
            "DANEMARK",
            "DIEGO-GARCIA",
            "DJIBOUTI",
            "DOMINIQUE",
            "EGYPTE",
            "EL SALVADOR",
            "EMIRATS ARABES UNIS",
            "EQUATEUR",
            "ERYTHREE",
            "ESPAGNE",
            "ESTONIE",
            "ETATS-UNIS",
            "ETHIOPIE",
            "FALKLAND (ILES)",
            "FIDJI (ILES)",
            "FINLANDE",
            "FRANCE",
            "GABON",
            "GAMBIE",
            "GEORGIE",
            "GHANA",
            "GRECE",
            "GRENADE",
            "GROENLAND",
            "GUAM",
            "GUATEMALA",
            "GUINEE",
            "GUINEE BISSAU",
            "GUINEE EQUATORIALE",
            "GUYANA HAITI",
            "HONDURAS",
            "HONG KONG",
            "HONGRIE",
            "ILES FEROE",
            "ILES VIERGES",
            "INDE",
            "INDONESIE",
            "IRAN",
            "IRAQ",
            "IRLANDE",
            "ISLANDE",
            "ISRAEL",
            "ITALIE",
            "JAMAIQUE",
            "JAPON",
            "JORDANIE",
            "KAZAKHSTAN",
            "KENYA",
            "KIRGHIZISTAN",
            "KIRIBATI",
            "KOWEIT",
            "LAOS",
            "LESOTHO",
            "LETTONIE",
            "LIBAN",
            "LIBERIA",
            "LIBYE",
            "LITUANIE",
            "LUXEMBOURG",
            "MACAO",
            "MACEDOINE",
            "MADAGASCAR",
            "MALAISIE",
            "MALAWI",
            "MALDIVES (ILES)",
            "MALI",
            "MALTE",
            "MAROC",
            "MARSHALL (ILES)",
            "MAURICE",
            "MAURITANIE",
            "MEXIQUE",
            "MICRONESIE",
            "MOLDAVIE",
            "MONACO",
            "MONGOLIE",
            "MONTSERRAT",
            "MOZAMBIQUE",
            "NAMIBIE",
            "NAURU",
            "NEPAL",
            "NICARAGUA",
            "NIGER",
            "NIGERIA",
            "NORFOLK",
            "NORFOLK",
            "NORVEGE",
            "NOUVELLE CALEDONIE",
            "NOUVELLE-ZELANDE",
            "OMAN",
            "OUGANDA",
            "OUZBEKISTAN",
            "PAKISTAN",
            "PALAU",
            "PANAMA",
            "PAPOUASIE NOUVELLE-GUINEE",
            "PARAGUAY",
            "PAYS-BAS",
            "PEROU",
            "PHILIPPINES",
            "POLOGNE",
            "PORTO RICO PORTUGAL",
            "PORTUGAL",
            "QATAR",
            "REPUBLIQUE DOMINICAINE",
            "REPUBLIQUE SLOVAQUE",
            "REPUBLIQUE TCHEQUE",
            "ROUMANIE",
            "ROYAUME-UNI",
            "RUSSIE",
            "RWANDA",
            "SAINT-CHRISTOPHE",
            "SAINTE-HELENE",
            "SAINTE-LUCIE",
            "SAINT-MARIN",
            "SAINT-VINCENT",
            "SAIPAN",
            "SALOMON (ILES)",
            "SAMOA OCCIDENTAL",
            "SAO TOME ET PRINCIPE",
            "SENEGAL",
            "SEYCHELLES",
            "SIERRA LEONE",
            "SINGAPOUR",
            "SLOVAQUIE",
            "SLOVENIE",
            "SOMALIE",
            "SOUDAN",
            "SRI LANKA",
            "SUEDE",
            "SUISSE",
            "SURINAM",
            "SWAZILAND",
            "SYRIE",
            "TADJIKISTAN",
            "TAIWAN",
            "TANZANIE",
            "TATARSTAN",
            "TCHAD",
            "THAILANDE",
            "TOGO",
            "TOKELAU",
            "TONGA",
            "TRANSKEI",
            "TRINITE ET TOBAGO",
            "TUNISIE",
            "TURCKS ET CAICOS",
            "TURKMENISTAN",
            "TURQUES ET CAIQUES (ILES)",
            "TURQUIE",
            "TUVALU",
            "UKRAINE",
            "URUGUAY",
            "VANUATU",
            "VATICAN",
            "VENEZUELA",
            "VIETNAM",
            "YEMEN ADEN",
            "YEMEN DU NORD (REP. ARABE)",
            "YEMEN DU SUD",
            "YOUGOSLAVIE",
            "ZAIRE",
            "ZAMBIE",
            "ZANZIBAR",
            "ZIMBABWE"
        ];

        foreach ($countries as $key => $country) {
            $toSave = new Country();
            $toSave->setLabel($country)
                ->setCode($key + 1);

            $manager->persist($toSave);
        }


        //autorites
        $authorities = [
            "Administration",
            "Assemblée Générale Actionnaires",
            "Centre Identification Sécuritaire",
            "Direction Générale des Impôts",
            "Min Affaires Etrangères",
            "Min Défense Gendarmerie Armée",
            "Min Intérieur",
            "Min Sécurité Police",
            "Tribunal",
            "ONI"
        ];

        foreach ($authorities as $key => $authority) {
            $toSave = new Authority();
            $toSave->setLabel($authority)
                ->setCode($key+1);

            $manager->persist($toSave);
        }

        //modes de payement
        $paymentsChoice = ['Especes', 'Mobile Money', 'Cheque'];

        foreach ($paymentsChoice as $key => $choice) {
            $paymentChoice = new PaymentChoice();
            $paymentChoice->setLabel($choice)
                ->setCode($key);
            $manager->persist($paymentChoice);
        }

        //banks

        $banks = [
            ["code" => 0,"label" => "BIAO"],
            ["code" => 1,"label" => "BICI-CI"],
            ["code" => 2,"label" => "SGBCI"],
            ["code" => 3,"label" => "SIB"],
            ["code" => 4,"label" => "CECP"],
            ["code" => 5,"label" => "BCEAO"],
            ["code" => 6,"label" => "COFIPA-INVEST"],
            ["code" => 49,"label" => "CITIBANK"],
            ["code" => 50,"label" => "COBACI/BARCLAYS BANK"],
            ["code" => 51,"label" => "BANQUE ATLANTIQUE"],
            ["code" => 52,"label" => "BHCI"],
            ["code" => 53,"label" => "BANK OF AFRICA"],
            ["code" => 54,"label" => "CAA"],
            ["code" => 55,"label" => "ECOBANK"],
            ["code" => 56,"label" => "BANQUE PARIBAS"],
            ["code" => 57,"label" => "BANQUE DU TRESOR"],
            ["code" => 58,"label" => "COFINCI"],
            ["code" => 59,"label" => "STANDARD CHARTERED"],
            ["code" => 60,"label" => "BANAFRIQUE"],
            ["code" => 61,"label" => "ACCESS BANK ex OMNIF"],
            ["code" => 62,"label" => "VERSUS BANQUE"],
            ["code" => 63,"label" => "Banque Finan Agri"],
            ["code" => 64,"label" => "BANQUE NATION.INVEST"],
            ["code" => 65,"label" => "CECP"],
            ["code" => 66,"label" => "Bque Regionale S.CI"],
            ["code" => 67,"label" => "BRIDGE BANK"],
            ["code" => 68,"label" => "COBACI"],
            ["code" => 69,"label" => "UBA"],
            ["code" => 70,"label" => "CITIBANK"],
            ["code" => 71,"label" => "BQUE SAELO-SAHERIENE"],
            ["code" => 72,"label" => "DIAMOND BANK"],
            ["code" => 73,"label" => "BANK GABONNAISE F.I."],
            ["code" => 74,"label" => "GUARANTY TRUST BANK"],
            ["code" => 97,"label" => "Recette des Impôts"],
            ["code" => 98,"label" => "BANQFICT.Reg.Enc.TVA"],
            ["code" => 99,"label" => "X - BANQUE INCONNUE"]
        ];

        foreach ($banks as $bank) {
            $toSave = new Bank();
            $toSave->setLabel($bank['label'])
                ->setCode($bank['code']);

            $manager->persist($toSave);
        }

        $manager->flush();
    }
}
