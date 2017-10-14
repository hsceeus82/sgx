<?php
	ini_set('display_errors', 1);
	include("Helper/Dom.php");
	include("Helper/Common.php");
	include("Helper/Crawl.php");
	
	$crawl=new Crawl;
	$dom = new Dom;
    $common = new Common;
	
	$arrUrl[0] = "http://www.sharesinv.com/Z74/"; // singtel
	$arrUrl[1] = "http://www.sharesinv.com/TQ5/"; // Frasers Cpt
	$arrUrl[2] = "http://www.sharesinv.com/NS8U/"; // HPH
	$arrUrl[3] = "http://www.sharesinv.com/NO4/"; // Dyna mac
	$arrUrl[4] = "http://www.sharesinv.com/J2T/"; // HockLianSeng
	$arrUrl[5] = "http://www.sharesinv.com/J85/"; // CDL HTrust
	$arrUrl[6] = "http://www.sharesinv.com/SK6U/"; // SPH REIT
	$arrUrl[7] = "http://www.sharesinv.com/S7OU/"; // Asian Pay TV Tr
	$arrUrl[8] = "http://www.sharesinv.com/D05/"; // DBS
	$arrUrl[9] = "http://www.sharesinv.com/CC3/"; // starhub
	$arrUrl[10] = "http://www.sharesinv.com/C6L/"; // SIA
	$arrUrl[11] = "http://www.sharesinv.com/O39/"; // OCBC
	$arrUrl[12] = "http://www.sharesinv.com/CJLU/"; // NETLINK
	$arrUrl[13] = "http://www.sharesinv.com/B2F/"; // M1
	$arrUrl[14] = "http://www.sharesinv.com/S59/"; // SIA ENG
	
	$count=0;
	$index=0;
	foreach($arrUrl as $url)
	{
		//$url="http://www.sharesinv.com/S10/search/";
		$crawl->url=$url;
		
		$crawl->jar="cookie.txt";
		$content=$crawl->curl();
		$content = str_replace('<font>', '</font>', $content); // fix invalid <font> close tag
		
		$html=$dom->str_get_html($content);
		
		// Get latest stock price
		$iTrCount = 0;
		$trs1=$html->find('table[class=company_shares] tr');
		foreach($trs1 as $tr){
		
			// get <td>
			$tds1=$tr->find('td');
			
			if($iTrCount == 0)
			{
				$minus=0;
				if($count == 6 || $count == 7 || $count == 1 || $count == 2 || $count == 12) $minus=1; // only in HPH
				
				$temp[$count]['price']=$common->clean_dom($tds1[1-$minus]->innertext); // get position 1 td
				$temp[$count]['incremental']=$common->clean_dom($tds1[2-$minus]->innertext); // get position 2 td
				$sBuy=$common->clean_dom($tds1[3-$minus]->innertext); // get position 3 td
				$temp[$count]['buy']=str_replace('Buy: ', '', $sBuy); // buy value
			}
			elseif($iTrCount == 0)
			{		
				//$temp[$count]['vol']=$common->clean_dom($tds1[0]->innertext); // get position 0 td
				$sSell=$common->clean_dom($tds1[1]->innertext); // get position 1 td
				$temp[$count]['sell']=str_replace('Sell: ', '', $sSell); // sell value
			}

			$iTrCount++;
		}
		
		// Get trading statistic
		$iTrCount = 0;
		$trs2=$html->find('table[class=trading_stat] tr');
		foreach($trs2 as $tr){
			
			// get <td>
			$tds1=$tr->find('td');
			
			if($iTrCount == 0)
			{		
				$temp[$count]['open']=$common->clean_dom($tds1[1]->innertext); // get position 1 td
				$temp[$count]['pe']=$common->clean_dom($tds1[5]->innertext); // get position 5 td
			}
			elseif($iTrCount == 1)
			{		
				$temp[$count]['high']=$common->clean_dom($tds1[1]->innertext); // get position 1 td
				$temp[$count]['eps']=$common->clean_dom($tds1[5]->innertext); // get position 5 td
			}
			elseif($iTrCount == 2)
			{		
				$temp[$count]['low']=$common->clean_dom($tds1[1]->innertext); // get position 1 td
				$temp[$count]['52wkhigh']=$common->clean_dom($tds1[3]->innertext); // get position 3 td
			}
			elseif($iTrCount == 3)
			{		
				$temp[$count]['52wklow']=$common->clean_dom($tds1[3]->innertext); // get position 3 td
				$temp[$count]['yield']=$common->clean_dom($tds1[5]->innertext); // get position 5 td
			}
			elseif($iTrCount == 4)
			{		
				$temp[$count]['AvgVol']=$common->clean_dom($tds1[3]->innertext); // get position 3 td
				//$temp[$count]['yield']=$common->clean_dom($tds1[5]->innertext); // get position 5 td
			}
			
			$iTrCount++;
		}
		
		$count++;
	}
	
	function getName($index=0)
	{
		if($index == 0) return "Singtel";
		if($index == 1) return "Frasers Cpt";
		if($index == 2) return "HPH";
		if($index == 3) return "Dyna-Mac";
		if($index == 4) return "HockLianSeng";
		if($index == 5) return "CDL HTrust";
		if($index == 6) return "SPH REIT";
		if($index == 7) return "Asian Pay TV Tr";
		if($index == 8) return "DBS";
		if($index == 9) return "Starhub";
		if($index == 10) return "SIA";
		if($index == 11) return "OCBC";
		if($index == 12) return "NETLINK";
		if($index == 13) return "M1";
		if($index == 14) return "SIA ENG";
		//if($index == 4) return "BRC Asia";
		//if($index == 5) return "First Resources";
		//if($index == 6) return "800 Super";
	}
