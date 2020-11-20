<?php

namespace app\models;

use Yii;
use yii\base\Security;
use yii\behaviors\TimestampBehavior;
use yii\db\ActiveRecord;
use yii\web\IdentityInterface;

class User extends ActiveRecord implements IdentityInterface
{
    const ANIMAL_SPECIES = [' americký', ' oranžovokřídlý', ' pomoučený', ' obrovský', ' koňská', ' arakanga', ' hyacintový', ' malý', ' rudobřichý', ' vojenský', ' řasnatý', ' zlatolící', ' dvojvousá', ' prelát', ' Desjardinův', ' pestrý', ' tominský', ' žlutý', ' horský', ' běločelý', ' bělolící', ' obojková', ' bělokrký', ' černý', ' jihoamerický', ' sedlatý', ' drobnoskvrnný', ' horský', ' novoguinejský', ' krokodýlovitá', ' indický', ' nosorožčí', ' hnědá', ' hnědý', ' madagaskarská', ' australský', ' indický', ' stříbrný', ' velkoploutvý', ' písař', ' pospolitý', ' tibetská', ' kuří', ' vdovka', ' stračí', ' skvrnitá', ' guayanský', ' vlnatý', ' bělolící', ' rudý', ' šedokřídlý', ' Antigonin', ' černokrký', ' královský', ' laločnatý', ' mandžuský', ' panenský', ' dravá', ' přilbový', ' hnědý', ' africký', ' očkatý', ' Kleinova', ' rudokrký', ' africký', ' růžový', ' andský', ' tříbarvá', ' veverovitý', ' domácí', ' Gecarcinus', ' bělokrký', ' divoký', ' obecný', ' velký', ' východní', ' černá', ' černokrká', ' hřivnatý', ' zelený', ' kata', ' dvouprstý', ' konžský', ' menší', ' třásnitá', ' pyskatý', ' skvrnitý', ' velký', ' čtyřprstý', ' velký', ' Darwinův', ' kea', ' africký', ' bílý', ' indomalajský', ' tuponosý jižní', ' korunkatý', ' jihoamerický', ' štíhlá', ' domácí - valašská', ' somálská', ' červená', ' šedohlavý', ' skvělý', ' korunkatý', ' australský', ' bílý', ' skvrnozobý', ' supí', ' šedozelený', ' bělokřídlá', ' ostruhatá', ' malý', ' růžový', ' malý', ' trnonoš', ' barvířská', ' batiková', ' pruhovaná', ' strašná', ' savanové', ' dvouprstý', ' tečkovaný', ' rozpůlený', ' kururu', ' včelí', ' inka', ' Haddonova', ' černozobá', ' ománská', ' modrá', ' africký', ' nádherný', ' rudozobý', ' bělohlavý', ' himálajský', ' hnědý', ' chocholatý', ' kapucín', ' mrchožravý', ' Rüppellův', ' obecný', ' pestrý', ' tmavohřbetý', ' čabrakový', ' jihoamerický', ' opačný', ' jednovousá', ' žlutozobý', ' agami', ' Humboldtův', ' bělolící', ' bělobřichý', ' žlutozobý', ' ussurijský', ' velký', ' dvouhrbý', ' šedobřichá', ' červenohlavý', ' červenohřbetý', ' zelený', ' žlutohřbetý', ' abok', ' člunozobý', ' nádherná', ' černoskvrnný', ' hnědoprsý', ' ploskohlavá', ' paví', ' obrovská', ' bělavý', ' stepní', ' zakrslý', ' africký', ' bělovlasatý', ' havraní', ' hrubozobý', ' hvízdavý', ' kaferský', ' křiklavý', ' mohutná', ' ostruhatá', ' pardálí', ' podlouhlá', ' pralesní', ' Rothschildova',];
    const ANIMAL_NAMES = ['Aligátor', 'Amazoňan', 'Anolis', 'Antilopa', 'Ara', 'Arassari', 'Arowana', 'Bažant', 'Bodlok', 'Bongo', 'Buvolec', 'Cichlidy', 'Čája', 'Čáp', 'Čolek', 'Dingo', 'Dracéna', 'Dvojzoborožec', 'Dželada', 'Emu', 'Felzuma', 'Flétnák', 'Gaur', 'Gibon', 'Glyptoper', 'Hadilov', 'Hoko', 'Husa', 'Husice', 'Husička', 'Husovec', 'Hyena', 'Hypostomus', 'Chápan', 'Chvostan', 'Ibis', 'Ibis', 'Jeřáb', 'Kajmanka', 'Kapybara', 'Kasuár', 'Kivi', 'Kladivouš', 'Klaun', 'Klipka', 'Klokan', 'Kolpík', 'Kolpík', 'Kondor', 'Kotinga', 'Kotul', 'Koza', 'Krab', 'Krkavec', 'Krocan', 'Kuandu', 'Kudu', 'Kuňka', 'Labuť', 'Labuť', 'Lachtan', 'Leguán', 'Lemur', 'Lenochod', 'Lev', 'Marabu', 'Matamata', 'Medvěd', 'Mlok', 'Morčák', 'Mravenečník', 'Mravenečník', 'Nandu', 'Nestor', 'Nesyt', 'Nesyt', 'Nesyt', 'Nosorožec', 'Orel', 'Ostnák', 'Ostralka', 'Ovce', 'Ovce', 'Panda', 'Papoušek', 'Parmovec', 'Páv', 'Pelikán', 'Pelikán', 'Pelikán', 'Perlička', 'Pilníkotrn', 'Pižmovka', 'Pižmovka', 'Plameňák', 'Plameňák', 'Polák', 'Pomčík', 'Pralesnička', 'Pralesnička', 'Pralesnička', 'Pralesnička', 'Prase', 'Pštros', 'Puštík', 'Pyskoun', 'Ropucha', 'Rosnička', 'Rybák', 'Sasanka', 'Seriema', 'Siba', 'Slípka', 'Slon', 'Slunatec', 'Snovač', 'Sup', 'Surikata', 'Sýček', 'Tamarín', 'Tamarín', 'Tapír', 'Tapír', 'Tenkozobec', 'Tereka', 'Toko', 'Trubač', 'Tučňák', 'Tukan', 'Turako', 'Turako', 'Tygr', 'Ústřičník', 'Velbloud', 'Veverka', 'Vikuňa', 'Vlhovec', 'Voduška', 'Volavčík', 'Volavka', 'Vousák', 'Vousivka', 'Vrubozubec', 'Vydra', 'Výr', 'Zebra', 'Zebu', 'Zejozob', 'Zoborožec', 'Želva', 'Žirafa',];

