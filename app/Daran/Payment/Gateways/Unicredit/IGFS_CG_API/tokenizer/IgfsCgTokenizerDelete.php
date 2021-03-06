<?php

require_once(__DIR__ . "/BaseIgfsCgTokenizer.php");

class IgfsCgTokenizerDelete extends BaseIgfsCgTokenizer {

	public $payInstrToken;

	function __construct() {
		parent::__construct();
	}

	protected function resetFields() {
		parent::resetFields();
		$this->payInstrToken = NULL;
	}

	protected function checkFields() {
		parent::checkFields();
		if ($this->payInstrToken == NULL)
			throw new IgfsMissingParException("Missing payInstrToken");
	}

	protected function buildRequest() {
		$request = parent::buildRequest();
		$request = $this->replaceRequest($request, "{payInstrToken}", $this->payInstrToken);
		return $request;
	}

	protected function setRequestSignature($request) {
		// signature dove il buffer e' cosi composto APIVERSION|TID|SHOPID|PAYINSTRTOKEN
		$fields = array(
				$this->getVersion(), // APIVERSION
				$this->tid, // TID
				$this->shopID, // SHOPID
				$this->payInstrToken); // PAYINSTRTOKEN
		$signature = $this->getSignature($this->kSig, // KSIGN
				$fields);
		$request = $this->replaceRequest($request, "{signature}", $signature);
		return $request;
	}

	protected function parseResponseMap($response) {
		parent::parseResponseMap($response);
	}

	protected function getResponseSignature($response) {
		$fields = array(
				IgfsUtils::getValue($response, "tid"), // TID
				IgfsUtils::getValue($response, "shopID"), // SHOPID
				IgfsUtils::getValue($response, "rc"), // RC
				IgfsUtils::getValue($response, "errorDesc"));// ERRORDESC
		// signature dove il buffer e' cosi composto TID|SHOPID|RC|ERRORDESC
		return $this->getSignature($this->kSig, // KSIGN
				$fields);
	}

	protected function getFileName() {
		return __DIR__ . "/IgfsCgTokenizerDelete.request";
	}

}

?>
