<?php
/**
 * 
 * Copyright (c) 2010, SRIT Stefan Riedel <info@srit-stefanriedel.de>
 * All rights reserved.
 *
 * Redistribution and use in source and binary forms, with or without modification,
 * are permitted provided that the following conditions are met:
 * - Redistributions of source code must retain the above copyright notice, 
 * this list of conditions and the following disclaimer.
 * - Redistributions in binary form must reproduce the above copyright notice, 
 * this list of conditions and the following disclaimer in the documentation 
 * and/or other materials provided with the distribution.
 * - Neither the name of the author nor the names of its 
 * contributors may be used to endorse or promote products derived from 
 * this software without specific prior written permission.
 * 
 * THIS SOFTWARE IS PROVIDED BY THE COPYRIGHT HOLDERS AND CONTRIBUTORS "AS IS" 
 * AND ANY EXPRESS OR IMPLIED WARRANTIES, INCLUDING, BUT NOT LIMITED TO, 
 * THE IMPLIED WARRANTIES OF MERCHANTABILITY AND FITNESS FOR A PARTICULAR 
 * PURPOSE ARE DISCLAIMED. IN NO EVENT SHALL THE COPYRIGHT OWNER OR 
 * CONTRIBUTORS BE LIABLE FOR ANY DIRECT, INDIRECT, INCIDENTAL, SPECIAL, 
 * EXEMPLARY, OR CONSEQUENTIAL DAMAGES (INCLUDING, BUT NOT LIMITED TO, 
 * PROCUREMENT OF SUBSTITUTE GOODS OR SERVICES; LOSS OF USE, DATA, OR PROFITS; 
 * OR BUSINESS INTERRUPTION) HOWEVER CAUSED AND ON ANY THEORY OF LIABILITY, 
 * WHETHER IN CONTRACT, STRICT LIABILITY, OR TORT (INCLUDING NEGLIGENCE 
 * OR OTHERWISE) ARISING IN ANY WAY OUT OF THE USE OF THIS SOFTWARE, 
 * EVEN IF ADVISED OF THE POSSIBILITY OF SUCH DAMAGE.
 *
 * PHP version 5
 *
 * @author    Stefan Riedel <info@srit-stefanriedel.de>
 * @copyright 2010 SRIT Stefan Riedel
 * @license   http://www.opensource.org/licenses/bsd-license.php New BSD License
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>

<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<meta name="description" content="Reflect Template" />
<meta http-equiv="X-UA-Compatible" content="IE=EmulateIE7" />
<title><?=$title?></title>
        
        <?php
								foreach ( $styles as $file => $type ) {
									if(is_array($type)) {
										$attributes = $type;	
									}
									else {
										$attributes = array('media' => $type);
									}
									echo HTML2::style ( $file, $attributes ), "\n";
								}							
		?>
      	<?php
							foreach ( $scripts as $file )
								echo HTML::script ( $file ), "\n"?>
    </head>

<body>
<!-- this is the content for the dialog that pops up on window start -->
<div id="top">
<div id="dialog"></div>
<div id="content">
<div class="content_block">
<h2 class="jquery_tab_title"><?=$siteTitle?></h2>
                                <?=(! empty ( $errors )) ? HTML2::errorBlock ( $errors ) : ''?>
                                <?=$content?>
                            </div>
</div>
<!--end content--></div>
<!-- end top -->
        <?php
								if (Kohana::config ( 'defaults.debug' )) {
									?>
        <div id="kohana-profiler">
		<?php
									echo View::factory ( 'profiler/stats' )?>
		</div>
        <?php
								}
								?>
                    	
<?php
$javascriptCode = HTML2::getJavaScriptCodes ();
if (! empty ( $javascriptCode )) :
	?>
<script type="text/javascript">
    /* <![CDATA[ */
    jQuery(document).ready(function(){
    <?php
	foreach ( $javascriptCode as $jsCode ) :
		?>
        <?=$jsCode;?>
    <?php
	endforeach
	;
	?>
    });
    /* ]]> */
</script>

<?php endif;
?>
    </body>

</html>
