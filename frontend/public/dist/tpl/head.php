<?php
require DIR_BASE."/php/views/UnderscoreTemplateOutput.php";
$utu = new UnderscoreTemplateOutput(DIR_BASE);
echo $utu->getTemplates("T_MainView", "src/tpl/MainView.html");
echo $utu->getTemplates("T_ConnectionsDropdownView", "src/tpl/ConnectionsDropdownView.html");
echo $utu->getTemplates("T_SingleConnectedView", "src/tpl/SingleConnectedView.html");
echo $utu->getTemplates("T_ConnectedGroupView", "src/tpl/ConnectedGroupView.html");
echo $utu->getTemplates("T_MainMessageView", "src/tpl/MainMessageView.html");
echo $utu->getTemplates("T_SelectorView", "src/tpl/SelectorView.html");
echo $utu->getTemplates("T_SelectorCtrView", "src/tpl/SelectorCtrView.html");
echo $utu->getTemplates("T_SingleItemView", "src/tpl/SingleItemView.html");
echo $utu->getTemplates("T_BreadcrumbsView", "src/tpl/BreadcrumbsView.html");
echo $utu->getTemplates("T_ConnectionIndicatorView", "src/tpl/ConnectionIndicatorView.html");
echo $utu->getTemplates("T_SelectorMessageView", "src/tpl/SelectorMessageView.html");
echo $utu->getTemplates("T_SingleBtnView", "src/tpl/SingleBtnView.html");
 ?>
<meta id="viewport" name="viewport" content ="width=device-width, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no"/>
<meta name="apple-mobile-web-app-capable" content="yes" />
<meta name="apple-mobile-web-app-status-bar-style" content="black" />
<link href="dist/css/style.min.css" rel="stylesheet"/>
<link href="dist/css/style.min.css.map" rel="stylesheet"/>
<script src="src/js/dy.js"></script>
<script src="src/js/lib/modernizr/2.7.1/modernizr.custom.79300.js"></script>
<script src="src/js/lib/jquery/2.1.0/jquery-2.1.0.js"></script>
<script src="src/js/lib/underscore/1.6.0/underscore.js"></script>
<script src="src/js/lib/backbone/1.1.2/backbone.js"></script>
<script src="src/js/lib/hammer/1.0.6/hammer.js"></script>
<script src="src/js/lib/namespace/Namespace.js"></script>
<script src="src/js/lib/greensock/1.11.8/src/minified/TweenLite.min.js"></script>
<script src="src/js/lib/greensock/1.11.8/src/minified/plugins/CSSPlugin.min.js"></script>
<script src="src/js/lib/greensock/1.11.8/src/uncompressed/TimelineLite.js"></script>
<script src="src/js/lib/greensock/1.11.8/src/uncompressed/easing/EasePack.js"></script>
<script src="src/js/lib/moment/2.8.1/moment-with-locales.js"></script>
<script src="src/js/lib/wordmath/wordMath.vanilla.js"></script>
<script src="src/js/app/Controller/Abstract.js"></script>
<script src="src/js/app/Controller/MainController.js"></script>
<script src="src/js/app/Controller/SelectorController.js"></script>
<script src="src/js/app/Controller/ConnectionIndicatorController.js"></script>
<script src="src/js/app/Controller/MainMessageController.js"></script>
<script src="src/js/app/Controller/ConnectedListController.js"></script>
<script src="src/js/app/Facade/ArcLoadingGraphic.js"></script>
<script src="src/js/app/Factory/String.js"></script>
<script src="src/js/app/Model/API.js"></script>
<script src="src/js/app/Model/AbstractModel.js"></script>
<script src="src/js/app/Model/AbstractCollection.js"></script>
<script src="src/js/app/Model/MainModel.js"></script>
<script src="src/js/app/Model/FromModel.js"></script>
<script src="src/js/app/Model/FromCollection.js"></script>
<script src="src/js/app/Model/ToModel.js"></script>
<script src="src/js/app/Model/ToCollection.js"></script>
<script src="src/js/app/Model/ConnectRequest.js"></script>
<script src="src/js/app/Model/ConnectedModel.js"></script>
<script src="src/js/app/Model/ConnectedCollection.js"></script>
<script src="src/js/app/View/Abstract.js"></script>
<script src="src/js/app/View/MainView.js"></script>
<script src="src/js/app/View/SelectorView.js"></script>
<script src="src/js/app/View/MainMessageView.js"></script>
<script src="src/js/app/View/ConnectionsDropdownView.js"></script>
<script src="src/js/app/View/ConnectedGroupView.js"></script>
<script src="src/js/app/View/SingleConnectedView.js"></script>
<script src="src/js/app/View/ConnectionIndicatorView.js"></script>
<script src="src/js/app/View/SelectorMessageView.js"></script>
<script src="src/js/app/View/SingleBtnView.js"></script>
<script src="src/js/app/View/SingleItemView.js"></script>
<script src="src/js/app/View/BreadcrumbsView.js"></script>
<script src="src/js/app/app.js"></script>
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Cinzel:400,700,900">
<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Open+Sans:300italic,400italic,600italic,700italic,800italic,400,300,600,700,800">
