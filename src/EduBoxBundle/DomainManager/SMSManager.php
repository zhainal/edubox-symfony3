<?php


namespace EduBoxBundle\DomainManager;


use EduBoxBundle\Entity\User;
use Zelenin\SmsRu\Api;
use Zelenin\SmsRu\Auth\ApiIdAuth;
use Zelenin\SmsRu\Entity\Sms;

class SMSManager
{
    private $settingManager;

    public function __construct(SettingManager $settingManager)
    {
        $this->settingManager = $settingManager;
    }

    public function sendMsg(User $user, $msg)
    {
        $phone = $user->getPhone();
        if ($phone != '') {
            $this->send($phone, $msg);
        }
    }

    public function getClient()
    {
        $smsEnabled = $this->settingManager->getSetting('smsEnabled')->getSettingValue();
        if (!$smsEnabled) {
            return false;
        }
        $apiId = $this->settingManager->getSetting('smsApiId')->getSettingValue();
        if ($apiId == '') {
            return false;
        }
        $client = new Api(new ApiIdAuth($apiId));
        if ($client->authCheck()->code != 100) {
            return false;
        }
        if ((int)$client->myLimit()->limit < 1) {
            return false;
        }
        return $client;
    }

    public function send($phone, $text)
    {
        $client = $this->getClient();
        if (!$client instanceof Api) {
            return false;
        }
        $sms = new Sms($phone, $text);
        $client->smsSend($sms);
        $balance = (float)$client->myBalance()->balance;
        $setting = $this->settingManager->getSetting('smsBalance')->setSettingValue($balance);
        $this->settingManager->store($setting);
        return $balance;
    }
}