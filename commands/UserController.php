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
    public function actionMake($email, $password)
    {
        echo (new User(["username" => $email, "password" => $password]))->save();
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
65211133	Andree Sabina, Bc.
24409145	Barták Adam, BcA.
72280124	Borovský Jakub, Bc.
49414796	Bubeníček Michal	
22808615	Daněčková Aneta	
84647570	Daňová Soňa	
56779394	Dobrá Jana	
86332812	Dorňáková Tereza, Bc.
69765057	Dvořáček Ondřej, Bc.
13047605	Dvořáček Vojtěch	
37456756	Dziuba Daryna, Bc.
16512580	Felcanová Tereza, Bc.
87034332	Fivebrová Tereza, Bc.
34218271	Folwarczná Aneta	
17677897	Fujáček Jakub, Bc.
32144012	Gruberová Nicole, Bc.
35516642	Haber Josef, Bc.
59632235	Hamrová Alžběta, Bc.
40750783	Haugwitz Daniel, Bc.
48451713	Horová Kateřina, Bc.
90783577	Ivasková Jarmila	
57836041	Jarolímková Zuzana, Bc.
68887154	Jelínková Kristýna, Bc.
86274731	Jírová Karolína, Bc.
14983099	Kazda Tomáš, Bc.
32515277	Kharisova Kamilla, Bc.
61062651	Kilberger Adam, Bc.
94392405	Kloučková Rozálie, Bc.
89013491	Koubek Michal, Bc.
29984961	Krawiecová Nela	
35273688	Křovák Jan, Bc.
78116553	Kudrnová Anna, Bc.
94965035	Kutnarová Kristýna, Bc.
25178429	Kytková Barbara, Bc.
44743503	Mašek Filip, Bc.
32212790	Matějková Kristýna, Bc.
61193271	Michálek Matěj, Bc.
33401265	Motyčková Kateřina, Bc.
99054040	Müllerová Eva, Bc.
83361723	Nechvátal Lukáš, Bc.
70331139	Nováková Tereza, DiS.
64853773	Oravová Tereza	
71727098	Petruková Anna-Marie
49661043	Počtová Tereza, Bc.
10364463	Podzimková Pavlína, Bc.
23721430	Pružinová Kristýna, Bc.
59607521	Rieger Matyáš, Bc.
15036015	Sedloňová Nikola, DiS.
48210638	Schönová Kristýna, Bc.
67299722	Šanda Štěpán, Bc.
23467222	Šedivá Tereza, Bc.
71907735	Šimek Jan, Bc.
62690773	Šimsová Kristýna, Bc.
29836470	Štěrbová Adéla, Bc.
27172413	Tinková Alexandra, Bc.
15455447	Tomeš Michal	
23242481	Trojanová Jitka	
76124534	Trsek Karel, Bc.
93736364	Ullsperger Adam, Bc.
45523126	Verem Anja, Bc.
83240311	Vogl Tomáš, Bc.
80646039	Vojkovský Tomáš	
78688033	Vokatá Lenka
21747460	Zangová Clara, Bc.
31805190	Zlámal Ondřej, Bc.
TSV;

        foreach (explode("\n", $tsv) as $line) {
            $l = explode("\t", $line);
            User::generate($l[0], $l[1]);
        }
        return ExitCode::OK;
    }

}
