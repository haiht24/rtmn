<?php

App::uses('AppController', 'Controller');

class CouponsController extends AppController
{
    public function index()
    {
        $options = $this->Coupon->buildOptions($this->params->query);
        $options['order'][] = 'Coupon.created DESC';
        if (!empty($this->request->query['expire_date_greater_null'])) {
            $options['conditions']['OR'] = [
                'Coupon.expire_date' => null,
                'DATE(Coupon.expire_date) >= ' => date('Y/m/d')
            ];
        }
        if (!empty($this->request->query['expire_date_greater'])) {
            $options['conditions'][] = [
                'DATE(Coupon.expire_date) >= ' => date('Y/m/d')
            ];
        }
        if (!empty($this->request->query['expired_date'])) {
            $options['conditions'][] = [
                'DATE(Coupon.expire_date) < ' => date('Y/m/d')
            ];
        }
        if (!empty($this->request->query['categoryId'])) {
            $options['conditions'][] = "Coupon.categories_id like '%" . $this->request->query['categoryId'] . "%'";
        }
        if (!empty($this->request->query['unbindModel'])) {
            $this->Coupon->unbindModel(
                $this->request->query['unbindModel']
            );
        }
        if (!empty($this->request->query['unbindAllExcept'])) {
            $this->Coupon->unbindAllExcept($this->request->query['unbindAllExcept']);
        }
        if (!empty($this->request->query['unbindAll'])) {
            $this->Coupon->unbindAll();
        }
        $coupons = $this->Coupon->find('all', $options);

        $count = 0;
        if (!empty($this->request->query['count'])) {
            if (!empty($options['limit'])) {
                unset($options['limit']);
            }
            $count = $this->Coupon->find('count', $options);
        }

        $this->set(array(
            'coupons' => $coupons,
            'count' => $count,
            '_serialize' => array('coupons', 'count')
        ));
    }

    public function add()
    {
        $data = $this->request->data;
        if (!empty($data)) {
            $this->set(array(
                'status' => $this->Coupon->save($data),
                'data' => $data,
                '_serialize' => array('data')));
        }else {
            $this->set(array(
                'status' => 'Failed',
                'data' => '',
                '_serialize' => array('data')));
        }
    }

    public function view($id)
    {
        $this->set(array(
            'coupon' => $this->Coupon->findById($id),
            '_serialize' => array('coupon')
        ));
    }
    public function emailSendInfo($toEmail = null, $data = [], $emailConfig = 'mcus')  {
        $email = new CakeEmail($emailConfig);
        $email->template('mcus_send_coupon_info')
            ->emailFormat('html')
            ->from(['mostcoupon@gmail.com' => 'MostCoupon'])
            ->to($toEmail)
            ->subject("MostCoupon - Coupon's Information")
            ->viewVars(['coupon' => $data])
            ->send();
    }

    public function sendMail(){
        $email = $this->getParam('email');
        $id = $this->getParam('id');
        $userObj = $this->Coupon->findById($id);
        if($userObj){
            $this->emailSendInfo($email,$userObj);
            $resp = "Send Coupon's Information succressful";
        }else{
            $resp = 'Your email not found in our system';
        }

        $this->set(['data' => json_encode($resp), '_serialize' => ['data']]);
    }

    public function saveall(){
        $this->set(array(
            'coupon' => $this->Coupon->updateAll(["publish_date" => "'".date('Y-m-d H:i:s')."'"]),
            '_serialize' => array('coupon')
        ));
    }
}