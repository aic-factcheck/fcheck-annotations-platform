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
83504998	Borská Pavlína, Bc.
54004524	Hojný Vojtěch
67721735	Koucká Anna
34717123	Kramlová Jana
71622324	Makhinchuk Yuliia
33957333	Pěkná Anna
59472508	Pepř Tadeáš
35168178	Potužník Marek Jiří
59607521	Rieger Matyáš, Bc.
99561858	Šolcová Tereza
77015105	Vincourová Hana
TSV;

        foreach (explode("\n", $tsv) as $line) {
            $l = explode("\t", $line);
            User::generate($l[0], $l[1]);
        }
        return ExitCode::OK;
    }

}
