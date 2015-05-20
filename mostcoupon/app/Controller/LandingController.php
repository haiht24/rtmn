<?php
class LandingController extends AppController {
         
    public function index(){
     
        $categories = $this->mCusApi->resource('Category')->query(array('status' => 'active'));          
        $this->set('categories', $categories['categories']);
        
        $hotdeals = $this->mCusApi->resource('Deal')->query(array('limit' => 4, 'hot_deal' => 1, 'status' => 'active'));
        $this->set('hotdeals', $hotdeals['deals']);
        
        $latestDeals = $this->mCusApi->resource('Deal')->query(array('limit' => 12, 'status' => 'active'));         
        $latestDeals1 = $latestDeals2 = array();
        
        $i = 0;
        
        foreach ($latestDeals['deals'] as $deal) {
           if ($i < 8) {
               array_push($latestDeals1, $deal);
           } else {
               array_push($latestDeals2, $deal);
           }
           $i ++; 
        }
        
        $this->set('latestDeals1', $latestDeals1);
        $this->set('latestDeals2', $latestDeals2);        
        
        $stores = $this->mCusApi->resource('Store')->query(array('limit' => 15, 'status' => 'publish'));
        $this->set('stores', $stores['stores']);
        
    }
}
