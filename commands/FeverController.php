<?php

/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\FeverPair;
use app\models\ParagraphQueue;
use yii\console\Controller;

class FeverController extends Controller
{
    const FEVER_SAMPLE = '{"id": "Call of Duty: Ghosts", "text": "Call of Duty : Ghosts ( také nazýváno jako CoD : Ghosts ) je videoherní střílečka z pohledu první osoby vydaná společností Activision v roce 2013 . V Japonsku hru distribuovala společnost Square Enix . Hra byla vytvořena herním studiem Infinity Ward za podpory studií Raven Software a Certain Affinity , kteří spolupracovali na hře více hráčů . Další studio Neversoft pomohlo vytvářet herní mód Extinction . Nejdříve hra vyšla na platformy Microsoft Windows , Sony PlayStation 3 , Microsoft Xbox 360 a také pro konzoli Nintendo Wii U , jejíž port byl vytvořen ve studiu Treayrch . Později hra vyšla také na konzole PlayStation 4 a Xbox One a to při jejich vydání na trh .", "lines": "0\tCall of Duty : Ghosts ( také nazýváno jako CoD : Ghosts ) je videoherní střílečka z pohledu první osoby vydaná společností Activision v roce 2013 .\n1\tV Japonsku hru distribuovala společnost Square Enix .\n2\tHra byla vytvořena herním studiem Infinity Ward za podpory studií Raven Software a Certain Affinity , kteří spolupracovali na hře více hráčů .\n3\tDalší studio Neversoft pomohlo vytvářet herní mód Extinction .\n4\tNejdříve hra vyšla na platformy Microsoft Windows , Sony PlayStation 3 , Microsoft Xbox 360 a také pro konzoli Nintendo Wii U , jejíž port byl vytvořen ve studiu Treayrch .\n5\tPozději hra vyšla také na konzole PlayStation 4 a Xbox One a to při jejich vydání na trh ."}
    {"id": "Jaromír Bünter", "text": "Jaromír Bünter ( 3. dubna 1930 Ledvice – 15. října 2015 Praha ) byl československý hokejový obránce .", "lines": "0\tJaromír Bünter ( 3. dubna 1930 Ledvice – 15. října 2015 Praha ) byl československý hokejový obránce ."}
    {"id": "Diecéze annecyjská", "text": "Diecéze Annecy ( lat . `` Diocesis Anneciensis \'\' , franc . `` Diocèse d\'Annecy \'\' ) je francouzská římskokatolická diecéze . Leží na území departementu Horní Savojsko , jehož hranice přesně kopíruje . Sídlo biskupství i katedrála sv. Petra se nachází v Annecy . Diecéze je součástí lyonské církevní provincie . Od 7. května 2001 je diecézním biskupem Mons . Yves Boivineau .", "lines": "0\tDiecéze Annecy ( lat .\n1\t`` Diocesis Anneciensis \'\' , franc .\n2\t`` Diocèse d\'Annecy \'\' ) je francouzská římskokatolická diecéze .\n3\tLeží na území departementu Horní Savojsko , jehož hranice přesně kopíruje .\n4\tSídlo biskupství i katedrála sv. Petra se nachází v Annecy .\n5\tDiecéze je součástí lyonské církevní provincie .\n6\tOd 7. května 2001 je diecézním biskupem Mons .\n7\tYves Boivineau ."}
    {"id": "Pneuservis", "text": "Pneuservis je místo , kde se mění či opravují pneumatiky motorových vozidel a pojízdných strojů . Pneuservis může být součástí autoservisu či může stát samostatně bez doplňkových služeb či pouze služeb spojenými s pneumatikami , jako uskladnění sezonních pneumatik .", "lines": "0\tPneuservis je místo , kde se mění či opravují pneumatiky motorových vozidel a pojízdných strojů .\n1\tPneuservis může být součástí autoservisu či může stát samostatně bez doplňkových služeb či pouze služeb spojenými s pneumatikami , jako uskladnění sezonních pneumatik ."}';


    public function actionImport($max_timestamp = 2147483647)
    {
        $handle = fopen("fever_5pct.jsonl", "r");
        fgets($handle);
        while (($line = fgets($handle)) !== false) {
            $l = json_decode($line);
            $l = new FeverPair($l);
            $l->evidence = json_encode($l->evidence);
            $l->evidence_cs = json_encode($l->evidence_cs);
            $l->save();
        }
    }
}
