<?php

namespace LlamaKisses\Controllers;

use LlamaKisses\Models\Home;

class HomeController extends BaseController {

  protected function Index() {
    $viewmodel = new Home();
    $this->ReturnView($viewmodel->Index());
  }

}
