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
85313177	Benešová Stanislava, Bc.
48854178	Dědečková Kristina
86332812	Dorňáková Tereza, Bc.
13047605	Dvořáček Vojtěch
75351707	Dvořák Vilém, Bc.
51971672	Dvořáková Barbora, Bc.
37456756	Dziuba Daryna, Bc.
17677897	Fujáček Jakub, Bc.
35516642	Haber Josef, Bc.
41487643	Hegedüš Tomáš, Bc.
88704782	Holíková Hana
57836041	Jarolímková Zuzana, Bc.
77574862	Klézl Tomáš, Bc.
94392405	Kloučková Rozálie, Bc.
33797702	Luu Danh Tiep, Bc.
55976794	Malá Markéta, Bc.
58476193	Málek Albert, Bc.
53463847	Martincová Kateřina
79902378	Motyčka Jakub
61598486	Mudrová Nikol, Bc.
83361723	Nechvátal Lukáš, Bc.
64853773	Oravová Tereza
66387363	Penkov Radoslav, Bc.
48347238	Podolka Tadeáš, Bc.
79941444	Samaras Alexandros OUT
57243509	Slivková Nela, Bc.
18676417	Spirit Martin
56385050	Šedinová Michaela
48349445	Šedivý Tomáš, Bc.
71907735	Šimek Jan, Bc.
64658425	Vraná Kateřina, Bc.
99319190	Zítko Tomáš, Bc.
31805190	Zlámal Ondřej, Bc.
TSV;

        foreach (explode("\n", $tsv) as $line) {
            $l = explode("\t", $line);
            User::generate($l[0], $l[1]);
        }
        return ExitCode::OK;
    }

}
