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
45822531	Balcárková Eliška, Bc.
67395497	Bartlová Natálie, Bc.
13579240	Dolenská Klára
75973669	Hávová Kristýna
89497302	Holková Vendula
45665851	Hruška Ondřej
62583311	Jeřábek Jan Lucián, Bc.
49151449	Ježková Adéla, Bc.
34898420	Libayová Barbora
88897345	Liška Lukáš Josef, Bc.
96996727	Losleben Lukáš
86601253	Lošková Natálie, Bc.
70335289	Luzar Petr
99159444	Mazúchová Sára, Bc.
70831809	Navrátilová Barbora, Bc.
55076503	Novotná Barbora, Bc.
63362021	Pacovský Tomáš, Bc.
52147778	Pančochářová Natálie, Bc.
61206665	Popková Dominika, Bc.
98722331	Řepková Andrea, Bc.
60762270	Svoboda Štěpán, Bc.
11802550	Šimek Pavel
77015105	Vincourová Hana, Bc.
41693770	Zimermanová Karolína, Bc.
45822531	Balcárková Eliška, Bc.
50408576	Bartoš Pavel, Bc.
98445011	Bečková Kateřina
60001074	Bočková Jana, Bc.
59920734	Bohunická Klára, Bc.
49551263	Černík Bartoloměj, Bc.
29920401	Černý Ondřej, Bc.
35977263	Dlouhý Jan, Bc.
51971672	Dvořáková Barbora, Bc.
98910743	Eder Ivana, Bc.
67057231	Faltys Marcel, Bc.
38850659	Fedičová Natálie, Bc.
81580478	Gleichová Tereza, Bc.
11282223	Grestyová Natália Jane
75973669	Hávová Kristýna
59943811	Hobzová Ilona, Bc.
81064892	Hodková Kateřina, Bc.
89497302	Holková Vendula
58310273	Hrdlička Jan, Bc.
49151449	Ježková Adéla, Bc.
72289095	Ježková Kateřina, Bc.
58146831	Jindrová Tereza, Bc.
90143841	Jonáš Jakub, Bc.
96535822	Jurková Anna
82427524	Kaňka Josef, Bc.
80425231	Karban Filip, Bc.
53034261	Kašpárková Lucie
31832338	Kobrlová Eva, Bc.
39601652	Kolář Adam
50860291	Koryntová Eva, Bc.
53810349	Kotvalová Lucie, Bc.
86896352	Kružíková Kateřina, Bc.
61331071	Kubant Vít
54187918	Kuchařová Anna, Bc.
88897345	Liška Lukáš Josef, Bc.
96996727	Losleben Lukáš
86601253	Lošková Natálie, Bc.
57763124	Lusková Martina
24512720	Maděrová Anna, Bc.
13922940	Mach Ondřej, Bc.
55976794	Malá Markéta, Bc.
47178673	Marková Tanja, Bc.
77951826	Martínková Veronika, Bc.
14258450	Maudr Viktor, Bc.
67612203	Mazáč Jan, Bc.
99159444	Mazúchová Sára, Bc.
58978331	Mikolandová Anna, Bc.
72432220	Müller Matyáš, Bc.
92660137	Němcová Karolína, Bc.
37382890	Nováková Klára, Bc.
55076503	Novotná Barbora, Bc.
63362021	Pacovský Tomáš, Bc.
52147778	Pančochářová Natálie, Bc.
48347238	Podolka Tadeáš, Bc.
51678011	Reisigová Anna, Bc.
85111005	Ryšánek Adam, Bc.
98722331	Řepková Andrea, Bc.
27426764	Sabadosh Anastasiya, Bc.
92611916	Sivoková Kateřina
39826860	Skuciusová Karolína
57243509	Slivková Nela, Bc.
11535000	Smržová Aneta, Bc.
94366250	Součková Barbora, Bc.
18361553	Svoboda Ondřej, Bc.
60762270	Svoboda Štěpán, Bc.
43480600	Svobodová Vilma, Bc.
36941375	Šimůnková Natálie, Bc.
28938258	Šponerová Adéla, Bc.
31451564	Štokrová Aneta, Bc.
26042184	Večeřová Barbora
14469066	Viktorinová Anna
77015105	Vincourová Hana, Bc.
58511707	Vokřál Jiří, Bc.
70024969	Weidenthaler Adam, Bc.
41693770	Zimermanová Karolína, Bc.
99319190	Zítko Tomáš, Bc.
50893328	Žáková Kristýna
TSV;
        $inserted = [];
        foreach (explode("\n", $tsv) as $line) {
            $l = explode("\t", $line);
            if (array_key_exists($l[0], $inserted)) {
                $inserted[$l[0]]->quota_coef=2;
                $inserted[$l[0]]->save();
            } else {
                if (($user = User::find()->andWhere(['username' => $l[0]])->one()) != null) {
                    $user->username = 'LS2021_' . $user->username;
                    $user->save();
                }
                $inserted[$l[0]] = User::generate($l[0], $l[1]);
            }
        }
        return ExitCode::OK;
    }

}
