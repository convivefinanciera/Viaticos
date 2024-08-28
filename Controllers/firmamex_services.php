<?php

class FirmamexServices
{
	public $baseUrl = 'https://firmamex.com';
	public $webId = '';
	public $apiKey = '';

	public function __construct($webId, $apiKey)
	{
		$this->webId = $webId;
		$this->apiKey = $apiKey;
	}


	public function getDocumentSet($documentSet)
	{
		return $this->hashAndGet('/api/documentset/' . $documentSet);
	}

	public function createDocumentSet($name)
	{
		$params = new StdClass();
		$params->name = $name;
		return $this->hashAndPost(json_encode($params), '/api/documentset');
	}

	public function closeDocumentSet($params)
	{
		return $this->hashAndPost(json_encode($params), '/api/documentset/close');
	}

	public function request($params)
	{
		return $this->hashAndPost(json_encode($params), '/developers/json');
	}


	public function saveTemplate($params)
	{
		return $this->hashAndPost($params, '/developers/template/save');
	}

	public function getData($params)
	{
		return $this->hashAndPost($params, '/developers/webhook');
	}

	public function timestamp($params)
	{
		return $this->hashAndPost($params, '/api/timestamp');
	}

	public function timestampValidateHash($params)
	{
		return $this->hashAndPost(json_encode($params), '/api/timestamp/validate');
	}

	public function getDocumentFromWebhook($params)
	{
		return $this->hashAndPost($params, '/developers/webhook');
	}

	public function docs($params)
	{
		return $this->hashAndPost(json_encode($params), '/developers/docs');
	}

	public function workflow($firmamexId, $params)
	{
		return $this->hashAndPost(json_encode($params), '/api/' . $firmamexId . '/workflow');
	}

	public function workflowSet($documentSet, $params)
	{
		return $this->hashAndPost(json_encode($params), '/api/' . $documentSet . '/workflowSet');
	}

	public function deleteDocument($params)
	{
		return $this->hashAndPost($params, '/developers/delete/');
	}

	public function updateSigner($firmamexId, $signerId, $params)
	{
		return $this->hashAndPost(json_encode($params), '/api/' . $firmamexId . '/signer/' . $signerId . '/update');
	}

	public function restoreDocument($params)
	{
		return $this->hashAndPost($params, '/developers/restore/');
	}

	public function getReport($ticket)
	{
		return $this->hashAndGet('/api/report/' . $ticket);
	}

    public function getAccountInfo()
    {
        return $this->hashAndGet('/api/account');
    }

	public function getDocument($docType, $ticket)
	{
		return $this->hashAndGet('/api/document/' . $docType . '/' . $ticket);
	}

	public function listDocuments($from, $to, $nextToken)
	{
		return $this->hashAndGet('/api/document?from=' . $from . '&to=' . $to . ($nextToken ? '&nextToken=' . $nextToken : ''));
	}

	public function getCertifiedEmailData($frmxId)
	{
		return $this->hashAndGet('/api/certifiedEmail/' . $frmxId . '/data');
	}

	public function getNom151StampsForDocument($frmxId)
	{
		return $this->hashAndGet('/api/document/' . $frmxId . '/stamps');
	}

	public function getDocumentForStamp($frmxId, $stampHash)
	{
		return $this->hashAndGet('/api/document/' . $frmxId . '/stamp/' . $stampHash . '/pdf');
	}

	private function hashAndGet($path)
	{
		$hmac = hash_hmac('sha256', $path, $this->apiKey, true);
		$hmacb64 = base64_encode($hmac);
		$url = $this->baseUrl . $path;
		return $this->get($hmacb64, $url);
	}

	private function hashAndPost($jsonParams, $path)
	{
		$jsonParams = json_encode(json_decode($jsonParams), JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);
		$hmac = hash_hmac('sha256', $jsonParams, $this->apiKey, true);
		$hmacb64 = base64_encode($hmac);
		$url = $this->baseUrl . $path;

		return $this->post($jsonParams, $hmacb64, $url);
	}

