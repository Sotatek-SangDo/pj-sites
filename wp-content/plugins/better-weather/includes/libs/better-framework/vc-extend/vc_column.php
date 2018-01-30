<?php
global $_vc_column_template_file,$_bf_vc_column_inner_atts,$_bf_vc_column_atts;


//todo: include $variable can cause security concerns
//EX: extract(...)
$_bf_vc_column_atts = $atts;
$_bf_vc_column_inner_atts = array();
if($_vc_column_template_file)
	include $_vc_column_template_file;

