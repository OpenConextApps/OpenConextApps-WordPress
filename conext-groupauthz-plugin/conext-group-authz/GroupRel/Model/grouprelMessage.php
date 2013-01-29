<?php
class grouprelMessage {
	
	public $_subject;
	public $_content;
	
	public $_sender;
	public $_recipients = array();

	public function addRecipient($recipient) {
		if (is_array($this->_recipients)) {
			$this->_recipients[] = $recipient;
		} else {
			$this->_recipients = array($recipient);
		}
	} 

	public function __toString() {
	if (count($this->_recipients) > 0) {
		$r = $this->_recipients[0]; 
	} else {
		$r = "[[undefined]]";
	}
	return <<<HERE
From: {$this->_sender}
To: {$r}
Subject: {$this->_subject}
{$this->_content}   
HERE
;
	}
	
}
?>
