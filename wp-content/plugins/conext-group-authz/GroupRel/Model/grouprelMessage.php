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
	
}
?>