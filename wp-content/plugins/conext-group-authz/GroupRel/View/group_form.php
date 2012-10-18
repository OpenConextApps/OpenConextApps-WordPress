<?php
/**
 * Extremely simple layout abstraction from data
 * 
 * Data in:
 * $data["groups"] containing cGroup instances
 * $data["form"] contains an array with formdata:
 *   ["action"] the action to post to
 *   ["method"] the post method
 *   ["selected"] array with cSelectable instances that contain defaults
 * $data["hiddenfields"] the fields that should be hidden in the form but posted
 * $data["showsubmit"] boolean that indicates whether to show the submit-button (default:true) 
 *   
 * $data["root"] contains web-path to document-root
 */
?>

<style type="text/css" media="screen">
<!--
  div.coinGroupSelector {
    font-family: sans-serif;
    
    width: 400px;
    background: #F1F2F3;
    //border: dotted;
    padding: 2px;
  }

  span.spGroup {
    font-weight: bold;
    text-align: left;
  }  

  span.spContact {
    font-weight: normal;
    text-align: left;
    font-size: smaller;
  }
  
  span.cpActionRow {
    width: 100%;
    text-align: right;
  }
-->
</style>

<div class="coinGroupSelector">
<? if ($data["form"] && $data["form"] != "embed") {
	$aForm = $data["form"];
	echo "<form" . ($aForm["action"] ? " action=\"" . $aForm["action"] . "\"" : "" )
	 . ($aForm["method"] ? " method=\"" . $aForm["method"] . "\"" : "" )
	 . ">\n";
  }
  if (is_array($data["hiddenfields"])) {
  	foreach ($data["hiddenfields"] as $name => $value) {
	   	echo "<input type=\"hidden\" name=\"" . $name . "\" value=\"" . $value . "\" />\n";
    }
  }
?>
<table width="100%" border="0">
  <thead>
    <tr>
      <td colspan="3" align="center">
        Selecteer groep(en) of perso(o)n(en)<hr />
      </td>
    </tr>
  </thead>
  <tbody>
<?
foreach ($data["groups"] as $aGroup) { 
	$sDisplayName = (array_key_exists("title", $aGroup->_aAttributes) ? 
			$aGroup->_aAttributes["title"] : 
			$aGroup->getIdentifier());
	?>
    <tr>
      <td colspan="1">
        <? if (sizeof($aGroup->getContacts()) > 0) { ?>
        <img src="<?=$data["root"]?>/resources/Person-group-add.png" height="16" width="16" />
        <? } else { ?>
        <img src="<?=$data["root"]?>/resources/Person-group.png" height="16" width="16" />
        <? }?>
      </td>
      <td>
        <input type="checkbox" name="<?=$aGroup->getInputName();?>" <?=(array_key_exists($aGroup->getIdentifier(), is_array($aForm["selected"]) ? $aForm["selected"] : array()) ? " checked" : "");?> />
      </td>
      <td><span class="spGroup"><?=$sDisplayName?></span></td>
    </tr>
<?
  foreach ($aGroup->getContacts() as $aContact) { ?>
    <tr>
      <td></td>
      <td>
        <img src="<?=$data["root"]?>/resources/Person-white.png" height="16" width="16" />
        <input type="checkbox" name="<?=$aContact->getInputName();?>" <?=(array_key_exists($aContact->getIdentifier(), is_array($aForm["selected"]) ? $aForm["selected"] : array()) ? " checked" : "");?> />
      </td>
      <td><span class="spContact" title="<?=$aContact->_aAttributes["eduPersonPrincipalName"][0];?>"><?=$aContact->_aAttributes["cn"][0];?></span></td>
    </tr>
<?
  } // foreach (contact)
} // foreach (group)
?>
<?php if ($data["showsubmit"] != false) { ?>
    <tr>
      <td colspan="3" align="right">
        <span class="cpActionRow">
          <input type="submit" name="submit" value="Verstuur" />
        </span>
      </td>
    </tr>
<?php } // showsubmit ?>    
  </tbody>

</table>
<? if ($data["form"] && $data["form"] != "embed") {
  echo "</form>";
}
?>
</div><!-- coinGroupSelector -->