    /**
     * {@inheritdoc}
     */
    public static function tableName()
    {
        return 'user';
    }

    public static function generate($name = null, $note = '')
    {
        $security = new Security();
        $user = new User(['auth_key' => $security->generateRandomString(), 'password' => $security->generateRandomString()]);
        $user->username = $name;
        $user->note = $note;
        $user->save();
        return $user;
    }

    /**
     * @inheritdoc
     */
    public static function findIdentity($id)
    {
        return self::findOne($id);
    }

    /**
     * @inheritdoc
     */
    public static function findIdentityByAccessToken($token, $type = null)
    {
        return null;
    }

    public function login()
    {
        return Yii::$app->user->login($this, 3600 * 24 * 30);
    }

    public function generateUniqueName()
    {
        do {
            $this->username = self::ANIMAL_NAMES[array_rand(self::ANIMAL_NAMES)] . self::ANIMAL_SPECIES[array_rand(self::ANIMAL_SPECIES)];
        } while (self::findByUsername($this->username) != null);
    }

    /**
     * Finds user by username
     *
     * @param string $username
     * @return static|null
     */
    public static function findByUsername($username)
    {
        return self::findOne(['username' => $username]);
    }

    public function behaviors()
    {
        return [TimestampBehavior::class,];
    }

    /**
     * {@inheritdoc}
     */
    public function rules()
    {
        return [
            [['username', 'password'], 'required'],
            [['username', 'password', 'note'], 'string', 'max' => 64],
            [['username'], 'unique'],
            [['password'], 'string', 'min' => 6],
            [['auth_key'], 'string', 'max' => 32],
        ];
    }

    /**
     * {@inheritdoc}
     */
    public function attributeLabels()
    {
        return [
            'id' => Yii::t('app', 'ID'),
            'username' => Yii::t('app', 'Uživatelské jméno'),
            'password' => Yii::t('app', 'Password'),
            'auth_key' => Yii::t('app', 'Auth Key'),
            'created_at' => Yii::t('app', 'Created At'),
            'updated_at' => Yii::t('app', 'Updated At'),
        ];
    }

    public function beforeSave($insert)
    {
        if ($this->isAttributeChanged('password')) {
            $this->auth_key = Yii::$app->security->generateRandomString();
            $this->password = Yii::$app->getSecurity()->generatePasswordHash($this->password);
        }
        return parent::beforeSave($insert);
    }

    /**
     * {@inheritdoc}
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * {@inheritdoc}
     */
    public function getAuthKey()
    {
        return $this->auth_key;
    }

    /**
     * {@inheritdoc}
     */
    public function validateAuthKey($authKey)
    {
        return $this->auth_key === $authKey;
    }

    /**
     * Validates password
     *
     * @param string $password password to validate
     * @return bool if password provided is valid for current user
     */
    public function validatePassword($password)
    {
        return Yii::$app->getSecurity()->validatePassword($password, $this->password);
    }
}
