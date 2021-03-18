<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\User;
use yii\console\Controller;
use yii\console\ExitCode;

/**
 * This command echoes the first argument that you have entered.
 *
 * This command is provided as an example for you to learn how to create console commands.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @since 2.0
 */
class UserController extends Controller
{
    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionIndex($message = 'hello world', $message2 = 'hello world')
    {
        echo $message . "\n";
        echo $message2 . "\n";

        return ExitCode::OK;
    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionMake($name, $note = '')
    {
        User::generate($name, $note);
        return ExitCode::OK;
    }

    /**
     * This command echoes what you have entered as the message.
     * @param string $message the message to be echoed.
     * @return int Exit code
     */
    public function actionImport()
    {
        $tsv = <<<TSV
32975305	Adámek Matyáš
45822531	Balcárková Eliška
60001074	Bočková Jana
49551263	Černík Bartoloměj
51652075	Doubravová Zuzana
52933130	Fantyš Petr
31430294	Foglová Adéla
17072217	Gastová Tereza
54004524	Hojný Vojtěch
38318690	Holubová Jitka
31776024	Hron Marek
82427524	Kaňka Josef
80425231	Karban Filip
83584611	Klečka Jiří
24486565	Kratochvílová Vanda
39278191	Křivánek Adam
54187918	Kuchařová Anna
88897345	Liška Lukáš Josef
13922940	Mach Ondřej
14522351	Machová Anna, Mgr.
14609814	Malá Helena
95194049	Mathauser Petr
37382890	Nováková Klára
52147778	Pančochářová Natálie
29033870	Panovská Alena
59472508	Pepř Tadeáš
35168178	Potužník Marek Jiří
87564626	Schubertová Karolína, Bc.
11323004	Spálenská Alena
16886578	Stein Dominik
65366023	Sůsa Richard
60762270	Svoboda Štěpán
11802550	Šimek Pavel
41206586	Tománek Dominik
58511707	Vokřál Jiří
70024969	Weidenthaler Adam
51860231	Zavřel Marek
39406744	Barták Lukáš
64044886	Blažková Karolína
50228080	Czyž Josef
79252936	Černá Adéla
51622934	Harciníková Alena
85990483	Hodina Stanislav
10179060	Horáková Adéla
82895495	Hübsch Dan
97399177	Levíčková Žaneta
75588160	Louženský Jan
52938185	Plešková Markéta
52591686	Portešová Anna
14254729	Slavíková Kristýna
10196519	Sýkorová Sandra
11588622	Šafová Julie
69183418	Šimečková Karolína
54398576	Zajíc František
TSV;

        foreach (explode("\n", $tsv) as $line) {
            $l = explode("\t", $line);
            User::generate($l[0], $l[1]);
        }
        return ExitCode::OK;
    }

}
