<?php

// Include our HTML Page Class
require_once ("oo_page.inc.php");

class MasterPage
{

    // -------FIELD MEMBERS----------------------------------------
    private $_htmlpage;

    private $_dynamic_2;

    // Field Representing our Dynamic Content #2
    private $_dynamic_3;

    // Field Representing our Dynamic Content #3

    // -------CONSTRUCTORS-----------------------------------------
    function __construct($ptitle)
    {
        $this->_htmlpage = new HTMLPage($ptitle);
        $this->setPageDefaults();
        $this->setDynamicDefaults();
    }

    // -------GETTER/SETTER FUNCTIONS------------------------------
    public function getDynamic2()
    {
        return $this->_dynamic_2;
    }

    public function getDynamic3()
    {
        return $this->_dynamic_3;
    }

    public function setDynamic2($phtml)
    {
        $this->_dynamic_2 = $phtml;
    }

    public function setDynamic3($phtml)
    {
        $this->_dynamic_3 = $phtml;
    }

    public function getPage(): HTMLPage
    {
        return $this->_htmlpage;
    }

    // -------PUBLIC FUNCTIONS-------------------------------------
    public function createPage()
    {
        // Create our Dynamic Injected Master Page
        $this->setMasterContent();
        // Return the HTML Page..
        return $this->_htmlpage->createPage();
    }

    public function renderPage()
    {
        // Create our Dynamic Injected Master Page
        $this->setMasterContent();
        // Echo the page immediately.
        $this->_htmlpage->renderPage();
    }

    public function addCSSFile($pcssfile)
    {
        $this->_htmlpage->addCSSFile($pcssfile);
    }

    public function addScriptFile($pjsfile)
    {
        $this->_htmlpage->addScriptFile($pjsfile);
    }

    // -------PRIVATE FUNCTIONS-----------------------------------
    private function setPageDefaults()
    {
        $this->_htmlpage->setMediaDirectory("css", "js", "fonts", "img", "data");
        $this->addCSSFile("bootstrap.css");
        $this->addCSSFile("site.css");
        $this->addScriptFile("jquery-2.2.4.js");
        $this->addScriptFile("bootstrap.js");
        $this->addScriptFile("holder.js");
    }

    private function setDynamicDefaults()
    {
        $tcurryear = date("Y");
        // Set the Two Dynamic Points to Empty By Default.
        $this->_dynamic_2 = "";
        $this->_dynamic_3 = <<<FOOTER
        <p><center>Sameer's Reviews - {$tcurryear}</center></p>
        FOOTER;
    }

    private function setMasterContent()
    {
        $tlogin = "app_entry.php";
        $tlogout = "app_exit.php";
        $tentryhtml = <<<FORM
        <form id="signin" action="{$tlogin}" method="post"
         class="navbar-form navbar-right" role="form">
            <div class="input-group">
              <input id="username" type="text" class="form-control"
                     name="username" value="" placeholder="Username" required="">
            </div>
            <div class="input-group">
              <input id="password" type="password" class="form-control"
                     name="password" value="" placeholder="Password" required="">
            </div>
            <button type="submit" class="btn btn-info">Log in</button>
            <p>Don't have an account? Click <a href="signUp.php">here</a> to sign up</p>
        </form>
        FORM;
        $texithtml = <<<EXIT
        <a class="btn btn-info navbar-right"
            href="{$tlogout}?action=exit">Log out</a>
        EXIT;
        $tauth = isset($_SESSION["myuser"]) ? $texithtml : $tentryhtml;
        if (isset($_SESSION["myuser"])) {
            $tauth = $texithtml;
        } else {
            $tauth = $tentryhtml;
        }
        $tmasterpage = <<<MASTER
            <div class="container">
        	   <div class="header clearfix">
        		  <nav>
                    <h3 class="text-info">&ensp;Sameer's Reviews</h3>
        		     {$tauth}
        			 <ul class="nav nav-pills pull-left">
                            <li role="presentation"><a href="index.php">Home</a></li>
        				    <li role="presentation"><a href="operatingSystem.php">iOS Operating System</a></li>
        				    <li role="presentation"><a href="device.php?deviceid=1">iPhone XR</a></li>
        				    <li role="presentation"><a href="device.php?deviceid=2">iPhone 12 Pro Max</a></li>
                            <li role="presentation"><a href="device.php?deviceid=3">Galaxy S20 FE</a></li>
                            <li role="presentation"><a href="rankings.php">Rankings</a></li>
        			 </ul>
        			 
        		  </nav>
        	   </div>
        	<div class="row details">
        		{$this->_dynamic_2}
            </div>
            <footer class="footer">
        		{$this->_dynamic_3}
        	</footer>
        </div>        
        MASTER;
        $this->_htmlpage->setBodyContent($tmasterpage);
    }
}

?>