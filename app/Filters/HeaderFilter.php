<?php


namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class HeaderFilter implements FilterInterface {

    public function before(RequestInterface $request) {
        $response = Services::response();
        $response->setHeader('Last-Modified:' . gmdate('D, d M Y H:i:s') . 'GMT');
        $response->setHeader('Cache-Control: no-store, no-cache, must-revalidate');
        $response->setHeader('Cache-Control: post-check=0, pre-check=0', false);
        $response->setHeader('Pragma: no-cache');
    }
    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response) {
        // Do something here
        }

}

