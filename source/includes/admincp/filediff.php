<?php

/*
	File Comparison System
	
	Created by: Ross Scrivener (www.scrivna.com)
	Enhanced by: Mihalism Technologies (www.mihalism.net)
*/

class filediff 
{
	var $diff = array();
	var $changes = array();
	var $linepadding = NULL;
	
	function doDiff($old, $new)
	{
		$old = ((is_array($old) == true) ? $old : file($old));
		$new = ((is_array($new) == true) ? $new : file($new));
	
		foreach ($old as $oindex => $ovalue) {
			$nkeys = array_keys($new, $ovalue);
			
			foreach ($nkeys as $nindex ){
				$matrix[$oindex][$nindex] = ((isset($matrix[($oindex - 1)][($nindex - 1)]) == true) ? ($matrix[($oindex - 1)][($nindex - 1)] + 1) : 1);
				
				if ($matrix[$oindex][$nindex] > $maxlen) {
					$maxlen = $matrix[$oindex][$nindex];
					$omax = (($oindex + 1) - $maxlen);
					$nmax = (($nindex + 1) - $maxlen);
				}
			}       
		}
		
		if ($maxlen == 0) {
			return array(array("d" => $old, "i" => $new));
		} else {
			return array_merge($this->doDiff(array_slice($old, 0, $omax), array_slice($new, 0, $nmax)), array_slice($new, $nmax, $maxlen), $this->doDiff(array_slice($old, ($omax + $maxlen)), array_slice($new, ($nmax + $maxlen))));
		}
	}
	
	function diffWrap($old, $new)
	{
		$ndiff = array();
		
		$this->changes = array();
		$this->diff = $this->doDiff($old, $new);
		
		foreach ($this->diff as $line => $k) {
			if (is_array($k) == true) {
				if (isset($k['d'][0]) == true || isset($k['i'][0]) == true) {
					$ndiff[$line] = $k;
					$this->changes[] = $line;
				}
			} else {
				$ndiff[$line] = $k;
			}
		}
		
		$this->diff = $ndiff;
		return $this->diff;
	}
	
	function formatcode($code)
	{
		$code = highlight_string(sprintf("<?php %s ?>", $code), true);
		$code = str_replace(array("<code>", "</code>", "<br />", "&lt;?php", "?&gt;"), NULL, $code);
		
		return $code;
	}
	
	function showline($line){
		if ($this->linepadding === 0) {
			return ((in_array($line,$this->changes) == true) ? true : false); 
		}
		
		if (is_null($this->linepadding) == true) {
			return true;
		}

		$end = ($line + $this->linepadding);
		$start = ((($line - $this->linepadding) > 0) ? ($line - $this->linepadding) : 0);
		
		$search = range($start, $end);
		
		foreach($search as $k){
			if (in_array($k, $this->changes) == true) {
				return true;
			}
		}
		
		return false;
	}
	
