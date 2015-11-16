<?php
/**
 * @category    Fishpig
 * @package    Fishpig_AttributeSplashPro
 * @license      http://fishpig.co.uk/license.txt
 * @author       Ben Tideswell <ben@fishpig.co.uk>
 */


$this->startSetup();

$this->run("

		CREATE TABLE IF NOT EXISTS {$this->getTable('country_ue')} (
			`stato` varchar(5) NOT NULL,
			PRIMARY KEY (`stato`)
		) ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella paesi';

		INSERT INTO {$this->getTable('country_ue')} (`stato`) VALUES
        ('AT'),
        ('BE'),
        ('BG'),
        ('CY'),
        ('HR'),
        ('DK'),
        ('EE'),
        ('FI'),
        ('FR'),
        ('DE'),
        ('GR'),
        ('IE'),
        ('LV'),
        ('LT'),
        ('LU'),
        ('MT'),
        ('NL'),
        ('PL'),
        ('PT'),
        ('GB'),
        ('CZ'),
        ('RO'),
        ('SK'),
        ('SI'),
        ('ES'),
        ('SE'),
        ('HU');

		CREATE TABLE IF NOT EXISTS {$this->getTable('spedizioni')} (
			`Paese` varchar(3) NOT NULL,
			`Regione_stato` varchar(100) NOT NULL,
			`Zip_codicepostale` varchar(100) NOT NULL,
			`Peso` double NOT NULL,
			`Prezzo` double NOT NULL,
			PRIMARY KEY(`Paese`)
		)  ENGINE=InnoDB DEFAULT CHARSET=utf8 COMMENT='Tabella costi di spedizione per paese';


		INSERT INTO {$this->getTable('spedizioni')} (`Paese`, `Regione_stato`, `Zip_codicepostale`, `Peso`, `Prezzo`) VALUES
        ('*', '*', '*', 0, 50),
        ('AUT', '*', '*', 0, 12),
        ('AZE', '*', '*', 0, 50),
        ('BEL', '*', '*', 0, 12),
        ('BGR', '*', '*', 0, 15),
        ('BRA', '*', '*', 0, 36),
        ('CAN', '*', '*', 0, 40),
        ('CHE', '*', '*', 0, 15),
        ('CYP', '*', '*', 0, 25),
        ('CZE', '*', '*', 0, 15),
        ('DEU', '*', '*', 0, 12),
        ('DNK', '*', '*', 0, 24),
        ('EGY', '*', '*', 0, 40),
        ('ESP', '*', '*', 0, 12),
        ('EST', '*', '*', 0, 20),
        ('FIN', '*', '*', 0, 16),
        ('FRA', '*', '*', 0, 12),
        ('GBR', '*', '*', 0, 12),
        ('GEO', '*', '*', 0, 40),
        ('GRC', '*', '*', 0, 16),
        ('HKG', '*', '*', 0, 25),
        ('HUN', '*', '*', 0, 15),
        ('IDN', '*', '*', 0, 30),
        ('IRL', '*', '*', 0, 16),
        ('ISR', '*', '*', 0, 40),
        ('ITA', '*', '*', 0, 7),
        ('KAZ', '*', '*', 0, 50),
        ('LIE', '*', '*', 0, 12),
        ('LTU', '*', '*', 0, 25),
        ('LUX', '*', '*', 0, 14),
        ('LVA', '*', '*', 0, 25),
        ('MDA', '*', '*', 0, 20),
        ('MEX', '*', '*', 0, 40),
        ('MLT', '*', '*', 0, 25),
        ('MNE', '*', '*', 0, 25),
        ('NLD', '*', '*', 0, 13),
        ('NOR', '*', '*', 0, 15),
        ('PAK', '*', '*', 0, 30),
        ('PHL', '*', '*', 0, 30),
        ('POL', '*', '*', 0, 15),
        ('PRT', '*', '*', 0, 15),
        ('ROU', '*', '*', 0, 15),
        ('RUS', '*', '*', 0, 35),
        ('SAU', '*', '*', 0, 50),
        ('SGP', '*', '*', 0, 30),
        ('SVK', '*', '*', 0, 15),
        ('SVN', '*', '*', 0, 12),
        ('SWE', '*', '*', 0, 16),
        ('THA', '*', '*', 0, 30),
        ('TUR', '*', '*', 0, 15),
        ('TWN', '*', '*', 0, 20),
        ('UKR', '*', '*', 0, 18),
        ('USA', '*', '*', 0, 30);

	");

$this->endSetup();
