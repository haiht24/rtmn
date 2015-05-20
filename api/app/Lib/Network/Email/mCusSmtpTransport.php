<?php

App::uses('SmtpTransport', 'Network/Email');
App::uses('CakeLog', 'Log');

class mCusSmtpTransport extends SmtpTransport {

    public function send(CakeEmail $email) {
        if(Configure::read('debug')) {
            $allowedAdr = array();
            foreach ($email->to() as $adr => $name) {

				//DEV MODE, only send emails to developers
				if(Configure::read('debug') > 1){
					foreach(Configure::read('DevelopmentEmailWhitelist') as $needle){
						if(strstr($adr, $needle)){
							$allowedAdr[$adr] = $name;
						}
					}
				}

            }
            if(sizeof($allowedAdr) > 0) {
                $email->to($allowedAdr);
                parent::send($email);
                $this->_content['skipped'] = false;
            } else {
                $this->_content['skipped'] = true;
				CakeLog::debug('Development mode, sending email to '.$adr.' ['.$name.'] will be skipped');
            }
        } else {
            parent::send($email);
            $this->_content['skipped'] = false;
        }
        return $this->_content;
    }

}
?>