	public function getPageData($fileData, $page)
	{
		$base64Hash = base64_encode(hash('sha256', $fileData, true));

		$hmac = hash_hmac('sha256', $base64Hash, $this->apiKey, true);
		$hmacb64 = base64_encode($hmac);

		$ch = curl_init($this->baseUrl . '/api/pdf/pageData');

		$base64FileString = 'data://application/octet-stream;base64,' . base64_encode($fileData);

		curl_setopt_array($ch, array(
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTP200ALIASES => (array)400,
			CURLOPT_FAILONERROR => false,
			CURLOPT_HTTPHEADER => array(
				"Authorization: signmage {$this->webId}:{$hmacb64}",
				"Content-SHA256: {$base64Hash}"
			),
			CURLOPT_POSTFIELDS => array(
				'file' => new CURLFile($base64FileString, 'application/pdf', 'file.pdf'),
				'page' => $page
			)
		));

		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($response === false) {
			die(curl_error($ch));
		}

		if ($httpcode === 200) {
			$responseData = $response;
		} else {
			$responseData = "Response code: {$httpcode}; Error message: {$response}";
		}

		curl_close($ch);
		return $responseData;
	}

	public function nom151Stamp($fileData)
	{
		$base64Hash = base64_encode(hash('sha256', $fileData, true));

		$hmac = hash_hmac('sha256', $base64Hash, $this->apiKey, true);
		$hmacb64 = base64_encode($hmac);


		$ch = curl_init($this->baseUrl . '/api/nom151/stamp');

		$base64FileString = 'data://application/octet-stream;base64,' . base64_encode($fileData);


		curl_setopt_array($ch, array(
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTP200ALIASES => (array)400,
			CURLOPT_FAILONERROR => false,
			CURLOPT_HTTPHEADER => array(
				"Authorization: signmage {$this->webId}:{$hmacb64}",
				"Content-SHA256: {$base64Hash}"
			),
			CURLOPT_POSTFIELDS => array(
				'file' => new CURLFile($base64FileString, 'application/pdf', 'file.pdf')
			)
		));

		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($response === false) {
			die(curl_error($ch));
		}

		if ($httpcode === 200) {
			$responseData = $response;
		} else {
			$responseData = "Response code: {$httpcode}; Error message: {$response}";
		}

		curl_close($ch);
		return $responseData;
	}


	private function get($hmacb64, $path)
	{

		$ch = curl_init($path);

		curl_setopt_array($ch, array(
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTP200ALIASES => (array)400,
			CURLOPT_FAILONERROR => false,
			CURLOPT_HTTPHEADER => array(
				"Authorization: signmage {$this->webId}:{$hmacb64}",
				'Content-Type: application/json'
			)
		));

		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($response === false) {
			die(curl_error($ch));
		}

		if ($httpcode === 200) {
			$responseData = $response;
		} else {
			$responseData = "Response code: {$httpcode}; Error message: {$response}";
		}

		curl_close($ch);
		return $responseData;
	}

	private function post($params, $hmacb64, $path)
	{

		$ch = curl_init($path);

		curl_setopt_array($ch, array(
			CURLOPT_POST => true,
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_HTTP200ALIASES => (array)400,
			CURLOPT_FAILONERROR => false,
			CURLOPT_SSL_VERIFYPEER => false,
			CURLOPT_HTTPHEADER => array(
				"Authorization: signmage {$this->webId}:{$hmacb64}",
				'Content-Type: application/json'
			),
			CURLOPT_POSTFIELDS => $params
		));

		$response = curl_exec($ch);
		$httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

		if ($response === false) {
			die(curl_error($ch));
		}

		if ($httpcode === 200) {
			$responseData = $response;
		} else {
			$responseData = "Response code: {$httpcode}; Error message: {$response}";
		}

		curl_close($ch);
		return $responseData;
	}
}