?>
<!DOCTYPE html>
<html>

<head>
	<meta charset='UTF-8'>
	<title>Table</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<link rel="stylesheet" href="css/style.css">
	<!--[if !IE]><!-->
	<style>
	/* 
	Max width before this PARTICULAR table gets nasty
	This query will take effect for any screen smaller than 760px
	and also iPads specifically.
	*/
	@media 
	only screen and (max-width: 760px),
	(min-device-width: 768px) and (max-device-width: 1024px)  {
	
		/* Force table to not be like tables anymore */
		table, thead, tbody, th, td, tr { 
			display: block; 
		}
		
		/* Hide table headers (but not display: none;, for accessibility) */
		thead tr { 
			position: absolute;
			top: -9999px;
			left: -9999px;
		}
		
		tr { border: 1px solid #ccc; }
		
		td { 
			/* Behave  like a "row" */
			border: none;
			border-bottom: 1px solid #eee;
			/*border: 1px solid #ccc;*/
			position: relative;
			padding-left: 50%; 
		}
		
		td:before { 
			/* Now like a table header */
			position: absolute;
			/* Top/left values mimic padding */
			top: 6px;
			left: 6px;
			width: 45%; 
			padding-right: 10px; 
			white-space: nowrap;
		}
		
		/*
		Label the data
		*/
		td:nth-of-type(1):before { content: "Name"; }
		td:nth-of-type(2):before { content: "Price"; }
		td:nth-of-type(3):before { content: "Change"; }
		td:nth-of-type(4):before { content: "Buy"; }
		td:nth-of-type(5):before { content: "Sell"; }
		td:nth-of-type(6):before { content: "Open"; }
		td:nth-of-type(7):before { content: "High"; }
		td:nth-of-type(8):before { content: "Low"; }
		td:nth-of-type(9):before { content: "Volume"; }
		td:nth-of-type(10):before { content: "PE"; }
		td:nth-of-type(11):before { content: "EPS"; }
		td:nth-of-type(12):before { content: "52W High"; }
		td:nth-of-type(13):before { content: "52W Low"; }
		td:nth-of-type(14):before { content: "Yield"; }
	}
	
	/* Smartphones (portrait and landscape) ----------- */
	@media only screen
	and (min-device-width : 320px)
	and (max-device-width : 480px) {
		body { 
			padding: 0; 
			margin: 0; 
			width: 320px; }
		}
	/* iPads (portrait and landscape) ----------- */
	@media only screen and (min-device-width: 768px) and (max-device-width: 1024px) {
		body { 
			width: 495px; 
		}
	}
	</style>
	<!--<![endif]-->
</head>
<body>
	<div id="page-wrap">
	<h1>Table</h1>
	<table>
		<thead>
		<tr>
			<th>Name</th>
			<th class="numeric">Price</th>
			<th class="numeric">Change</th>
			<th class="numeric">Buy</th>
			<th class="numeric">Sell</th>
			<th class="numeric">Open</th>
			<th class="numeric">High</th>
			<th class="numeric">Low</th>
			<th class="numeric">Volume</th>
			<th class="numeric">PE</th>
			<th class="numeric">EPS</th>
			<th class="numeric">52W High</th>
			<th class="numeric">52W Low</th>
			<th class="numeric">Yield</th>
		</tr>
		</thead>
		<tbody>
		<?php foreach($temp as $rec){ ?>
			<tr>
				<td data-title="Company"><?php echo getName($index); ?></td>
				<td data-title="Price" class="numeric"><?php echo $rec['price']; ?></td>
				<td data-title="Change" class="numeric"><?php echo $rec['incremental']; ?></td>
				<td data-title="Buy" class="numeric"><?php echo $rec['buy']; ?></td>
				<td data-title="Sell" class="numeric"><?php echo $rec['sell']; ?></td>
				<td data-title="Open" class="numeric"><?php echo $rec['open']; ?></td>
				<td data-title="High" class="numeric"><?php echo $rec['high']; ?></td>
				<td data-title="Low" class="numeric"><?php echo $rec['low']; ?></td>
				<td data-title="Volume" class="numeric"><?php echo $rec['AvgVol']; ?></td>
				<td data-title="PE" class="numeric"><?php echo $rec['pe']; ?></td>
				<td data-title="EPS" class="numeric"><?php echo $rec['eps']; ?></td>
				<td data-title="52W High" class="numeric"><?php echo $rec['52wkhigh']; ?></td>
				<td data-title="52W Low" class="numeric"><?php echo $rec['52wklow']; ?></td>
				<td data-title="Yield" class="numeric"><?php echo $rec['yield']; ?></td>
			</tr>
		<?php $index++; } ?>
		</tbody>
	</table>
	</div>	
</body>
</html>