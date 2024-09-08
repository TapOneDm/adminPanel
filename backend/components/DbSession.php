<?php

namespace app\components;

use Yii;


class DbSession extends \yii\web\DbSession
{

    private $readedSessionId;
    private $readedSessionData;
    private $readedSessionExpire;

    public $expireUpdateTimeout = 0;

    private function getCacheKey($id)
    {
        return __METHOD__ . $id;
    }

    public function refreshCookie()
    {

        $params = $this->cookieParams;
        unset($params['lifetime']);
        $params['expires'] = time() + $this->timeout;

        setcookie(session_name(), session_id(), $params);
    }

    public function readSession($id)
    {
        $fields = Yii::$app->cache->get($this->getCacheKey($id));
        if ($fields === false) {
            $fields = $this->db->createCommand(
                '
                    select * from ' . $this->sessionTable . ' where [[id]]=:id and [[expire]]>:expire
                ',
                [
                    ':id' => $id,
                    ':expire' => time()
                ]
            )->queryOne();

            if ($fields !== false) {
                $fields['data'] = stream_get_contents($fields['data']);

                Yii::$app->cache->set($this->getCacheKey($id), $fields, $this->expireUpdateTimeout);
            }
        }

        if ($fields !== false) {
            $this->readedSessionExpire = $fields['expire'];
            $this->readedSessionData = $fields['data'];
            $this->readedSessionId = $fields['id'];
            return $this->readedSessionData;
        }

        return '';
    }

    public function writeSession($id, $data)
    {
        $timeout = $this->getTimeout();
        $now = time();

        if (
            $id !== $this->readedSessionId || $this->readedSessionData !== $data ||
            $this->readedSessionExpire - $now <= $timeout - $this->expireUpdateTimeout
        ) {
            try {
                $fields = [
                    'expire' => $now + $timeout,
                    'data' => $data,
                    'id' => $id
                ];

                $this->db->createCommand(
                    'insert into ' . $this->sessionTable . ' ([[id]],[[expire]],[[data]]) values(:id,:expire,:data)
                    on conflict([[id]]) do update set [[expire]]=EXCLUDED.[[expire]], [[data]]=EXCLUDED.[[data]]',
                    [
                        ':expire' => $fields['expire'],
                        ':data' => $fields['data'],
                        ':id' => $fields['id']
                    ]
                )->execute();

                Yii::$app->cache->set($this->getCacheKey($id), $fields, $this->expireUpdateTimeout);
            } catch (\Exception $e) {
                return false;
            }
            return true;
        } else {
            return true;
        }
    }

    public function destroySession($id)
    {
        Yii::$app->cache->delete($this->getCacheKey($id));

        if ($id == $this->readedSessionId) {
            $this->readedSessionId = null;
            $this->readedSessionData = null;
            $this->readedSessionExpire = null;
        }

        return parent::destroySession($id);
    }
}