	function inline($old, $new, $linepadding = 2, $oldtitle = "Local", $newtitle = "SVN"){
		ini_set("highlight.comment", "#666666;");
		
		$this->linepadding = $linepadding;
		
		$diffhtml = "<pre><table width=\"100%\" border=\"0\" cellspacing=\"0\" cellpadding=\"0\" class=\"code\">";
		$diffhtml .= "<tr><td style=\"text-align: center;\">{$oldtitle}</td><td style=\"text-align: center;\">{$newtitle}</td><td></td></tr>";
		
		$count_old = 1;
		$count_new = 1;
		
		$insert = false;
		$delete = false;
		$truncate = false;
		
		$diff = $this->diffWrap($old, $new);

		foreach ($diff as $line => $k) {
			if ($this->showline($line) == true) {
				$truncate = false;
				
				if (is_array($k) == true) {
					foreach ($k['d'] as $val) {
						$class = NULL;
						
						if ($delete == false) {
							$delete = true;
							$class = (($insert == true) ? NULL : "first");
							$insert = false;
						}
						
						$diffhtml .= "<tr><th>{$count_old}</th>";
						$diffhtml .= "<th>&nbsp;</th>";
						$diffhtml .= "<td class=\"sign\">-</td>";
						$diffhtml .= "<td class=\"del {$class}\">{$this->formatcode($val)}</td>";
						$diffhtml .= "</tr>";
						
						$count_old++;
					}
					
					foreach ($k['i'] as $val) {
						$class = NULL;
						
						if ($insert == false) {
							$insert = true;
							$class = (($delete == true) ? NULL : "first");
							$delete = false;
						}
						
						$diffhtml .= "<tr><th>&nbsp;</th>";
						$diffhtml .= "<th>{$count_new}</th>";
						$diffhtml .= "<td class=\"sign\">+</td>";
						$diffhtml .= "<td class=\"ins {$class}\">{$this->formatcode($val)}</td>";
						$diffhtml .= "</tr>";
						
						$count_new++;
					}
				} else {
					$class = (($delete == true) ? "del_end" : NULL);
					$class = (($insert == true) ? "ins_end" : $class);
					
					$delete = false;
					$insert = false;
					
					$diffhtml .= "<tr><th>{$count_old}</th>";
					$diffhtml .= "<th>{$count_new}</th>";
					$diffhtml .= "<td class=\"sign\"></td>";
					$diffhtml .= "<td class=\"{$class}\">{$this->formatcode($k)}</td>";
					$diffhtml .= "</tr>";
					
					$count_old++;
					$count_new++;
				}
			} else {
				$class = (($delete == true) ? "del_end" : NULL);
				$class = (($insert == true) ? "ins_end" : $class);
				
				$delete = false;
				$insert = false;
				
				if ($truncate == false) {
					$truncate = true;
					
					$diffhtml .= "<tr><th>...</th>";
					$diffhtml .= "<th></th>";
					$diffhtml .= "<td class=\"sign\"></td>";
					$diffhtml .= "<td class=\"truncated {$class}\">&nbsp;</td>";
					$diffhtml .= "</tr>";
				}
				
				$count_old++;
				$count_new++;
			}
		}
		
		$diffhtml .= "</table></pre>";
		
		return $this->output($diffhtml, count($this->changes));
	}
	
	function output($html, $changes)
	{
		return "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.0 Strict//EN\" \"http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd\">
		<html xmlns=\"http://www.w3.org/1999/xhtml\" dir=\"ltr\" lang=\"en-us\" xml:lang=\"en-us\">
			<head>
				<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\" />
				<meta http-equiv=\"Content-Style-Type\" content=\"text/css\" />
				<meta http-equiv=\"Content-Language\" content=\"en-us\" />
				<title>File Comparison</title>
				<style type=\"text/css\"><!--
					body { font-family: Verdana, Arial, Verdana, helvetica, sans-serif; font-size: 12px; color: #000000; background-color: #ffffff; padding: 0; margin: 0; }
					h2 { font-family: \"Trebuchet MS\", Helvetica, sans-serif; font-size: 1.70em; font-weight: 700; color: #333333; margin: 10px 0 0 15px; }
					a:link, a:visited { text-decoration: none; color: #0066aa; }
					a:active, a:hover { text-decoration: underline; }
					.copyright { text-align: center; font-size: 10px; margin-bottom: 10px; } 
					table.code { border: 1px solid #ddd; color: #666; border-spacing: 0; border-top: 0; empty-cells: show; font-size: 12px; line-height: 110%; padding: 0; margin: 0; width: 100%; }
					table.code th { background-color: #f7f7f7; padding: 0 0.5em; text-align: right; font-size: 11px; width: 25px; font-weight: normal; }
					table.code td { font-family: monospace, Verdana, Arial, Verdana, helvetica, sans-serif; font-size: 8pt; background: #fff; padding: 1px 2px; white-space: nowrap; overflow: none; }
					table.code td.del { background-color: #ffd8d8; border-left: 1px solid #a33; border-right: 1px solid #a33; }
					table.code td.del.first { border-top: 1px solid #a33; }
					table.code td.ins { background-color: #ddf8cc; border-left: 1px solid #3a3; border-right: 1px solid #3a3; }
					table.code td.ins.first { border-top: 1px solid #3a3; }
					table.code td.del_end {	border-top: 1px solid #a33; }
					table.code td.ins_end { border-top: 1px solid #3a3; }
					table.code td.truncated { background-color: #f7f7f7; }
					table.code td.sign { background-color: #f7f7f7; width: 10px; font-weight: 700; font-size: 12px; }
				--></style>
			</head>
			<body>
				<h2>File Comparison - {$changes} Changes</h2>
				{$html}
				<div class=\"copyright\">
					File Comparison System by <a href=\"http://scrivna.com/\">Ross Scrivener</a> - 
					Enhanced by <a href=\"http://www.mihalism.net/\">Mihalism Technologies</a>
				</div>
			</body>
		</html>";	
	}
}

?>